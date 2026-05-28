<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Login</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root {
            color-scheme: dark;
            font-family: Inter, system-ui, sans-serif;
            background: #07121d;
            color: #f8fafc;
            --panel: rgba(11, 22, 38, 0.96);
            --panel-strong: rgba(16, 29, 50, 0.98);
            --border: rgba(56, 189, 248, 0.15);
            --shadow: 0 32px 80px rgba(0, 0, 0, 0.25);
            --accent: #5dd4ff;
            --accent-strong: #38bdf8;
            --text-muted: #94a3b8;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at top left, rgba(56,189,248,.18), transparent 18%), radial-gradient(circle at 90% 0, rgba(59,130,246,.1), transparent 12%), linear-gradient(180deg, #07121d 0%, #081830 48%, #03070f 100%); color: #f8fafc; }
        body::before { content: ''; position: fixed; inset: 0; background: radial-gradient(circle at 80% 20%, rgba(16, 185,129, .08), transparent 18%); pointer-events: none; z-index: -1; }
        .layout { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(520px, 100%); padding: 36px; border-radius: 32px; background: var(--panel); border: 1px solid var(--border); box-shadow: var(--shadow); backdrop-filter: blur(18px); }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1rem; letter-spacing: .18em; text-transform: uppercase; color: #e0f2fe; text-decoration: none; margin-bottom: 28px; }
        .brand span { width: 46px; height: 46px; display: grid; place-items: center; border-radius: 14px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); box-shadow: 0 18px 50px rgba(14,165,233,.18); overflow: hidden; }
        .brand span img { width: 38px; height: 38px; display: block; object-fit: contain; }
        .hero { margin-bottom: 32px; }
        .hero h1 { margin: 0; font-size: clamp(2rem, 2.3vw, 2.8rem); line-height: 1.02; }
        .hero p { margin: 14px 0 0; color: var(--text-muted); font-size: 1rem; line-height: 1.75; max-width: 38rem; }
        .form-group { display: grid; gap: 10px; margin-bottom: 20px; }
        label { font-size: .85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .08em; }
        input { width: 100%; border-radius: 18px; border: 1px solid rgba(148,163,184,.18); background: rgba(255,255,255,.07); color: #f8fafc; padding: 16px 18px; font-size: 1rem; outline: none; transition: border-color .2s ease, background .2s ease; }
        input:focus { background: rgba(255,255,255,.1); border-color: rgba(56,189,248,.45); }
        .actions { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-top: 4px; }
        .actions a { color: #7dd3fc; text-decoration: none; font-weight: 600; }
        .button { width: 100%; border: none; border-radius: 18px; padding: 16px 18px; background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #07121d; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform .2s ease, filter .2s ease; }
        .button:hover { transform: translateY(-1px); filter: brightness(1.04); }
        .auxiliary { margin-top: 28px; padding: 22px; border-radius: 22px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); }
        .auxiliary p { margin: 0; color: #cbd5e1; font-size: .95rem; }
        .auxiliary a { color: #bae6fd; text-decoration: none; font-weight: 700; }
        @media (max-width: 560px) { .card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="layout">
        <section class="card">
            <a class="brand" href="{{ url('/login') }}"><span><img src="{{ asset('images/brasao-exercito.svg') }}" alt="Brasao"></span>DTI SISKanban</a>
            <div class="hero">
                <h1>Entrar no painel</h1>
                <p>Faça login com seu usuário e senha para acessar o sistema Kanban com segurança.</p>
            </div>
            @if(session('error'))
                <p style="color:#f87171; margin-bottom:18px; font-weight:600;">{{ session('error') }}</p>
            @endif
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="form-group">
                    <label for="loginUser">Usuário</label>
                    <input id="loginUser" name="user" type="text" placeholder="Nome ou e-mail" autocomplete="username" required />
                </div>
                <div class="form-group">
                    <label for="loginPassword">Senha</label>
                    <input id="loginPassword" name="password" type="password" placeholder="Digite sua senha" autocomplete="current-password" required />
                </div>

                <button class="button" type="submit">Entrar</button>
                <br>
                <br>
                <div class="actions">
                    <a href="{{ url('/password/reset') }}">Esqueci minha senha</a>
                </div>
                
            </form>
            <div class="auxiliary">
                
            <p>Nao tem acesso ainda? Peca criacao de usuario.</p>
            <br>
            <br>
            <p>
                <p>
            <p class="hint" center>Desenvolvido por </strong> : <strong>3º SGT MAGNUM</strong></p>
            </div>
        </section>
    </main>
</body>
</html>
