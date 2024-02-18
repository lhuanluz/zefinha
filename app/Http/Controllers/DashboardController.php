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

    public function fetchDeckList($commanderName)
    {
        $client = new Client();
        // Substitui espaços e vírgulas por hífens, e remove caracteres especiais
        $formattedCommanderName = preg_replace('/[ ,]+/', '-', $commanderName);
        $url = 'https://edhrec.com/average-decks/' . strtolower(urlencode($formattedCommanderName)) . '';

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
