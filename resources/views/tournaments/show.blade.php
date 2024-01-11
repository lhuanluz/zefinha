<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">{{ $tournament->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Rodadas: {{ $tournament->rounds }}</p>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Data de Início: {{ $tournament->start_date }}</p>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Máximo de Decks por Usuário: {{ $tournament->max_decks_per_user }}</p>

                        <!-- Botão para iniciar o torneio (apenas para o dono do torneio) -->
                        @if(auth()->user()->id === $tournament->user_id && $tournament->status === 'aberto')
                        <form method="POST" action="{{ route('tournaments.start', $tournament) }}">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Iniciar Torneio
                            </button>
                        </form>
                        @endif

                        <!-- Lista de decks participantes -->
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-4 mb-2">Decks Participantes:</h2>
                        @if($tournament->decks->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Não há decks participantes neste torneio.</p>
                        @else
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
