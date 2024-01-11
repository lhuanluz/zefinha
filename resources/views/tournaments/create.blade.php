<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Criar Novo Torneio</h1>
                        <form method="POST" action="{{ route('tournaments.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-lg text-gray-700 dark:text-gray-300">Nome do Torneio:</label>
                                <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
                            </div>
                            <div class="mb-4">
                                <label for="rounds" class="block text-lg text-gray-700 dark:text-gray-300">Quantidade de Rodadas:</label>
                                <input type="number" id="rounds" name="rounds" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
                            </div>
                            <div class="mb-4">
                                <label for="photo_url" class="block text-lg text-gray-700 dark:text-gray-300">URL da Foto do Torneio:</label>
                                <input type="text" id="photo_url" name="photo_url" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
                            </div>

                            <div class="mb-4">
                                <label for="start_date" class="block text-lg text-gray-700 dark:text-gray-300">Data de Início:</label>
                                <input type="datetime-local" id="start_date" name="start_date" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
                            </div>
                            <div class="mb-4">
                                <label for="max_decks_per_user" class="block text-lg text-gray-700 dark:text-gray-300">Máximo de Decks por Usuário:</label>
                                <input type="number" id="max_decks_per_user" name="max_decks_per_user" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
                            </div>
                            <!-- Outros campos do formulário (usuários participantes, etc.) aqui -->
                            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md focus:outline-none focus:shadow-outline">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
