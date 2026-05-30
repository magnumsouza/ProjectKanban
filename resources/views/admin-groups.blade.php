<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Grupos</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root { --bg:#07121d; --surface:rgba(8,18,29,.95); --surface-light:rgba(15,27,44,.9); --text:#eef5ff; --text-muted:#a1b0c8; --accent:#5dd4ff; --border:rgba(148,163,184,.16); --shadow-soft:0 18px 40px rgba(0,0,0,.18); --button-bg:linear-gradient(135deg,#4fd1ff,#0ea5e9); --button-text:#0b1120; --input-bg:rgba(15,23,42,.9); --input-border:rgba(148,163,184,.22); }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; font-family:Inter,system-ui,sans-serif; background:linear-gradient(180deg,#07121d 0%,#081830 42%,#060b15 100%); color:var(--text); }
        .page { width:100%; max-width:1500px; margin:0 auto; padding:18px 24px 44px; }
        .navbar { position:sticky; top:0; z-index:20; display:grid; grid-template-columns:minmax(230px,1fr) auto; gap:18px; align-items:center; padding:14px 16px; margin:0 0 18px; border:1px solid var(--border); border-radius:22px; background:rgba(8,18,29,.92); box-shadow:var(--shadow-soft); backdrop-filter:blur(16px); }
        .brand-block { display:flex; align-items:center; gap:12px; min-width:0; }
        .brand-mark { width:48px; height:48px; display:grid; place-items:center; border-radius:14px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); flex:0 0 auto; }
        .brand-mark img { width:40px; height:40px; object-fit:contain; display:block; }
        .brand-copy h1 { margin:0; font-size:1.34rem; line-height:1.1; }
        .brand-copy small, .muted { color:var(--text-muted); }
        .actions { display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:flex-end; }
        .button, .button-secondary, .button-danger { font:inherit; border-radius:999px; padding:12px 18px; font-weight:800; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; border:none; transition:background .2s ease, border-color .2s ease, color .2s ease, filter .2s ease, transform .2s ease, box-shadow .2s ease; }
        .button { background:var(--button-bg); color:var(--button-text); text-transform:uppercase; }
        .button-secondary { border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.08); color:var(--text); text-transform:none; }
        .button-danger { background:rgba(248,113,113,.16); color:#fecaca; text-transform:none; }
        .button:hover { filter:brightness(1.12) saturate(1.08); transform:translateY(-1px); box-shadow:0 16px 34px rgba(14,165,233,.22); }
        .button-secondary:hover { background:rgba(56,189,248,.16); border-color:rgba(56,189,248,.42); color:#dff7ff; transform:translateY(-1px); }
        .button-danger:hover { background:rgba(248,113,113,.28); color:#fff1f2; transform:translateY(-1px); box-shadow:0 14px 30px rgba(248,113,113,.14); }
        .button:focus-visible, .button-secondary:focus-visible, .button-danger:focus-visible { outline:3px solid rgba(56,189,248,.35); outline-offset:3px; }
        .workspace-header { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 18px; padding:0 4px; color:var(--text-muted); }
        .workspace-header strong { color:var(--text); }
        .grid { display:grid; grid-template-columns:minmax(320px,.78fr) minmax(0,1.22fr); gap:20px; align-items:start; }
        .section, .group-card { background:linear-gradient(180deg,var(--surface-light),var(--surface)); border:1px solid var(--border); border-radius:24px; padding:22px; box-shadow:var(--shadow-soft); }
        .section h2, .group-card h3 { margin:0; font-size:1.08rem; }
        .section-intro { margin:8px 0 20px; color:var(--text-muted); line-height:1.6; }
        .field { display:grid; gap:8px; margin-bottom:14px; }
        label { color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.08em; }
        input, textarea, select { width:100%; border-radius:14px; border:1px solid var(--input-border); background:var(--input-bg); color:var(--text); padding:12px 14px; font-size:.95rem; }
        textarea { resize:vertical; min-height:108px; }
        input:focus, textarea:focus, select:focus { outline:none; border-color:rgba(56,189,248,.55); }
        .alert { margin:0 0 18px; padding:14px 16px; border-radius:18px; background:rgba(16,185,129,.1); color:#bef264; }
        .alert-error { background:rgba(248,113,113,.12); color:#fecaca; }
        .group-list { display:grid; gap:14px; }
        .group-card header { display:flex; flex-wrap:wrap; align-items:start; justify-content:space-between; gap:12px; margin-bottom:16px; }
        .group-link { color:var(--accent); text-decoration:none; font-weight:800; overflow-wrap:anywhere; }
        .inline-form { display:grid; grid-template-columns:minmax(180px,1fr) 190px auto; gap:12px; align-items:end; margin:0 0 16px; padding:14px; border-radius:18px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); }
        .member-list { display:grid; gap:10px; }
        .member-row { display:grid; grid-template-columns:minmax(180px,1fr) 190px auto auto; gap:10px; align-items:center; padding:12px; border-radius:16px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); }
        .member-row form { margin:0; display:contents; }
        .member-name strong { display:block; }
        @media (max-width:1020px) { .grid, .navbar { grid-template-columns:1fr; } .actions { justify-content:flex-start; } }
        @media (max-width:820px) { .page { padding:12px; } .inline-form, .member-row { grid-template-columns:1fr; } .button, .button-secondary, .button-danger { width:100%; } .member-row form { display:grid; gap:10px; } }
    </style>
</head>
<body>
    <main class="page">
        <nav class="navbar" aria-label="Navegacao principal">
            <div class="brand-block">
                <span class="brand-mark"><img src="{{ asset('images/brasao-exercito.svg') }}" alt="Brasao"></span>
                <div class="brand-copy">
                    <h1>Grupos</h1>
                    <small>Compartilhamento e permissoes</small>
                </div>
            </div>
            <div class="actions">
                @if(($currentUser->is_admin ?? false))
                    <a class="button-secondary" href="{{ url('/admin/users') }}">Usuarios</a>
                @endif
                <a class="button" href="{{ url('/sistema') }}">Painel</a>
                <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
                    @csrf
                    <button class="button-secondary" type="submit">Sair</button>
                </form>
            </div>
        </nav>

        <div class="workspace-header">
            <div><strong>Provisionamento de grupos</strong></div>
            <div>Usuarios comuns gerenciam apenas grupos que criaram; administradores gerais veem todos.</div>
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
            <section class="section">
                <h2>Criar grupo</h2>
                <p class="section-intro">Ao criar um grupo, voce entra automaticamente como administrador dele.</p>
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
            </section>

            <section class="group-list">
                @forelse($groups as $group)
                    <article class="group-card">
                        <header>
                            <div>
                                <h3>{{ $group->name }}</h3>
                                <div class="muted">{{ $group->description ?: 'Sem descricao.' }}</div>
                            </div>
                            <a class="group-link" href="{{ url('/kanban/'.$group->slug) }}">{{ url('/kanban/'.$group->slug) }}</a>
                        </header>

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
                                    <div class="member-name">
                                        <strong>{{ $member->name }}</strong>
                                        <span class="muted">{{ $member->email }}</span>
                                    </div>
                                    <form method="POST" action="{{ url('/groups/'.$group->id.'/users/'.$member->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" @disabled($member->id === $group->created_by)>
                                            <option value="viewer" @selected($member->pivot->role === 'viewer')>Visualizar</option>
                                            <option value="editor" @selected($member->pivot->role === 'editor')>Editar e visualizar</option>
                                            <option value="admin" @selected($member->pivot->role === 'admin')>Administrador</option>
                                        </select>
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
                    <section class="section">
                        <h2>Nenhum grupo criado</h2>
                        <p class="section-intro">Crie o primeiro grupo para compartilhar tarefas com outros usuarios.</p>
                    </section>
                @endforelse
            </section>
        </div>
    </main>
</body>
</html>
