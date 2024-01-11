<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <div class="flex justify-end">
                            <a href="{{ route('tournaments.create') }}" class="bg-gray-500 dark:bg-green-500 hover:bg-gray-600 dark:hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                                Criar Novo Torneio
                            </a>
                        </div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Lista de Torneios</h1>
                        @if($tournaments->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Não há torneios cadastrados.</p>
                        @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($tournaments as $tournament)
                            <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg tournament-card">
                                <a href="{{ route('tournaments.show', $tournament) }}"> <!-- Link para ver detalhes -->
                                    <!-- Conteúdo do torneio, como nome, participantes, etc. -->
                                </a>
                                <div class="p-6"> <!-- Aumentado o padding aqui -->
                                    <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $tournament->name }}</h5> <!-- Adicionado margem abaixo do título -->
                                    <!-- Exibir informações do torneio, como participantes, data de início, status, etc. -->

                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('tournaments.show', $tournament) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                            Ver Detalhes
                                        </a>
                                        @if(auth()->check() && !$tournament->hasUserReachedMaxDecks(auth()->user()))
                                        <form method="GET" action="{{ route('tournaments.selectDeck', $tournament->id) }}">
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                                Participar
                                            </button>
                                        </form>
                                        @endif

                                        @if(auth()->user()->id === $tournament->user_id)
                                        <button onclick="confirmDeleteTournament({{ $tournament->id }})" class="text-red-500 hover:text-red-600">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <!-- Modal de Confirmação -->
                            <div id="confirmDeleteModal{{ $tournament->id }}" class="modal-overlay hidden" data-is-owner="true">
                                <div class="modal">
                                    <p>Tem certeza que deseja apagar este torneio?</p>
                                    <div class="flex justify-end space-x-4 mt-4">
                                        <button onclick="document.getElementById('confirmDeleteModal{{ $tournament->id }}').style.display='none'" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                                            Cancelar
                                        </button>
                                        <button onclick="if (document.getElementById('confirmDeleteModal{{ $tournament->id }}').getAttribute('data-is-owner') === 'true') document.getElementById('delete-form-{{ $tournament->id }}').submit()" class="bg-red-600 text-white rounded py-2 px-4">
                                            Confirmar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulário de Apagar Torneio -->
                            <form id="delete-form-{{ $tournament->id }}" action="{{ route('tournaments.destroy', $tournament->id) }}" method="POST" style="display: none;">
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
        function confirmDeleteTournament(tournamentId) {

            var modal = document.getElementById('confirmDeleteModal' + tournamentId);
            console.log("Valor de data-is-owner:", modal.getAttribute('data-is-owner'));
            // Verificação de propriedade antes de mostrar o modal
            if (modal && modal.getAttribute('data-is-owner') === 'true') {
                console.log("oxe")
                modal.style.display = 'block';
            }
        }
    </script>
</x-app-layout>
