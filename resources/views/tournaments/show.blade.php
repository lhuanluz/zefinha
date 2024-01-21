<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Coluna Esquerda: Banner do Torneio -->
                            <div class="mb-4">

                                <img src="{{ $tournament->photo_url }}" alt="{{ $tournament->name }}" class="w-full h-auto">
                                <!-- Ranking dos jogadores -->
                                </br>
                                <div class="ranking">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">&ensp;Ranking dos Jogadores</h2>
                                    @foreach($rankings as $index => $ranking)
                                    <div class="ranking__row">
                                        <span class="rank"> </span>
                                        <div class="avatar" style="background-image: url({{ $ranking['user']->profile_photo_url }})"></div>
                                        <div class="minimum-progress"></div>
                                        <div class="progress">
                                            <div class="progress__bar" style="flex-basis: {{ $ranking['totalPoints'] }}% ">
                                                <div class="truncate-text">
                                                    <span class="name">{{ $ranking['user']->name }}</span>
                                                </div>
                                            </div>
                                            <div class="progress__value">{{ $ranking['totalPoints'] }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Coluna Direita: Informações do Torneio -->
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">{{ $tournament->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">Rodadas: {{ $tournament->rounds }}</p>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">Data de Início: {{ $tournament->start_date }}</p>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">Máximo de Decks por Usuário: {{ $tournament->max_decks_per_user }}</p>

                                <!-- Botão para iniciar o torneio (apenas para o dono do torneio) -->
                                @if(auth()->check() && $tournament->status === 'aberto' && !$tournament->hasUserReachedMaxDecks(auth()->user()))
                                <form method="GET" action="{{ route('tournaments.selectDeck', $tournament->id) }}">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                        Participar
                                    </button>
                                </form>
                                @endif
                                @if(auth()->user()->id === $tournament->user_id && $tournament->status === 'aberto')
                                <form method="POST" action="{{ route('tournaments.start', $tournament) }}">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                        Iniciar Torneio
                                    </button>
                                </form>
                                @endif
                                <!-- Formulário de Pontuação para o Dono do Torneio -->
                                @if(auth()->user()->id === $tournament->user_id && $tournament->status === 'em_andamento')
                                <div class="max-w-lg mx-auto rounded overflow-hidden shadow-lg bg-white dark:bg-gray-800 mt-8">
                                    <div class="px-6 py-4">
                                        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 mb-4">Inserir Pontos da Rodada</h2>
                                        <form method="POST" action="{{ route('tournaments.updateRoundPoints', $tournament) }}">
                                            @csrf

                                            <!-- Campo para selecionar a rodada -->
                                            <div class="mb-4">
                                                <label for="round_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Selecione a Rodada:</label>
                                                <select name="round_id" id="round_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                    @foreach($tournament->tournamentRounds as $round)
                                                    <option value="{{ $round->id }}">Rodada {{ $round->round_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @foreach($participants as $participant)
                                            <div class="mb-4">
                                                <h3 class="text-lg font-semibold">{{ $participant->name }}</h3>
                                                <!-- Campo de entrada de pontos -->
                                                <div>
                                                    <label for="points_{{ $participant->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                        Pontos:
                                                    </label>
                                                    <input type="number" name="points[{{ $participant->id }}]" id="points_{{ $participant->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                </div>
                                            </div>
                                            @endforeach

                                            <!-- Botão de submissão -->
                                            <div class="flex justify-end">
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                                    Atualizar Pontos
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                <!-- Lista de decks participantes -->
                                @if(!$tournament->decks->isEmpty())
                                <div class="max-w-lg mx-auto rounded overflow-hidden shadow-lg bg-white dark:bg-gray-800 mt-8">
                                    <div class="px-6 py-4">
                                        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 mb-4">Decks Participantes</h2>
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach($tournament->decks as $deck)
                                            <div class="mb-4">
                                                <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg tournament-card">
                                                    <div class="p-6">
                                                        @if($tournament->status !== 'aberto')
                                                        <h5 class="text-lg font-semibold text-blue-600 dark:text-white mb-2">
                                                            <a href="{{ route('decks.show', $deck) }}">{{ $deck->name }}</a>
                                                        </h5>
                                                        @else
                                                        <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                                                            {{ $deck->user_id === auth()->user()->id ? $deck->name : 'Deck X' }}
                                                        </h5>
                                                        @endif
                                                        <p class="text-gray-600 dark:text-gray-400 mb-2">Valor Mínimo: {{ $deck->valorMin }}</p>
                                                        <p class="text-gray-600 dark:text-gray-400 mb-2">Dono: {{ $deck->user->name }}</p>
                                                        @if(auth()->user()->id === $deck->user_id && $tournament->status === 'aberto')
                                                        <form method="POST" action="{{ route('tournaments.removeDeck', [$tournament, $deck]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                                                Remover do Torneio
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
