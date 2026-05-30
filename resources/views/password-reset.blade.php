<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Resetar senha</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root {
            font-family: Inter, system-ui, sans-serif;
            background: #07121d;
            color: #f8fafc;
            --panel: rgba(11, 22, 38, 0.96);
            --border: rgba(56, 189, 248, 0.15);
            --accent: #5dd4ff;
            --text-muted: #94a3b8;
            --shadow: 0 32px 80px rgba(0, 0, 0, 0.25);
        }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: radial-gradient(circle at top right, rgba(59,130,246,.18), transparent 18%), linear-gradient(180deg, #07121d 0%, #081830 45%, #03070f 100%); color: #f8fafc; }
        .layout { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(520px, 100%); padding: 36px; border-radius: 32px; background: var(--panel); border: 1px solid var(--border); box-shadow: var(--shadow); backdrop-filter: blur(18px); }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #7dd3fc; text-decoration: none; font-weight: 600; margin-bottom: 26px; }
        .back-link:hover { opacity: .9; }
        h1 { margin: 0 0 10px; font-size: clamp(2rem, 2.3vw, 2.6rem); line-height: 1.05; }
        p { margin: 0 0 28px; color: var(--text-muted); line-height: 1.75; }
        .form-group { display: grid; gap: 10px; margin-bottom: 22px; }
        label { font-size: .85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .08em; }
        input { width: 100%; border-radius: 18px; border: 1px solid rgba(148,163,184,.18); background: rgba(255,255,255,.07); color: #f8fafc; padding: 16px 18px; font-size: 1rem; outline: none; transition: border-color .2s ease, background .2s ease; }
        input:focus { background: rgba(255,255,255,.1); border-color: rgba(56,189,248,.45); }
        .button { width: 100%; border: none; border-radius: 18px; padding: 16px 18px; background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: #07121d; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform .2s ease, filter .2s ease, box-shadow .2s ease; }
        .button:hover { transform: translateY(-1px); filter: brightness(1.12) saturate(1.08); box-shadow: 0 16px 34px rgba(14,165,233,.22); }
        .button:focus-visible { outline: 3px solid rgba(56,189,248,.35); outline-offset: 3px; }
        .button:hover { transform: translateY(-1px); filter: brightness(1.04); }
        .hint { margin-top: 22px; color: #cbd5e1; font-size: .95rem; }
        .hint a { color: #bae6fd; text-decoration: none; font-weight: 700; }
        @media (max-width: 560px) { .card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="layout">
        <section class="card">
            <a class="back-link" href="{{ url('/login') }}">Voltar ao login</a>
            <h1>Redefinir senha</h1>
            <p>Informe seu e-mail para receber instruções de redefinição de senha.</p>
            <form onsubmit="event.preventDefault(); alert('Solicitação enviada. Implemente backend para envio de e-mail.');">
                <div class="form-group">
                    <label for="resetEmail">E-mail</label>
                    <input id="resetEmail" name="email" type="email" placeholder="seu@email.com" autocomplete="email" required />
                </div>
                <button class="button" type="submit">Enviar link de redefinição</button>
            </form>
            <p class="hint">Se não receber o e-mail em alguns minutos, verifique a caixa de spam ou tente novamente.</p>
        </section>
    </main>
</body>
</html>
