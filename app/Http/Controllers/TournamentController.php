<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\Deck;
use App\Models\UserRoundPoints;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::all();
        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rounds' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'max_decks_per_user' => 'required|integer|min:1',
            'photo_url' => 'required|url',
        ]);

        $tournament = new Tournament([
            'name' => $request->name,
            'rounds' => $request->rounds,
            'start_date' => $request->start_date,
            'max_decks_per_user' => $request->max_decks_per_user,
            'photo_url' => $request->photo_url,
        ]);

        $tournament->user_id = auth()->user()->id;
        $tournament->user_id = auth()->user()->id;

        $tournament->save();

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Torneio cadastrado com sucesso!');
    }

    public function show(Tournament $tournament)
    {
        $tournament->load('tournamentRounds');

        // Obter participantes únicos
        $participants = $tournament->decks->pluck('user')->unique('id');

        // Calcular a soma dos pontos para cada jogador
        $rankings = collect();
        foreach ($participants as $participant) {
            $totalPoints = UserRoundPoints::where('user_id', $participant->id)
                ->whereHas('round', function ($query) use ($tournament) {
                    $query->where('tournament_id', $tournament->id);
                })
                ->sum('points');

            $rankings->push([
                'user' => $participant,
                'totalPoints' => $totalPoints
            ]);
        }

        // Ordenar por pontuação, do maior para o menor
        $rankings = $rankings->sortByDesc('totalPoints');

        return view('tournaments.show', compact('tournament', 'participants', 'rankings'));
    }

    public function removeDeck(Tournament $tournament, Deck $deck)
    {
        if (!$tournament->isOwner(auth()->user())) {
            return redirect()->back()->with('error', 'Você não tem permissão para remover decks deste torneio.');
        }

        if (!$tournament->decks->contains($deck)) {
            return redirect()->back()->with('error', 'Este deck não está inscrito neste torneio.');
        }

        $tournament->decks()->detach($deck->id);

        return redirect()->back()->with('success', 'O deck foi removido do torneio com sucesso.');
    }

    public function selectDeck(Tournament $tournament)
    {
        $userDecks = auth()->user()->decks;

        return view('tournaments.selectDeck', compact('tournament', 'userDecks'));
    }

    public function start(Tournament $tournament)
    {
        // Verifique se o usuário é o dono do torneio
        if (!$tournament->isOwner(auth()->user())) {
            return redirect()->back()->with('error', 'Você não tem permissão para iniciar este torneio.');
        }

        // Verifique se o torneio já está no estado "aberto"
        if ($tournament->status !== 'aberto') {
            return redirect()->back()->with('error', 'Este torneio não pode ser iniciado.');
        }

        // Atualize o status do torneio para "iniciado"
        $tournament->status = 'em_andamento';
        $tournament->save();

        // Criar rodadas para o torneio
        for ($i = 1; $i <= $tournament->rounds; $i++) {
            $tournament->rounds()->create([
                'round_number' => $i,
                // outras informações da rodada, se necessário
            ]);
        }

        return redirect()->back()->with('success', 'O torneio foi iniciado com sucesso.');
    }


    public function join(Request $request, Tournament $tournament)
    {
        $selectedDeckId = $request->input('selected_deck_id');

        if ($tournament->status !== 'aberto') {
            return redirect()->back()->with('error', 'Este torneio não está aberto para inscrições.');
        }

        $user = auth()->user();
        if ($user->tournaments()->where('tournament_id', $tournament->id)->count() >= $tournament->max_decks_per_user) {
            return redirect()->back()->with('error', 'Você atingiu o limite de decks inscritos neste torneio.');
        }

        $deck = $user->decks()->find($selectedDeckId);
        if (!$deck) {
            return redirect()->back()->with('error', 'O deck selecionado não pertence a você.');
        }

        if ($tournament->decks->contains($deck)) {
            return redirect()->back()->with('error', 'Este deck já está inscrito neste torneio.');
        }

        $tournament->decks()->attach($deck->id, ['user_id' => $user->id]);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Você se inscreveu no torneio com sucesso!');
    }
    public function destroy(Tournament $tournament)
    {
        // Verifique se o usuário é o dono do torneio
        if (!$tournament->isOwner(auth()->user())) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir este torneio.');
        }

        // Exclua o torneio do banco de dados
        $tournament->delete();

        return redirect()->route('tournaments.index')->with('success', 'Torneio excluído com sucesso.');
    }

    public function updateRoundPoints(Request $request, Tournament $tournament)
    {
        if (auth()->user()->id !== $tournament->user_id || $tournament->status !== 'em_andamento') {
            return back()->withErrors('Você não tem permissão para realizar esta ação.');
        }

        $roundId = $request->input('round_id');
        $pointsData = $request->input('points', []);

        DB::transaction(function () use ($pointsData, $roundId) {
            foreach ($pointsData as $userId => $point) {
                UserRoundPoints::updateOrCreate(
                    ['user_id' => $userId, 'round_id' => $roundId],
                    ['points' => $point]
                );
            }
        });

        return back()->withSuccess('Pontos atualizados com sucesso.');
    }

}
