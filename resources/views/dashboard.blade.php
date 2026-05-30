<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTI SISKanban | Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root { --bg:#07121d; --surface:rgba(8,18,29,.95); --surface-light:rgba(15,27,44,.9); --text:#eef5ff; --text-muted:#a1b0c8; --accent:#5dd4ff; --border:rgba(148,163,184,.16); --shadow-soft:0 18px 40px rgba(0,0,0,.18); --button-bg:linear-gradient(135deg,#4fd1ff,#0ea5e9); --button-text:#0b1120; }
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
        .button, .button-secondary { font:inherit; border-radius:999px; padding:12px 18px; font-weight:800; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; border:none; transition:background .2s ease, border-color .2s ease, color .2s ease, filter .2s ease, transform .2s ease, box-shadow .2s ease; }
        .button { background:var(--button-bg); color:var(--button-text); text-transform:uppercase; }
        .button-secondary { border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.08); color:var(--text); text-transform:none; }
        .button:hover { filter:brightness(1.12) saturate(1.08); transform:translateY(-1px); box-shadow:0 16px 34px rgba(14,165,233,.22); }
        .button-secondary:hover { background:rgba(56,189,248,.16); border-color:rgba(56,189,248,.42); color:#dff7ff; transform:translateY(-1px); }
        .workspace-header { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 18px; padding:0 4px; color:var(--text-muted); }
        .workspace-header strong { color:var(--text); }
        .summary-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; margin-bottom:20px; }
        .metric, .panel { background:linear-gradient(180deg,var(--surface-light),var(--surface)); border:1px solid var(--border); border-radius:24px; box-shadow:var(--shadow-soft); }
        .metric { padding:20px; }
        .metric span { display:block; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.08em; }
        .metric strong { display:block; margin-top:8px; font-size:2rem; line-height:1; }
        .status-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-bottom:20px; }
        .status-card { padding:16px; border-radius:20px; border:1px solid rgba(255,255,255,.08); background:rgba(255,255,255,.04); }
        .status-card span { color:var(--text-muted); font-size:.82rem; }
        .status-card strong { display:block; margin-top:8px; font-size:1.6rem; }
        .chart-grid { display:grid; grid-template-columns:minmax(320px,.8fr) minmax(0,1.2fr); gap:20px; margin-bottom:20px; }
        .chart-panel, .panel { background:linear-gradient(180deg,var(--surface-light),var(--surface)); border:1px solid var(--border); border-radius:24px; box-shadow:var(--shadow-soft); }
        .chart-panel { padding:22px; }
        .chart-panel h2 { margin:0 0 16px; font-size:1.1rem; }
        .donut-wrap { display:grid; grid-template-columns:180px minmax(0,1fr); gap:20px; align-items:center; }
        .donut { width:180px; height:180px; border-radius:50%; display:grid; place-items:center; background:conic-gradient(#38bdf8 0 var(--nao), #fbbf24 var(--nao) var(--andamento), #f87171 var(--andamento) var(--prorrogado), #34d399 var(--prorrogado) 100%); box-shadow:inset 0 0 0 1px rgba(255,255,255,.08); }
        .donut::before { content:attr(data-total); width:108px; height:108px; border-radius:50%; display:grid; place-items:center; background:var(--surface); color:var(--text); font-size:2rem; font-weight:900; }
        .legend { display:grid; gap:10px; }
        .legend-item { display:grid; grid-template-columns:14px minmax(0,1fr) auto; gap:10px; align-items:center; color:var(--text-muted); }
        .legend-color { width:14px; height:14px; border-radius:4px; }
        .bars { display:grid; gap:12px; }
        .bar-row { display:grid; grid-template-columns:minmax(140px,220px) minmax(140px,1fr) 44px; gap:12px; align-items:center; }
        .bar-name { min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--text); font-weight:800; }
        .bar-track { height:14px; border-radius:999px; background:rgba(255,255,255,.08); overflow:hidden; border:1px solid rgba(255,255,255,.06); }
        .bar-fill { height:100%; min-width:4px; border-radius:999px; background:linear-gradient(90deg,#38bdf8,#34d399); }
        .bar-value { text-align:right; color:var(--text-muted); font-variant-numeric:tabular-nums; font-weight:800; }
        .panels { display:grid; gap:20px; }
        .panel { padding:22px; overflow-x:auto; }
        .panel h2 { margin:0 0 14px; font-size:1.1rem; }
        table { width:100%; border-collapse:collapse; min-width:760px; }
        th, td { text-align:left; padding:13px 12px; border-bottom:1px solid rgba(255,255,255,.08); vertical-align:top; }
        th { color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.08em; }
        td strong { color:var(--text); }
        .badge { display:inline-flex; padding:5px 10px; border-radius:999px; background:rgba(56,189,248,.12); color:#a5f3fc; font-size:.76rem; font-weight:800; }
        .number { font-variant-numeric:tabular-nums; font-weight:800; }
        @media (max-width:1020px) { .summary-grid, .status-grid, .chart-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .navbar { grid-template-columns:1fr; } .actions { justify-content:flex-start; } .donut-wrap { grid-template-columns:1fr; justify-items:center; } }
        @media (max-width:680px) { .page { padding:12px; } .summary-grid, .status-grid, .chart-grid { grid-template-columns:1fr; } .button, .button-secondary { width:100%; } .bar-row { grid-template-columns:1fr; gap:6px; } .bar-value { text-align:left; } }
    </style>
</head>
<body>
    <main class="page">
        <nav class="navbar" aria-label="Navegacao principal">
            <div class="brand-block">
                <span class="brand-mark"><img src="{{ asset('images/brasao-exercito.svg') }}" alt="Brasao"></span>
                <div class="brand-copy">
                    <h1>Dashboard</h1>
                    <small>Indicadores administrativos</small>
                </div>
            </div>
            <div class="actions">
                <a class="button-secondary" href="{{ url('/sistema') }}">Painel</a>
                <a class="button-secondary" href="{{ url('/groups') }}">Grupos</a>
                <a class="button" href="{{ url('/admin/users') }}">Usuarios</a>
                <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
                    @csrf
                    <button class="button-secondary" type="submit">Sair</button>
                </form>
            </div>
        </nav>

        <div class="workspace-header">
            <div>Ola, <strong>{{ $user['name'] }}</strong></div>
            <div>Visao completa por grupos, usuarios e status.</div>
        </div>

        <section class="summary-grid" aria-label="Resumo geral">
            <article class="metric"><span>Tarefas</span><strong>{{ $totalTasks }}</strong></article>
            <article class="metric"><span>Grupos</span><strong>{{ $totalGroups }}</strong></article>
            <article class="metric"><span>Usuarios</span><strong>{{ $totalUsers }}</strong></article>
            <article class="metric"><span>Privadas</span><strong>{{ $privateTasks }}</strong></article>
        </section>

        <section class="status-grid" aria-label="Status geral">
            @foreach($statuses as $status => $label)
                <article class="status-card">
                    <span>{{ $label }}</span>
                    <strong>{{ $statusTotals[$status] ?? 0 }}</strong>
                </article>
            @endforeach
        </section>

        @php
            $totalForCharts = max($totalTasks, 1);
            $naoPercent = (($statusTotals['nao-iniciado'] ?? 0) / $totalForCharts) * 100;
            $andamentoPercent = $naoPercent + ((($statusTotals['em-andamento'] ?? 0) / $totalForCharts) * 100);
            $prorrogadoPercent = $andamentoPercent + ((($statusTotals['prorrogado'] ?? 0) / $totalForCharts) * 100);
            $topGroups = collect($groupRows)->sortByDesc('total')->take(6)->values();
            $topUsers = collect($userRows)->sortByDesc('total')->take(6)->values();
            $maxGroupTasks = max($topGroups->max('total') ?? 0, 1);
            $maxUserTasks = max($topUsers->max('total') ?? 0, 1);
        @endphp

        <section class="chart-grid" aria-label="Graficos do dashboard">
            <article class="chart-panel">
                <h2>Distribuicao por status</h2>
                <div class="donut-wrap">
                    <div class="donut"
                        data-total="{{ $totalTasks }}"
                        style="--nao: {{ $naoPercent }}%; --andamento: {{ $andamentoPercent }}%; --prorrogado: {{ $prorrogadoPercent }}%;">
                    </div>
                    <div class="legend">
                        <div class="legend-item"><span class="legend-color" style="background:#38bdf8"></span><span>Nao iniciado</span><strong>{{ $statusTotals['nao-iniciado'] ?? 0 }}</strong></div>
                        <div class="legend-item"><span class="legend-color" style="background:#fbbf24"></span><span>Em andamento</span><strong>{{ $statusTotals['em-andamento'] ?? 0 }}</strong></div>
                        <div class="legend-item"><span class="legend-color" style="background:#f87171"></span><span>Prorrogado</span><strong>{{ $statusTotals['prorrogado'] ?? 0 }}</strong></div>
                        <div class="legend-item"><span class="legend-color" style="background:#34d399"></span><span>Concluido</span><strong>{{ $statusTotals['concluido'] ?? 0 }}</strong></div>
                    </div>
                </div>
            </article>

            <article class="chart-panel">
                <h2>Tarefas por grupo</h2>
                <div class="bars">
                    @foreach($topGroups as $row)
                        <div class="bar-row">
                            <div class="bar-name" title="{{ $row['name'] }}">{{ $row['name'] }}</div>
                            <div class="bar-track"><div class="bar-fill" style="width: {{ max(($row['total'] / $maxGroupTasks) * 100, $row['total'] > 0 ? 4 : 0) }}%;"></div></div>
                            <div class="bar-value">{{ $row['total'] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="chart-panel">
                <h2>Tarefas por usuario</h2>
                <div class="bars">
                    @foreach($topUsers as $row)
                        <div class="bar-row">
                            <div class="bar-name" title="{{ $row['name'] }}">{{ $row['name'] }}</div>
                            <div class="bar-track"><div class="bar-fill" style="width: {{ max(($row['total'] / $maxUserTasks) * 100, $row['total'] > 0 ? 4 : 0) }}%;"></div></div>
                            <div class="bar-value">{{ $row['total'] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <div class="panels">
            <section class="panel">
                <h2>Indicadores por grupo</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Membros</th>
                            <th>Total</th>
                            @foreach($statuses as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupRows as $row)
                            <tr>
                                <td>
                                    <strong>{{ $row['name'] }}</strong>
                                    @if($row['slug'])
                                        <div class="muted">/kanban/{{ $row['slug'] }}</div>
                                    @endif
                                </td>
                                <td>{{ $row['members'] }}</td>
                                <td class="number">{{ $row['total'] }}</td>
                                @foreach($statuses as $status => $label)
                                    <td class="number">{{ $row['statuses'][$status] ?? 0 }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <section class="panel">
                <h2>Indicadores por usuario</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Perfil</th>
                            <th>Grupos</th>
                            <th>Total</th>
                            @foreach($statuses as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userRows as $row)
                            <tr>
                                <td>
                                    <strong>{{ $row['name'] }}</strong>
                                    <div class="muted">{{ $row['email'] }}</div>
                                </td>
                                <td><span class="badge">{{ $row['is_admin'] ? 'Admin' : 'Usuario' }}</span></td>
                                <td class="number">{{ $row['groups'] }}</td>
                                <td class="number">{{ $row['total'] }}</td>
                                @foreach($statuses as $status => $label)
                                    <td class="number">{{ $row['statuses'][$status] ?? 0 }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </main>
</body>
</html>
