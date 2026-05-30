<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Usuarios</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root { --bg:#07121d; --surface:rgba(8,18,29,.95); --surface-light:rgba(15,27,44,.9); --text:#eef5ff; --text-muted:#a1b0c8; --accent:#5dd4ff; --border:rgba(148,163,184,.16); --shadow-soft:0 18px 40px rgba(0,0,0,.18); --button-bg:linear-gradient(135deg,#4fd1ff,#0ea5e9); --button-text:#0b1120; --input-bg:rgba(15,23,42,.9); --input-border:rgba(148,163,184,.22); }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; font-family:Inter,system-ui,sans-serif; background:linear-gradient(180deg,#07121d 0%,#081830 42%,#060b15 100%); color:var(--text); }
        .page { width:100%; max-width:1500px; margin:0 auto; padding:18px 24px 44px; }
        .navbar { position:sticky; top:0; z-index:20; display:grid; grid-template-columns:minmax(230px,1fr) auto; gap:18px; align-items:center; padding:14px 16px; margin:0 0 18px; border:1px solid var(--border); border-radius:22px; background:rgba(8,18,29,.92); box-shadow:var(--shadow-soft); backdrop-filter:blur(16px); }
        .brand-block { display:flex; align-items:center; gap:12px; min-width:0; }
        .brand-mark { width:48px; height:48px; display:grid; place-items:center; border-radius:14px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); flex:0 0 auto; }
        .brand-mark img { width:40px; height:40px; object-fit:contain; display:block; }
        .brand-copy h1 { margin:0; font-size:1.34rem; line-height:1.1; }
        .brand-copy small, .muted { color:var(--text-muted); }
        .actions { display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:flex-end; }
        .button, .button-secondary { font:inherit; border-radius:999px; padding:12px 18px; font-weight:800; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; border:none; transition:background .2s ease, border-color .2s ease, color .2s ease, filter .2s ease, transform .2s ease, box-shadow .2s ease; }
        .button { background:var(--button-bg); color:var(--button-text); text-transform:uppercase; }
        .button-secondary { border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.08); color:var(--text); text-transform:none; }
        .button:hover { filter:brightness(1.12) saturate(1.08); transform:translateY(-1px); box-shadow:0 16px 34px rgba(14,165,233,.22); }
        .button-secondary:hover { background:rgba(56,189,248,.16); border-color:rgba(56,189,248,.42); color:#dff7ff; transform:translateY(-1px); }
        .button:focus-visible, .button-secondary:focus-visible { outline:3px solid rgba(56,189,248,.35); outline-offset:3px; }
        .workspace-header { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 18px; padding:0 4px; color:var(--text-muted); }
        .workspace-header strong { color:var(--text); }
        .grid { display:grid; grid-template-columns:minmax(320px,.78fr) minmax(0,1.22fr); gap:20px; align-items:start; }
        .section, .user-card { background:linear-gradient(180deg,var(--surface-light),var(--surface)); border:1px solid var(--border); border-radius:24px; padding:22px; box-shadow:var(--shadow-soft); }
        .section h2, .user-card h3 { margin:0; font-size:1.08rem; }
        .section-intro { margin:8px 0 20px; color:var(--text-muted); line-height:1.6; }
        .field { display:grid; gap:8px; margin-bottom:14px; }
        label { color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.08em; }
        input, select { width:100%; border-radius:14px; border:1px solid var(--input-border); background:var(--input-bg); color:var(--text); padding:12px 14px; font-size:.95rem; }
        input[type="checkbox"] { width:auto; margin-right:8px; }
        input:focus, select:focus { outline:none; border-color:rgba(56,189,248,.55); }
        .alert { margin:0 0 18px; padding:14px 16px; border-radius:18px; background:rgba(16,185,129,.1); color:#bef264; }
        .alert-error { background:rgba(248,113,113,.12); color:#fecaca; }
        .group-role-list { display:grid; gap:10px; margin-top:8px; }
        .group-role-row { display:grid; grid-template-columns:minmax(150px,1fr) 190px; gap:12px; align-items:center; padding:12px; border-radius:16px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); }
        .group-role-row strong { display:block; }
        .group-role-row span { display:block; margin-top:4px; color:var(--text-muted); font-size:.82rem; overflow-wrap:anywhere; }
        .users-list { display:grid; gap:14px; }
        .user-card header { display:flex; flex-wrap:wrap; align-items:start; justify-content:space-between; gap:12px; margin-bottom:16px; }
        .badge { display:inline-flex; padding:6px 12px; border-radius:999px; font-size:.76rem; font-weight:800; text-transform:uppercase; background:rgba(148,163,184,.12); color:#cbd5e1; }
        .badge-admin { background:rgba(56,189,248,.16); color:#a5f3fc; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .form-grid .full { grid-column:1 / -1; }
        @media (max-width:1020px) { .grid, .navbar { grid-template-columns:1fr; } .actions { justify-content:flex-start; } }
        @media (max-width:720px) { .page { padding:12px; } .form-grid, .group-role-row { grid-template-columns:1fr; } .button, .button-secondary { width:100%; } }
    </style>
</head>
<body>
    <main class="page">
        <nav class="navbar" aria-label="Navegacao principal">
            <div class="brand-block">
                <span class="brand-mark"><img src="{{ asset('images/brasao-exercito.svg') }}" alt="Brasao"></span>
                <div class="brand-copy">
                    <h1>Usuarios</h1>
                    <small>Cadastro, perfil e permissoes</small>
                </div>
            </div>
            <div class="actions">
                <a class="button-secondary" href="{{ url('/groups') }}">Grupos</a>
                <a class="button" href="{{ url('/sistema') }}">Painel</a>
                <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
                    @csrf
                    <button class="button-secondary" type="submit">Sair</button>
                </form>
            </div>
        </nav>

        <div class="workspace-header">
            <div><strong>Administracao de usuarios</strong></div>
            <div>Crie usuarios e ajuste acessos sem alterar o fluxo de autenticacao atual.</div>
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
                <h2>Criar novo usuario</h2>
                <p class="section-intro">Defina o login e, se necessario, ja vincule o usuario aos grupos existentes.</p>
                <form method="POST" action="{{ url('/admin/users') }}">
                    @csrf
                    <div class="field">
                        <label for="name">Nome completo</label>
                        <input id="name" name="name" type="text" placeholder="Nome do usuario" value="{{ old('name') }}" required>
                    </div>
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" placeholder="email@empresa.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="password">Senha</label>
                        <input id="password" name="password" type="password" placeholder="Senha segura" required>
                    </div>
                    <div class="field">
                        <label><input type="checkbox" name="is_admin" value="1" @checked(old('is_admin'))> Criar como administrador geral</label>
                    </div>
                    <div class="field">
                        <label>Grupos de acesso</label>
                        <div class="group-role-list">
                            @forelse($groups as $group)
                                <div class="group-role-row">
                                    <div>
                                        <strong>{{ $group->name }}</strong>
                                        <span>{{ url('/kanban/'.$group->slug) }}</span>
                                    </div>
                                    <select name="group_roles[{{ $group->id }}]">
                                        <option value="none" @selected(old("group_roles.$group->id", 'none') === 'none')>Sem acesso</option>
                                        <option value="viewer" @selected(old("group_roles.$group->id") === 'viewer')>Visualizar</option>
                                        <option value="editor" @selected(old("group_roles.$group->id") === 'editor')>Editar e visualizar</option>
                                        <option value="admin" @selected(old("group_roles.$group->id") === 'admin')>Administrador do grupo</option>
                                    </select>
                                </div>
                            @empty
                                <span class="muted">Nenhum grupo criado ainda.</span>
                            @endforelse
                        </div>
                    </div>
                    <button class="button" type="submit">Criar usuario</button>
                </form>
            </section>

            <section class="users-list">
                @foreach($users as $listedUser)
                    <article class="user-card">
                        <header>
                            <div>
                                <h3>{{ $listedUser->name }}</h3>
                                <div class="muted">{{ $listedUser->email }}</div>
                            </div>
                            <span class="badge {{ $listedUser->is_admin ? 'badge-admin' : '' }}">{{ $listedUser->is_admin ? 'Administrador' : 'Usuario' }}</span>
                        </header>
                        <form method="POST" action="{{ url('/admin/users/'.$listedUser->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-grid">
                                <div class="field">
                                    <label for="name-{{ $listedUser->id }}">Nome</label>
                                    <input id="name-{{ $listedUser->id }}" name="name" type="text" value="{{ old("users.$listedUser->id.name", $listedUser->name) }}" required>
                                </div>
                                <div class="field">
                                    <label for="email-{{ $listedUser->id }}">E-mail</label>
                                    <input id="email-{{ $listedUser->id }}" name="email" type="email" value="{{ old("users.$listedUser->id.email", $listedUser->email) }}" required>
                                </div>
                                <div class="field">
                                    <label for="password-{{ $listedUser->id }}">Nova senha</label>
                                    <input id="password-{{ $listedUser->id }}" name="password" type="password" placeholder="Manter senha atual">
                                </div>
                                <div class="field">
                                    <label><input type="checkbox" name="is_admin" value="1" @checked($listedUser->is_admin) @disabled(session('user.id') === $listedUser->id)> Administrador geral</label>
                                </div>
                                <div class="field full">
                                    <label>Permissoes em grupos</label>
                                    <div class="group-role-list">
                                        @forelse($groups as $group)
                                            @php
                                                $memberGroup = $listedUser->groups->firstWhere('id', $group->id);
                                                $role = $memberGroup?->pivot?->role ?? 'none';
                                            @endphp
                                            <div class="group-role-row">
                                                <div>
                                                    <strong>{{ $group->name }}</strong>
                                                    <span>{{ url('/kanban/'.$group->slug) }}</span>
                                                </div>
                                                <select name="group_roles[{{ $group->id }}]">
                                                    <option value="none" @selected($role === 'none')>Sem acesso</option>
                                                    <option value="viewer" @selected($role === 'viewer')>Visualizar</option>
                                                    <option value="editor" @selected($role === 'editor')>Editar e visualizar</option>
                                                    <option value="admin" @selected($role === 'admin')>Administrador do grupo</option>
                                                </select>
                                            </div>
                                        @empty
                                            <span class="muted">Nenhum grupo criado ainda.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <button class="button-secondary" type="submit">Salvar alteracoes</button>
                        </form>
                    </article>
                @endforeach
            </section>
        </div>
    </main>
</body>
</html>
