<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Administracao de Usuarios</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root { font-family: Inter, system-ui, sans-serif; background: #07121d; color: #f8fafc; --panel: rgba(11, 22, 38, 0.96); --border: rgba(56, 189, 248, 0.15); --shadow: 0 32px 80px rgba(0, 0, 0, 0.25); --text-muted: #94a3b8; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at top right, rgba(59,130,246,.18), transparent 18%), linear-gradient(180deg, #07121d 0%, #081830 45%, #03070f 100%); color: #f8fafc; }
        .layout { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(1100px, 100%); padding: 34px; border-radius: 32px; background: var(--panel); border: 1px solid var(--border); box-shadow: var(--shadow); backdrop-filter: blur(18px); }
        .topbar { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 18px; margin-bottom: 24px; }
        .topbar h1 { margin: 0; font-size: clamp(2rem, 2.3vw, 2.8rem); }
        .topbar p { margin: 6px 0 0; color: var(--text-muted); }
        .button { border: none; border-radius: 16px; padding: 14px 20px; background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #07121d; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .button-secondary { border: 1px solid rgba(255,255,255,.14); background: rgba(255,255,255,.06); color: #f8fafc; border-radius: 16px; padding: 14px 20px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .grid { display: grid; gap: 24px; }
        .grid-2 { grid-template-columns: 1fr 1fr; align-items: start; }
        .section { padding: 28px; border-radius: 28px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.08); overflow-x: auto; }
        .section h2 { margin: 0 0 14px; font-size: 1.2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        table th, table td { text-align: left; padding: 14px 12px; border-bottom: 1px solid rgba(255,255,255,.08); vertical-align: top; }
        table th { color: #94a3b8; font-size: .88rem; text-transform: uppercase; letter-spacing: .08em; }
        .badge { display: inline-flex; padding: 6px 12px; border-radius: 999px; font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; background: rgba(148,163,184,.12); color: #cbd5e1; }
        .badge-admin { background: rgba(56,189,248,.16); color: #a5f3fc; }
        .muted { color: var(--text-muted); }
        .hint { margin-top: 16px; color: #94a3b8; font-size: .95rem; }
        .field { display: grid; gap: 10px; margin-bottom: 16px; }
        label { color: #94a3b8; font-size: .82rem; text-transform: uppercase; letter-spacing: .08em; }
        input, select { width: 100%; border-radius: 16px; border: 1px solid rgba(148,163,184,.18); background: rgba(15,23,42,.96); color: #f8fafc; padding: 14px 16px; font-size: 1rem; }
        input[type="checkbox"] { width: auto; margin-right: 8px; }
        input:focus, select:focus { outline: none; border-color: rgba(56,189,248,.45); }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-top: 14px; }
        .alert { margin: 0 0 18px; padding: 14px 16px; border-radius: 18px; background: rgba(16,185,129,.1); color: #bef264; }
        .alert-error { background: rgba(248,113,113,.12); color: #fecaca; }
        .group-role-list { display: grid; gap: 10px; }
        .group-role-row { display: grid; grid-template-columns: minmax(160px, 1fr) 190px; gap: 12px; align-items: center; padding: 12px; border-radius: 16px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.06); }
        .group-role-row strong { display: block; }
        .group-role-row span { display: block; margin-top: 4px; color: var(--text-muted); font-size: .85rem; }
        @media (max-width: 860px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="layout">
        <section class="card">
            <div class="topbar">
                <div>
                    <h1>Administracao de Usuarios</h1>
                    <p>Somente o usuario administrador pode criar logins e vincular grupos.</p>
                </div>
                <div class="actions">
                    <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
                        @csrf
                        <button class="button-secondary" type="submit">Sair</button>
                    </form>
                    <a class="button-secondary" href="{{ url('/admin/groups') }}">Grupos</a>
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
                    <strong>Nao foi possivel criar o usuario:</strong>
                    <ul style="margin:10px 0 0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-2">
                <div class="section">
                    <h2>Usuarios existentes</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Perfil</th>
                                <th>Grupos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge {{ $user->is_admin ? 'badge-admin' : '' }}">{{ $user->is_admin ? 'Administrador' : 'Usuario' }}</span></td>
                                    <td>
                                        @forelse($user->groups as $group)
                                            <div>{{ $group->name }} <span class="muted">({{ $group->pivot->role }})</span></div>
                                        @empty
                                            Sem grupo
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <h2>Criar novo login</h2>
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
                            <label>
                                <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin'))> Criar como administrador
                            </label>
                        </div>
                        <div class="field">
                            <label>Grupos de acesso</label>
                            <div class="group-role-list">
                                @foreach($groups as $group)
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
                                @endforeach
                            </div>
                        </div>
                        <button class="button" type="submit">Criar usuario</button>
                    </form>
                    <p class="hint">Usuarios sem grupo continuam fazendo login, mas nao acessam os Kanbans restritos por grupo.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
