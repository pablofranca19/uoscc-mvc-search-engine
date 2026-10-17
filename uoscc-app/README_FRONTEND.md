# UOSCC — Front-end entregue

Este pacote contém a implementação do front-end em Laravel/Blade/Tailwind para apresentação do projeto.

## Incluído

- Layout Blade reutilizável
- Navbar responsiva com menu mobile
- Footer reutilizável
- Home completa
- Cards reutilizáveis de eventos, organizações e espaços
- Listagem de eventos com busca e filtro por região
- Detalhe de evento + formulário de inscrição
- Listagem e perfil de organizações
- Listagem e detalhe de espaços + formulário de reserva
- Responsividade para desktop/tablet/mobile
- Paleta visual UOSCC
- Flash message para submissões dos formulários

## Instalação

Na pasta `uoscc-app`:

```bash
cp .env.example .env
composer install
php artisan key:generate
npm install
npm run build
php artisan serve
```

## Importante sobre dados reais

A interface está preparada para receber dados do back-end, mas os dados usados nesta versão são mockados em `routes/web.php` para permitir uma demonstração completa do front-end sem depender do banco de dados.

Quando o back-end estiver pronto, substitua os arrays mockados por Models/Controllers e mantenha os mesmos nomes das variáveis enviados às Views (`eventos`, `organizacoes`, `espacos`, etc.).
