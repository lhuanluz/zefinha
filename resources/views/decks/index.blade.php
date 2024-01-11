<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <div class="flex justify-end">
                            <a href="{{ route('decks.create') }}" class="bg-gray-500 dark:bg-green-500 hover:bg-gray-600 dark:hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                                Criar Novo Deck
                            </a>
                        </div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Lista de Decks</h1>
                        @if($decks->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Não há decks cadastrados.</p>
                        @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($decks as $deck)
                            <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg deck-card">
                                <a href="{{ route('decks.show', $deck) }}"> <!-- Link para ver detalhes -->
                                    @if($deck->commander_image)
                                    <img src="{{ $deck->commander_image }}" alt="Comandante: {{ $deck->commander_name }}" class="w-full h-auto">
                                    @endif
                                </a>
                                <div class="p-6"> <!-- Aumentado o padding aqui -->
                                    <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $deck->name }}</h5> <!-- Adicionado margem abaixo do título -->
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">Comandante: {{ $deck->commander_name }}</p> <!-- Adicionado margem abaixo do comandante -->

                                    @if($deck->valorMin)
                                    <p class="text-green-600 dark:text-green-400 mb-2">Valor Mínimo: R$ {{ $deck->valorMin }}</p> <!-- Exibir valorMin se estiver definido -->
                                    @endif

                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('decks.show', $deck) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                            Ver Detalhes
                                        </a>

                                        @if($deck->tournaments->isEmpty())
                                        <!-- Se não estiver associado a nenhum torneio, mostrar o botão "Apagar" -->
                                        <form method="POST" action="{{ route('decks.destroy', $deck->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                                Apagar
                                            </button>
                                        </form>
                                        @else
                                        <!-- Se estiver associado a algum torneio, mostrar uma mensagem -->
                                        <p class="text-red-600">Este deck está associado a um torneio e não pode ser apagado.</p>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <!-- Modal de Confirmação -->
                            <div id="confirmDeleteModal{{ $deck->id }}" class="modal-overlay hidden">
                                <div class="modal">
                                    <p>Tem certeza que deseja apagar este deck?</p>
                                    <div class="flex justify-end space-x-4 mt-4">
                                        <button onclick="document.getElementById('confirmDeleteModal{{ $deck->id }}').style.display='none'" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                                            Cancelar
                                        </button>
                                        <button onclick="document.getElementById('delete-form-{{ $deck->id }}').submit()" class="bg-red-600 text-white rounded py-2 px-4">
                                            Confirmar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulário de Apagar Deck -->
                            <form id="delete-form-{{ $deck->id }}" action="{{ route('decks.destroy', $deck->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(deckId) {
            var modal = document.getElementById('confirmDeleteModal' + deckId);
            modal.style.display = 'block';
        }
    </script>
</x-app-layout>
