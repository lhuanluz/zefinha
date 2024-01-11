# Zefinha - Magic: The Gathering Decklist Manager

## Sobre o Projeto
Este projeto é um sistema de gerenciamento de decklists para campeonatos de Magic: The Gathering. A plataforma permite que os jogadores submetam suas decklists, respeitando as regras do torneio, como valor máximo das cartas e quantidade máxima de cartas no deck. Além disso, o sistema registra a pontuação de cada jogador a cada rodada do campeonato.

## Funcionalidades

- **Submissão de Decklists**: Os jogadores podem submeter suas decklists antes do início do evento.
- **Validação de Regras**: O sistema valida automaticamente as decklists com base nas regras do campeonato.
- **Gerenciamento de Pontuação**: Registra e atualiza a pontuação dos jogadores durante o campeonato.
- **Interface Responsiva**: O site é totalmente responsivo, adequado para todos os dispositivos.

## Tecnologias Utilizadas

- **Front-End**: Livewire para uma interface reativa e dinâmica, integrada ao Laravel.
- **Back-End**: Laravel, com o Laravel Jetstream para autenticação e gestão de usuários.
- **Banco de Dados**: MySQL.
- **Autenticação e Gestão de Usuários**: Integrados através do Laravel Jetstream.

## Como Configurar o Projeto

1. **Clone o Repositório**
```
git clone https://github.com/lhuanluz/zefinha
cd zefinha
```

2. **Instale as Dependências**
```
composer install
npm install
```
3. **Configure o Ambiente**
- Copie `.env.example` para `.env` e ajuste as configurações do banco de dados.
`cp .env.example .env`

- Gere a chave da aplicação
`php artisan key:generate`

4. **Execute as Migrações e Sementes (se aplicável)**
`php artisan migrate`

5. **Inicie o Servidor de Desenvolvimento**
`php artisan serve`

## Contribuindo

Contribuições são sempre bem-vindas! Se você tem uma sugestão que poderia melhorar este projeto, siga estes passos:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Faça suas alterações e commit (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## Licença

Distribuído sob a licença MIT. Veja `LICENSE` para mais informações.

## Contato

Luan Luz - luan@taosec.com.br

Link do Projeto: [https://github.com/lhuanluz/zefinha](https://github.com/lhuanluz/zefinha)
