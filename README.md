# SISKanban

SISKanban e um sistema web de controle de tarefas em modelo Kanban, desenvolvido em Laravel. O projeto permite organizar demandas por status, controlar visibilidade por grupos e administrar usuarios, perfis e permissoes.

## Prints do projeto

> Coloque os arquivos de captura na pasta `docs/screenshots/` usando os nomes abaixo para que as imagens aparecam automaticamente nesta secao.

| Tela | Print |
| --- | --- |
| Login | ![Tela de login](https://github.com/magnumsouza/ProjectKanban/blob/main/docs/screenshots/login.png?raw=true) |
| Painel Kanban | ![Painel Kanban](https://github.com/magnumsouza/ProjectKanban/blob/main/docs/screenshots/kanban.png?raw=true) |
| Dashboard administrativo | ![Dashboard administrativo](https://github.com/magnumsouza/ProjectKanban/blob/main/docs/screenshots/dashboard.png?raw=true) |
| Gestao de grupos | ![Gestao de grupos](https://github.com/magnumsouza/ProjectKanban/blob/main/docs/screenshots/grupos.png?raw=true) |
| Gestao de usuarios | ![Gestao de usuarios](https://github.com/magnumsouza/ProjectKanban/blob/main/docs/screenshots/usuarios.png?raw=true) |

## Funcionalidades

- Autenticacao por usuario/e-mail e senha.
- Painel Kanban com quatro status: Nao iniciado, Em andamento, Prorrogado e Concluido.
- Criacao, edicao, exclusao e movimentacao de tarefas entre colunas.
- Filtros por busca, grupo, responsavel, status e escopo.
- Tarefas privadas visiveis apenas para o criador.
- Tarefas compartilhadas por grupos.
- Controle de permissoes por grupo: visualizar, editar e administrar.
- Criacao e administracao de grupos.
- Inclusao, alteracao e remocao de membros nos grupos.
- Administracao geral de usuarios por perfil administrador.
- Dashboard administrativo com totais, distribuicao por status, tarefas por grupo e tarefas por usuario.
- Alternancia de tema no painel principal.

## Regras de acesso

- Administrador geral: acessa o dashboard, gerencia usuarios, ve todos os grupos e pode administrar todas as tarefas.
- Administrador de grupo: gerencia membros do grupo e pode editar/excluir tarefas do grupo conforme as regras do sistema.
- Editor de grupo: pode criar e editar tarefas compartilhadas no grupo.
- Visualizador de grupo: pode visualizar tarefas compartilhadas no grupo.
- Usuario comum: ve as proprias tarefas privadas e as tarefas dos grupos dos quais participa.

## Tecnologias

- PHP 8.2+
- Laravel 12
- SQLite
- Blade
- JavaScript nativo
- CSS customizado

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e npm
- Extensao SQLite habilitada no PHP

## Instalacao

1. Instale as dependencias PHP:

```bash
composer install
```

2. Instale as dependencias JavaScript:

```bash
npm install
```

3. Copie o arquivo de ambiente, se necessario:

```bash
cp .env.example .env
```

4. Gere a chave da aplicacao:

```bash
php artisan key:generate
```

5. Execute as migracoes e seeders:

```bash
php artisan migrate --seed
```

6. Compile os assets:

```bash
npm run build
```

7. Inicie o servidor local:

```bash
php artisan serve
```

Depois acesse `http://127.0.0.1:8000`.

## Acessos de desenvolvimento

Quando os seeders forem executados, ajuste os acessos conforme o ambiente. Use valores ficticios na documentacao publica:

| Perfil | Login | Senha |
| --- | --- | --- |
| Administrador | `admin@example.local` | `SenhaFicticia123` |
| Usuario padrao | `usuario@example.local` | `SenhaFicticia123` |

## Rotas principais

- `/login`: tela de acesso.
- `/sistema`: painel Kanban principal.
- `/groups`: gestao de grupos e membros.
- `/admin/users`: gestao de usuarios, disponivel para administradores.
- `/dashboard`: dashboard administrativo.
- `/kanban/{grupo}`: atalho para acessar o painel filtrado por grupo.

## Estrutura relevante

```text
app/Models/Task.php
app/Models/Group.php
database/migrations/
database/seeders/
resources/views/login.blade.php
resources/views/sistema.blade.php
resources/views/dashboard.blade.php
resources/views/admin-groups.blade.php
resources/views/admin-users.blade.php
routes/web.php
public/images/
docs/screenshots/
```

## Como atualizar os prints

1. Rode o projeto localmente.
2. Acesse cada tela principal.
3. Capture a tela em PNG.
4. Salve os arquivos com estes nomes:

```text
docs/screenshots/login.png
docs/screenshots/kanban.png
docs/screenshots/dashboard.png
docs/screenshots/grupos.png
docs/screenshots/usuarios.png
```

Ao manter esses nomes, o README exibira os prints automaticamente.
