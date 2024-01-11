<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;



class DeckController extends Controller
{
    public function index()
    {
        $decks = auth()->user()->decks;
        foreach ($decks as $deck) {
            $deck->commander_image = $this->getDeckCommanderImage($deck->commander_name);
        }
        return view('decks.index', compact('decks'));
    }

    public function create()
    {
        $commanders = $this->fetchAllCommanders();
        return view('decks.create', compact('commanders'));
    }
    public function fetchAllCommanders()
    {
        // Verifique se a lista de comandantes já está em cache
        if (Cache::has('all_commanders')) {
            return Cache::get('all_commanders');
        }

        $client = new Client();
        $currentPage = 1;
        $allCommanders = [];

        do {
            // Faça uma solicitação à API com o número da página atual
            $response = $client->get("https://api.scryfall.com/cards/search?q=is%3Acommander&page={$currentPage}");

            // Decodifique a resposta JSON
            $data = json_decode($response->getBody(), true);

            // Adicione os comandantes desta página à matriz $allCommanders
            $allCommanders = array_merge($allCommanders, $data['data']);

            // Verifique se há uma próxima página
            $currentPage++;
        } while (!empty($data['has_more']));

        // Armazene a lista de comandantes em cache por 1 mês
        Cache::put('all_commanders', $allCommanders, now()->addMonths(1));

        return $allCommanders;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'commander_name' => 'required|string|max:255',
            'urlLigamagic' => 'required|url',
        ]);

        $deck = new Deck();
        $deck->name = $request->name;
        $deck->commander_name = $request->commander_name;
        $deck->user_id = auth()->user()->id;
        $deck->urlLigamagic = $request->urlLigamagic;

        // Buscar o campo "valorMin" se a URL do Ligamagic estiver presente
        $valorMin = $this->buscarValorMinNoLigamagic($request->urlLigamagic);
        if ($valorMin !== null) {
            $deck->valorMin = $valorMin;
        }

        // Obter o ID a partir da URL do Ligamagic
        $id = $this->obterIdDaUrlLigamagic($request->urlLigamagic);

        // Realizar o download do arquivo de texto usando o ID obtido
        $downloadedText = $this->downloadTextFile($id);

        if ($downloadedText !== null) {
            // Remover o comandante da decklist
            $decklistWithoutCommander = $this->removeCommanderFromDecklist($downloadedText, $request->commander_name);

            // Salvar a decklist no campo "cards"
            $deck->cards = $decklistWithoutCommander;
        } else {
            // Tratar o caso de falha no download ou leitura do arquivo
            return redirect()->route('decks.create')->with('error', 'Não foi possível baixar ou ler o arquivo do Ligamagic.');
        }

        $deck->save();

        return redirect()->route('decks.index')->with('success', 'Deck cadastrado com sucesso!');
    }

    private function removeCommanderFromDecklist($decklist, $commanderName)
    {
        // Separar as linhas da decklist
        $lines = explode("\n", $decklist);

        // Inicializar um array para armazenar as linhas sem o comandante
        $decklistWithoutCommander = [];

        // Obter o primeiro lado do comandante
        list($firstSideCommander) = explode(' // ', $commanderName, 2);
        //dd($firstSideCommander);
        // Percorrer as linhas da decklist e adicionar apenas as que não contêm o comandante
        foreach ($lines as $line) {
            // Obter o primeiro lado da linha
            list($firstSideLine) = explode(' // ', $line, 2);

            if (stripos($firstSideLine, $firstSideCommander) === false) {
                $decklistWithoutCommander[] = $line;
            }
        }

        // Unir as linhas novamente em uma única string
        return implode("\n", $decklistWithoutCommander);
    }

    private function obterIdDaUrlLigamagic($url)
    {
        // Extrair o ID da URL usando uma expressão regular
        preg_match('/[?&]id=([^&]+)/', $url, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    private function downloadTextFile($id)
    {
        try {
            // Montar a URL completa com o ID
            $url = "https://www.ligamagic.com.br/?view=dks/exportar&type=4&id=$id";

            // Fazer o download do arquivo
            $response = Http::get($url);
//            dd($response->body());
            // Verificar se a resposta foi bem-sucedida
            if ($response->successful()) {
                // Ler o conteúdo do arquivo
                return $response->body();
            }
        } catch (\Exception $e) {
            // Tratar exceções, se ocorrerem
            return null;
        }

        return null;
    }
    private function buscarValorMinNoLigamagic($url)
    {
        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);
        $crawler = $browser->getCrawler();

        $valorMin = $crawler->filter('.price-head.lower.price-selected')->text();
        $valorMin = trim( str_replace( "R$", null, $valorMin ) );
        $valorMin = str_replace( ",", ".", $valorMin );

        // Você pode precisar de alguma lógica adicional para extrair o valor correto da string

        return $valorMin;
    }

    public function show(Deck $deck)
    {
        $cartasTexto = explode("\n", $deck->cards);
        $cartasFormatadas = [];

        foreach ($cartasTexto as $cartaTexto) {
            list($quantidade, $nomeCarta) = explode(' ', $cartaTexto, 2);
            $nomeCarta = trim($nomeCarta);
            $dadosCarta = $this->buscarDadosCartaNoCache($nomeCarta);

            if (!$dadosCarta) {
                // Se os dados da carta não estiverem em cache, busque na API da Scryfall e armazene em cache
                $dadosCarta = $this->buscarDadosCartaNoScryfall($nomeCarta);
                if ($dadosCarta) {
                    $this->armazenarDadosCartaNoCache($nomeCarta, $dadosCarta);
                }
            }

            if ($dadosCarta) {
                // Buscar informações adicionais da carta no site "ligamagic.com.br"
                $cartasFormatadas[] = [
                    'quantidade' => $quantidade,
                    'nome' => $dadosCarta['nome'],
                    'imagem' => $dadosCarta['imagem'],
                    'mana_cost' => $dadosCarta['mana_cost'],
                    'type_line' => $dadosCarta['type_line'],
                    // Outros dados da carta, se necessário
                ];
            }
        }

        return view('decks.show', compact('deck', 'cartasFormatadas'));
    }

    public function buscarDadosCartaNoCache($cardName)
    {
        return Cache::get('carta_dados_' . $cardName);
    }

    public function armazenarDadosCartaNoCache($cardName, $dadosCarta)
    {
        Cache::put('carta_dados_' . $cardName, $dadosCarta, now()->addMonths(1)); // Cache válido por 1 mês
    }

    public function buscarDadosCartaNoScryfall($cardName)
    {
        $client = new Client();
        $response = $client->get("https://api.scryfall.com/cards/named?exact=" . urlencode($cardName));

        $data = json_decode($response->getBody(), true); // Converta para array associativo
        $dadosCarta = [];
//        dd($data);
        if (isset($data['card_faces'])) {
            if ($data['layout'] == 'adventure'){
                $dadosCarta['nome'] = $data['name'];
                $dadosCarta['imagem'] = $data['image_uris']['normal'];
                $dadosCarta['mana_cost'] = $data['mana_cost'];
                $dadosCarta['type_line'] = $data['type_line'];
            }else{
                $dadosCarta['nome'] = $data['card_faces'][0]['name'];
                $dadosCarta['imagem'] = $data['card_faces'][0]['image_uris']['normal'];
                $dadosCarta['mana_cost'] = $data['card_faces'][0]['mana_cost'];
                $dadosCarta['type_line'] = $data['card_faces'][0]['type_line'];
            }
        } elseif (isset($data['name'])) {
            $dadosCarta['nome'] = $data['name'];
            $dadosCarta['imagem'] = $data['image_uris']['normal'];
            $dadosCarta['mana_cost'] = $data['mana_cost'];
            $dadosCarta['type_line'] = $data['type_line'];
        }


        return $dadosCarta;
    }



    public function getDeckCommanderImage($cardName)
    {
        $client = new Client();
        $response = $client->get("https://api.scryfall.com/cards/named?exact=" . urlencode($cardName));

        $data = json_decode($response->getBody());
        // Verifica se a carta é double faced e retorna a imagem da primeira face
        if (isset($data->card_faces)) {
            return $data->card_faces[0]->image_uris->art_crop;
        }

        return $data->image_uris->art_crop ?? null;
    }

    public function destroy($id)
    {
        $deck = Deck::findOrFail($id);

        // Adicione verificações de segurança conforme necessário
        // Por exemplo, verificar se o usuário atual é o proprietário do deck

        $deck->delete();

        return redirect()->route('decks.index')->with('success', 'Deck apagado com sucesso.');
    }


}
