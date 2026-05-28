<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Grupos</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root { font-family: Inter, system-ui, sans-serif; background: #07121d; color: #f8fafc; --panel: rgba(11, 22, 38, 0.96); --border: rgba(56, 189, 248, 0.15); --shadow: 0 32px 80px rgba(0, 0, 0, 0.25); --text-muted: #94a3b8; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at top right, rgba(59,130,246,.18), transparent 18%), linear-gradient(180deg, #07121d 0%, #081830 45%, #03070f 100%); color: #f8fafc; }
        .layout { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(1180px, 100%); padding: 34px; border-radius: 32px; background: var(--panel); border: 1px solid var(--border); box-shadow: var(--shadow); backdrop-filter: blur(18px); }
        .topbar { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 18px; margin-bottom: 24px; }
        .topbar h1 { margin: 0; font-size: clamp(2rem, 2.3vw, 2.8rem); }
        .topbar p, .muted { color: var(--text-muted); line-height: 1.6; }
        .topbar p { margin: 6px 0 0; }
        .button, .button-secondary, .button-danger { border-radius: 16px; padding: 12px 18px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; border: none; }
        .button { background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #07121d; }
        .button-secondary { border: 1px solid rgba(255,255,255,.14); background: rgba(255,255,255,.06); color: #f8fafc; }
        .button-danger { background: rgba(248,113,113,.16); color: #fecaca; }
        .grid { display: grid; grid-template-columns: .8fr 1.2fr; gap: 24px; align-items: start; }
        .section, .group-item { padding: 24px; border-radius: 24px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.08); }
        .section h2 { margin: 0 0 14px; font-size: 1.2rem; }
        .field { display: grid; gap: 10px; margin-bottom: 16px; }
        label { color: #94a3b8; font-size: .82rem; text-transform: uppercase; letter-spacing: .08em; }
        input, textarea, select { width: 100%; border-radius: 16px; border: 1px solid rgba(148,163,184,.18); background: rgba(15,23,42,.96); color: #f8fafc; padding: 12px 14px; font-size: .96rem; }
        textarea { resize: vertical; min-height: 100px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: rgba(56,189,248,.45); }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-top: 14px; }
        .alert { margin: 0 0 18px; padding: 14px 16px; border-radius: 18px; background: rgba(16,185,129,.1); color: #bef264; }
        .alert-error { background: rgba(248,113,113,.12); color: #fecaca; }
        .group-list { display: grid; gap: 16px; }
        .group-header { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
        .group-header h3 { margin: 0; font-size: 1.05rem; }
        .group-header a { color: #7dd3fc; text-decoration: none; font-weight: 700; }
        .inline-form { display: grid; grid-template-columns: minmax(180px, 1fr) 180px auto; gap: 10px; align-items: end; margin-bottom: 18px; padding: 14px; border-radius: 18px; background: rgba(255,255,255,.04); }
        .member-list { display: grid; gap: 10px; }
        .member-row { display: grid; grid-template-columns: minmax(180px, 1fr) 170px auto auto; gap: 10px; align-items: center; padding: 12px; border-radius: 16px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.06); }
        .member-row form { margin: 0; }
        @media (max-width: 960px) { .grid, .inline-form, .member-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="layout">
        <section class="card">
            <div class="topbar">
                <div>
                    <h1>Grupos</h1>
                    <p>Crie um grupo e inclua usuarios com papel de visualizacao, edicao ou administracao.</p>
                </div>
                <div class="actions">
                    @if(($currentUser->is_admin ?? false))
                        <a class="button-secondary" href="{{ url('/admin/users') }}">Usuarios</a>
                    @endif
                    <a class="button" href="{{ url('/sistema') }}">Ir para o painel</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <strong>Nao foi possivel salvar:</strong>
                    <ul style="margin:10px 0 0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid">
                <div class="section">
                    <h2>Criar grupo</h2>
                    <form method="POST" action="{{ url('/groups') }}">
                        @csrf
                        <div class="field">
                            <label for="name">Nome</label>
                            <input id="name" name="name" type="text" placeholder="Ex: Financeiro" value="{{ old('name') }}" required>
                        </div>
                        <div class="field">
                            <label for="slug">Endereco</label>
                            <input id="slug" name="slug" type="text" placeholder="Ex: financeiro" value="{{ old('slug') }}">
                        </div>
                        <div class="field">
                            <label for="description">Descricao</label>
                            <textarea id="description" name="description" placeholder="Finalidade do grupo">{{ old('description') }}</textarea>
                        </div>
                        <button class="button" type="submit">Criar grupo</button>
                    </form>
                </div>

                <div class="section">
                    <h2>Grupos que voce pode gerenciar</h2>
                    <div class="group-list">
                        @forelse($groups as $group)
                            <article class="group-item">
                                <div class="group-header">
                                    <div>
                                        <h3>{{ $group->name }}</h3>
                                        <div class="muted">{{ $group->description ?: 'Sem descricao.' }}</div>
                                    </div>
                                    <a href="{{ url('/kanban/'.$group->slug) }}">{{ url('/kanban/'.$group->slug) }}</a>
                                </div>

                                <form class="inline-form" method="POST" action="{{ url('/groups/'.$group->id.'/users') }}">
                                    @csrf
                                    <div>
                                        <label for="user-{{ $group->id }}">Adicionar usuario</label>
                                        <select id="user-{{ $group->id }}" name="user_id" required>
                                            <option value="">Selecione</option>
                                            @foreach($users as $listedUser)
                                                @if(! $group->users->contains($listedUser->id))
                                                    <option value="{{ $listedUser->id }}">{{ $listedUser->name }} - {{ $listedUser->email }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="role-{{ $group->id }}">Papel</label>
                                        <select id="role-{{ $group->id }}" name="role" required>
                                            <option value="viewer">Visualizar</option>
                                            <option value="editor">Editar e visualizar</option>
                                            <option value="admin">Administrador do grupo</option>
                                        </select>
                                    </div>
                                    <button class="button-secondary" type="submit">Adicionar</button>
                                </form>

                                <div class="member-list">
                                    @forelse($group->users as $member)
                                        <div class="member-row">
                                            <div>
                                                <strong>{{ $member->name }}</strong>
                                                <div class="muted">{{ $member->email }}</div>
                                            </div>
                                            <form method="POST" action="{{ url('/groups/'.$group->id.'/users/'.$member->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" @disabled($member->id === $group->created_by)>
                                                    <option value="viewer" @selected($member->pivot->role === 'viewer')>Visualizar</option>
                                                    <option value="editor" @selected($member->pivot->role === 'editor')>Editar e visualizar</option>
                                                    <option value="admin" @selected($member->pivot->role === 'admin')>Administrador</option>
                                                </select>
                                            </form>
                                            <form method="POST" action="{{ url('/groups/'.$group->id.'/users/'.$member->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="role" value="{{ $member->id === $group->created_by ? 'admin' : $member->pivot->role }}">
                                                <button class="button-secondary" type="submit">Salvar</button>
                                            </form>
                                            @if($member->id !== $group->created_by)
                                                <form method="POST" action="{{ url('/groups/'.$group->id.'/users/'.$member->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="button-danger" type="submit">Remover</button>
                                                </form>
                                            @else
                                                <span class="muted">Criador</span>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="muted">Nenhum usuario no grupo ainda.</p>
                                    @endforelse
                                </div>
                            </article>
                        @empty
                            <p class="muted">Voce ainda nao criou nenhum grupo.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        document.querySelectorAll('.member-row select[name="role"]').forEach(select => {
            select.addEventListener('change', () => {
                const row = select.closest('.member-row');
                const hidden = row.querySelector('input[name="role"]');
                if (hidden) hidden.value = select.value;
            });
        });
    </script>
</body>
</html>
