<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto p-4">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Detalhes do Deck</h1>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Nome do Deck: {{ $deck->name }}</h2>
                            <p class="text-gray-600 dark:text-gray-400">Comandante: {{ $deck->commander_name }}</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Lista de Cartas</h2>
                            <ul>
                                @foreach($cartasFormatadas as $carta)
                                <li>
                                    <span class="font-bold text-white dark:text-gray-400">{{ $carta['quantidade'] }}</span>&ensp;<span class="relative cursor-pointer hover:underline dark:text-gray-400" data-tippy-content='<img src="{{ $carta['imagem'] }}" alt="{{ $carta['nome'] }}" class="max-w-full h-auto">'>{!! $carta['nome'] !!}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            tippy('[data-tippy-content]', {
                placement: 'right', // Define a posição do tooltip
                allowHTML: true, // Permite conteúdo HTML no tooltip
            });
        });
    </script>
</x-app-layout>
