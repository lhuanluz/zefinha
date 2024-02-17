<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DeckController;
use Symfony\Component\DomCrawler\Crawler;

class DashboardController extends Controller
{
    protected $deckController;

    public function __construct(DeckController $deckController)
    {
        $this->deckController = $deckController;
    }

    public function index()
    {

        return view('dashboard');
    }

    private function extractPriceFromHtml($html)
    {
        // Expressão regular para encontrar o preço da carta
        $pattern = '/<td class="min products-table__price[^>]*>(.*?)<\/b>/s';

        // Executar a expressão regular
        preg_match($pattern, $html, $matches);

        // Se houver uma correspondência encontrada
        if (!empty($matches[1])) {
            // Limpar a correspondência para obter apenas o preço
            $price = strip_tags($matches[1]);

            return $price;
        }

        // Se não houver correspondência, retornar null
        return null;
    }

    public function fetchDeckList($commanderName)
    {
        $client = new Client();
        // Substitui espaços e vírgulas por hífens, e remove caracteres especiais
        $formattedCommanderName = preg_replace('/[ ,]+/', '-', $commanderName);
        $url = 'https://edhrec.com/average-decks/' . strtolower(urlencode($formattedCommanderName)) . '/budget';

        try {
            $response = $client->request('GET', $url);
            $body = $response->getBody()->getContents();

            // Extrai o conteúdo específico dentro do elemento <code>
            $matches = [];
            preg_match('/<code>(.*?)<\/code>/s', $body, $matches);
            $deckList = $matches[1] ?? 'Lista não encontrada.';

            return response()->json([
                'success' => true,
                'deckList' => $deckList,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar a lista do deck.'
            ], 500);
        }
    }
}
