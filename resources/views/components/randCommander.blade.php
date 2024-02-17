<div class="bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700" style="max-width: 300px;">
    <center class="text-2xl font-medium text-gray-900 dark:text-white" >
        Comandante Aleatório
    </center>

    <div class="mt-6">
        @if($commanderImage)
            <img src="{{ $commanderImage }}" alt="Comandante Aleatório" style="max-width: 100%; height: auto;">
        @else
            <p>Nenhuma imagem disponível para o comandante aleatório.</p>
        @endif
    </div>
</div>

