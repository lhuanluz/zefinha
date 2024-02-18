<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg w-1/2 mr-4">
                <x-welcome />
            </div>
            <link rel="stylesheet" href="{{ asset('assets/css/randomCard.css') }}">
            <div class="">
                <div class="cardInfo">
                    <div class="flip-card flipped">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">
                                <img src="" alt="Commander" class="cardInfo__img">
                            </div>
                            <div class="flip-card-back">
                                <img src="./images/cardback.png" alt="Commander" class="cardInfo__img--back">
                            </div>
                        </div>
                    </div>
                    <div class="cardDetails">
                        <div>
                            <h2 class="cardDetails__name">Nome</h2>
                            <p class="cardDetails__type">Tipo</p>
                            <p class="cardDetails__cost">Custo de Mana</p>
                            <p class="cardDetails__colorId">Identidade de Cor</p>
                            <p class="cardDetails__rank"><span class="rank-value">--</span></p>
                            <a class="cardDetails__edhRec" target="_blank" href="https://edhrec.com/">EDH Rec</a>
                            <div class="cardDetails__oracleText">

                                </br>
                                <button id="generateDeckListBtn" class="btn">Gerar Lista do Deck</button>
                                <textarea id="deckListTextarea" class="deck-list-textarea" style="width:100%; height:200px; display:none; color: black !important;"></textarea>
                                </br>

                            </div>



                        </div>
                        <button class="cardDetails__randomCardBtn btn">Random</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
<script>

    const img = document.querySelector('.cardInfo__img');
    const name = document.querySelector('.cardDetails__name');
    const type = document.querySelector('.cardDetails__type');
    const manacost = document.querySelector('.cardDetails__cost');
    const colorId = document.querySelector('.cardDetails__colorId');
    const edhRec = document.querySelector('.cardDetails__edhRec');
    const randomBtn = document.querySelector('.cardDetails__randomCardBtn');
    const flipCard = document.querySelector('.flip-card');
    const rank = document.querySelector('.cardDetails__rank .rank-value');
    const SCRYFALL_URL = 'https://api.scryfall.com/cards/random?q=is%3Acommander';

    // Display promise errors
    const handleErrors = (err) => {
        console.log('Oh no, something went wrong!');
        console.log(err);
    };

    document.getElementById('generateDeckListBtn').addEventListener('click', fetchAndDisplayDeckList);

    const fetchEDHRECRank = async (commanderName) => {
        const sanitizedCommanderName = replaceSpecialCharacters(commanderName);
        const url = `https://edhrec.com/commanders/${encodeURIComponent(sanitizedCommanderName)}`;

        try {
            const response = await fetch(url);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const rankElement = doc.querySelector('.CardLabel_label__iAM7T');

            if (rankElement) {
                return rankElement.textContent.trim();
            } else {
                return 'Rank indisponível';
            }
        } catch (error) {
            console.error('Erro ao buscar o rank no EDHREC:', error);
            return 'Erro ao buscar o rank';
        }
    };

    async function fetchAndDisplayDeckList() {
        const commanderName = name.textContent; // Certifique-se de que isso captura o nome correto do comandante.
        try {
            const response = await fetch(`/fetch-deck-list/${encodeURIComponent(commanderName)}`);
            const data = await response.json();

            if (data.success) {
                const deckListTextarea = document.getElementById('deckListTextarea');
                // Decodifica as entidades HTML antes de inserir o texto
                const decodedDeckList = decodeHtmlEntities(data.deckList);
                deckListTextarea.value = decodedDeckList;
                deckListTextarea.style.display = ''; // Torna a textarea visível
                deckListTextarea.style.color = 'black'; // Define a cor do texto como preto
            } else {
                throw new Error(data.error || 'Falha ao obter a lista do deck.');
            }
        } catch (error) {
            console.error('Erro ao buscar a lista do deck:', error);
            alert('Falha ao buscar a lista do deck.');
        }
    }


    const fetchCard = async () => {
        const res = await fetch(SCRYFALL_URL);
        const data = await res.json();
        return data;
    };

    // If card is not legal, get new card
    const checkCommanderLegality = async (card) => {
        if (card.legalities.commander === 'not_legal') {
            getNewCard();
        }
    };

    const showCard = async (card) => {
        // Exibir os detalhes do comandante
        img.src = card.image_uris.png;
        name.textContent = card.name;
        type.textContent = card.type_line;
        manacost.textContent = `Custo de mana: ${card.mana_cost}`;
        colorId.textContent = `Identidade de cor: ${card.color_identity.join('')}`;
        edhRec.href = card.related_uris.edhrec;

        // Buscar e exibir o rank do comandante no EDHREC
        const commanderName = card.name;
        const edhrecRank = await fetchEDHRECRank(commanderName);
        rank.textContent = edhrecRank;
    };

    const getNewCard = async () => {
        flipCard.classList.add('flipped');
        const newCard = await fetchCard().catch(handleErrors);
        await checkCommanderLegality(newCard).catch(handleErrors);
        await showCard(newCard).catch(handleErrors);
    };

    const showNewCard = async () => {
        flipCard.classList.add('flipped');
        await getNewCard();
    }

    const replaceSpecialCharacters = (commanderName) => {
        return commanderName
            .toLowerCase()          // Converter todo o texto para minúsculas
            .replace(/[ ,]+/g, '-') // Substituir espaços e vírgulas por hífens
            .replace(/-+/g, '-');   // Remover hífens consecutivos
    };

    const cardImageLoaded = () => {
        flipCard.classList.remove('flipped');
    }


    randomBtn.addEventListener('click', showNewCard);
    img.addEventListener('load', cardImageLoaded);

    function decodeHtmlEntities(text) {
        var textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
    }
</script>
