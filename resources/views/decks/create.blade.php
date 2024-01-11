<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Cadastrar Deck</h1>
        <form method="POST" action="{{ route('decks.store') }}" class="mt-6">
            @csrf
            <div class="form-group mb-4">
                <label for="name" class="block text-lg text-gray-700 dark:text-gray-300">Nome do Deck:</label>
                <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
            </div>
            <div class="form-group mb-4">
                <label for="commander_name" class="block text-lg text-gray-700 dark:text-gray-300">Nome do Comandante:</label>
                <select id="commander_name" name="commander_name" required class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 select2">
                    <option value="" disabled selected>Escolha o Comandante</option>
                    @foreach($commanders as $commander)
                    <option value="{{ $commander['name'] }}">{{ $commander['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-4">
                <label for="urlLigamagic" class="block text-lg text-gray-700 dark:text-gray-300">URL do Ligamagic:</label>
                <input type="url" id="urlLigamagic" required name="urlLigamagic" class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500" />
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md focus:outline-none focus:shadow-outline">Cadastrar</button>
        </form>
    </div>

</x-app-layout>
