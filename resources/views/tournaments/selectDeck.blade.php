<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Selecione um Deck</h1>

                        @if($userDecks->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Você não tem decks disponíveis para este torneio.</p>
                        @else
                        <form method="POST" action="{{ route('tournaments.join', $tournament) }}">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($userDecks as $deck)
                                @if (!$tournament->decks->contains($deck))
                                <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg tournament-card">
                                    <div class="p-6">
                                        <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $deck->name }}</h5>
                                        <p class="text-gray-600 dark:text-gray-400 mb-2">Comandante: {{ $deck->commander_name }}</p>
                                        <p class="text-gray-600 dark:text-gray-400 mb-2">Valor Mínimo: {{ $deck->valorMin }}</p>
                                        <button type="submit" name="selected_deck_id" value="{{ $deck->id }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                            Selecionar
                                        </button>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
