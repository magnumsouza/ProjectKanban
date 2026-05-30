<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DTI SISKanban</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brasao-exercito.svg') }}">
    <style>
        :root { --bg:#07121d; --surface:rgba(8,18,29,.95); --surface-light:rgba(15,27,44,.9); --text:#eef5ff; --text-muted:#a1b0c8; --accent:#5dd4ff; --success:#34d399; --warning:#fbbf24; --danger:#f87171; --border:rgba(148,163,184,.16); --shadow-soft:0 18px 40px rgba(0,0,0,.18); --card-bg:rgba(18,29,44,.98); --button-bg:linear-gradient(135deg,#4fd1ff,#0ea5e9); --button-text:#0b1120; --panel-bg:rgba(12,21,34,.96); --panel-border:rgba(148,163,184,.14); --input-bg:rgba(15,23,42,.9); --input-border:rgba(148,163,184,.22); }
        [data-theme="light"] { --bg:#eff6ff; --surface:#fff; --surface-light:#f8fbff; --text:#0f172a; --text-muted:#64748b; --card-bg:#fff; --button-bg:linear-gradient(135deg,#38bdf8,#0ea5e9); --button-text:#0f172a; --panel-bg:rgba(255,255,255,.98); --panel-border:rgba(148,163,184,.18); --input-bg:#f8fafc; --input-border:rgba(148,163,184,.22); }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; font-family:Inter,system-ui,sans-serif; background:linear-gradient(180deg,#07121d 0%,#081830 40%,#060b15 100%); color:var(--text); }
        [data-theme="light"] body { background:linear-gradient(180deg,#f8fbff 0%,#eef6ff 45%,#f1f5f9 100%); }
        .page { width:100%; max-width:1500px; margin:0 auto; padding:18px 24px 44px; }
        .navbar { position:sticky; top:0; z-index:20; display:grid; grid-template-columns:minmax(230px,.8fr) auto auto; gap:18px; align-items:center; padding:14px 16px; margin:0 0 18px; border:1px solid var(--border); border-radius:22px; background:rgba(8,18,29,.92); box-shadow:var(--shadow-soft); backdrop-filter:blur(16px); }
        [data-theme="light"] .navbar { background:rgba(255,255,255,.92); }
        .brand-block { display:flex; align-items:center; gap:12px; min-width:0; }
        .brand-mark { width:48px; height:48px; display:grid; place-items:center; border-radius:14px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); flex:0 0 auto; }
        .brand-mark img { width:40px; height:40px; object-fit:contain; display:block; }
        .brand-copy { min-width:0; }
        .brand-copy h1 { margin:0; font-size:1.34rem; line-height:1.1; }
        .brand-copy small, .muted { color:var(--text-muted); }
        .navbar-section { min-width:0; position:relative; }
        .navbar-label { display:block; margin:0 0 8px; color:var(--text-muted); font-size:.72rem; text-transform:uppercase; letter-spacing:.08em; }
        .action-group, .group-links { display:flex; flex-wrap:wrap; align-items:center; gap:10px; }
        .navbar-actions { justify-content:flex-end; }
        .groups-menu-button { min-width:190px; justify-content:space-between; }
        .groups-dropdown { position:absolute; top:calc(100% + 10px); right:0; width:min(360px,calc(100vw - 32px)); max-height:360px; overflow:auto; padding:12px; border:1px solid var(--border); border-radius:18px; background:rgba(8,18,29,.98); box-shadow:0 24px 60px rgba(0,0,0,.28); display:none; }
        [data-theme="light"] .groups-dropdown { background:rgba(255,255,255,.98); }
        .navbar-section.open .groups-dropdown { display:block; }
        .group-links { display:grid; gap:8px; }
        .group-link { display:inline-flex; padding:8px 12px; border-radius:999px; border:1px solid rgba(56,189,248,.18); color:var(--accent); background:rgba(56,189,248,.08); text-decoration:none; font-size:.88rem; font-weight:700; }
        .button-primary, .button-secondary { font:inherit; display:inline-flex; align-items:center; justify-content:center; gap:10px; border-radius:999px; padding:14px 20px; cursor:pointer; text-decoration:none; transition:background .2s ease, border-color .2s ease, color .2s ease, filter .2s ease, transform .2s ease, box-shadow .2s ease; }
        .button-primary { border:0; background:var(--button-bg); color:var(--button-text); font-weight:800; text-transform:uppercase; }
        .button-secondary { border:1px solid var(--panel-border); background:rgba(255,255,255,.08); color:var(--text); }
        .button-primary:hover { filter:brightness(1.12) saturate(1.08); transform:translateY(-1px); box-shadow:0 16px 34px rgba(14,165,233,.22); }
        .button-secondary:hover { background:rgba(56,189,248,.16); border-color:rgba(56,189,248,.42); color:#dff7ff; transform:translateY(-1px); }
        .button-primary:focus-visible, .button-secondary:focus-visible { outline:3px solid rgba(56,189,248,.35); outline-offset:3px; }
        .workspace-header { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin:0 0 18px; padding:0 4px; color:var(--text-muted); }
        .workspace-header strong { color:var(--text); }
        .filters { display:grid; grid-template-columns:minmax(200px,1.35fr) repeat(4,minmax(132px,1fr)) auto; gap:10px; align-items:end; margin:0 0 16px; padding:12px; border:1px solid var(--border); border-radius:18px; background:rgba(8,18,29,.72); box-shadow:var(--shadow-soft); }
        [data-theme="light"] .filters { background:rgba(255,255,255,.84); }
        .filter-field { display:grid; gap:5px; min-width:0; }
        .filter-field label { margin:0; }
        .filters input, .filters select { padding:9px 11px; border-radius:12px; font-size:.88rem; }
        .filters .button-secondary { padding:10px 14px; min-height:40px; }
        .board { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:20px; align-items:start; }
        .column { background:linear-gradient(180deg,var(--surface-light),var(--surface)); border:1px solid var(--border); border-radius:24px; padding:20px; min-height:560px; display:flex; flex-direction:column; box-shadow:var(--shadow-soft); }
        .column h2 { margin:0 0 6px; font-size:1.05rem; }
        .column small { color:var(--text-muted); font-size:.92rem; }
        .column-title { display:flex; justify-content:space-between; gap:10px; align-items:center; }
        .column-count { display:inline-flex; min-width:34px; height:28px; align-items:center; justify-content:center; border-radius:999px; background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.18); color:var(--accent); font-weight:900; font-size:.82rem; }
        .column-body { flex:1; display:grid; gap:16px; align-content:start; margin-top:18px; }
        .task-card { background:var(--card-bg); border:1px solid rgba(148,163,184,.12); border-radius:18px; padding:18px; box-shadow:var(--shadow-soft); }
        .task-card.status-nao-iniciado-card { background:linear-gradient(180deg,rgba(56,189,248,.16),var(--card-bg)); border-color:rgba(56,189,248,.34); }
        .task-card.status-em-andamento-card { background:linear-gradient(180deg,rgba(251,191,36,.16),var(--card-bg)); border-color:rgba(251,191,36,.34); }
        .task-card.status-prorrogado-card { background:linear-gradient(180deg,rgba(248,113,113,.16),var(--card-bg)); border-color:rgba(248,113,113,.34); }
        .task-card.status-concluido-card { background:linear-gradient(180deg,rgba(52,211,153,.16),var(--card-bg)); border-color:rgba(52,211,153,.34); }
        .task-card[data-editable="true"] { cursor:grab; }
        .task-card h3 { margin:0 0 10px; font-size:1.02rem; line-height:1.3; }
        .task-card p { margin:0 0 14px; color:var(--text-muted); line-height:1.65; font-size:.94rem; }
        .task-head { display:flex; justify-content:space-between; align-items:start; gap:10px; }
        .task-actions { display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end; }
        .task-actions button { border:0; background:rgba(56,189,248,.12); color:var(--text); padding:8px 10px; border-radius:999px; cursor:pointer; font-weight:700; }
        .task-actions .danger { background:rgba(248,113,113,.14); color:#fecaca; }
        .properties { display:grid; gap:8px; margin-top:14px; }
        .property { display:flex; justify-content:space-between; gap:10px; color:var(--text-muted); font-size:.86rem; }
        .status-pill { display:inline-flex; align-items:center; justify-content:center; padding:7px 12px; border-radius:999px; font-size:.78rem; font-weight:800; color:#111827; text-transform:uppercase; }
        .status-nao-iniciado { background:#38bdf8; } .status-em-andamento { background:#fbbf24; } .status-prorrogado { background:#f87171; } .status-concluido { background:#34d399; }
        .drag-over { outline:2px dashed rgba(56,189,248,.65); outline-offset:-6px; }
        .modal-overlay { position:fixed; inset:0; background:rgba(5,11,20,.85); display:none; align-items:center; justify-content:center; padding:20px; z-index:50; overflow-y:auto; backdrop-filter:blur(8px); }
        .modal-overlay.active { display:flex; }
        .modal { width:min(100%,740px); background:var(--panel-bg); border-radius:24px; padding:28px; border:1px solid var(--panel-border); max-height:calc(100vh - 80px); overflow-y:auto; position:relative; }
        .modal h2 { margin:0 0 8px; font-size:1.4rem; }
        .modal p { margin:0 0 22px; color:var(--text-muted); }
        .form-grid { display:grid; gap:16px; grid-template-columns:1fr 1fr; }
        .form-grid .full { grid-column:1 / -1; }
        label { display:block; font-size:.82rem; margin-bottom:8px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em; }
        input, select, textarea { width:100%; padding:14px 16px; border:1px solid var(--input-border); border-radius:14px; background:var(--input-bg); color:var(--text); font-size:.96rem; }
        textarea { resize:vertical; min-height:110px; }
        .modal footer { margin-top:24px; display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; }
        .overlay-close { position:absolute; top:18px; right:18px; width:38px; height:38px; border:0; border-radius:50%; background:rgba(148,163,184,.12); color:var(--text); cursor:pointer; }
        @media (max-width:1120px) { .navbar { grid-template-columns:1fr; align-items:stretch; } .navbar-actions { justify-content:flex-start; } .groups-dropdown { left:0; right:auto; } .filters { grid-template-columns:repeat(2,minmax(0,1fr)); } .board { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width:720px) { .page { padding:12px 12px 30px; } .board, .form-grid, .filters { grid-template-columns:1fr; } .button-primary, .button-secondary { width:100%; } .action-group { width:100%; } .navbar { border-radius:18px; } }
    </style>
</head>
<body>
    @php
        $loggedUser = $user ?? null;
        $isAdmin = $loggedUser['is_admin'] ?? false;
        $availableGroups = collect($groups ?? []);
        $editableGroups = collect($shareGroups ?? []);
    @endphp
    <div class="page">
        <nav class="navbar" aria-label="Navegacao principal">
            <div class="brand-block">
                <span class="brand-mark"><img src="{{ asset('images/brasao-exercito.svg') }}" alt="Brasao"></span>
                <div class="brand-copy">
                    <h1>DTI SISKanban</h1>
                    <small>Controle de tarefas e grupos</small>
                </div>
            </div>

            <div class="navbar-section">
                <button class="button-secondary groups-menu-button" id="groupsMenuButton" type="button" aria-expanded="false" aria-controls="groupsDropdown">
                    Grupos <span>{{ $availableGroups->count() }}</span>
                </button>
                <div class="groups-dropdown" id="groupsDropdown">
                    <span class="navbar-label">Grupos vinculados</span>
                    <div class="group-links">
                        @forelse($availableGroups as $availableGroup)
                            <a class="group-link" href="{{ url('/kanban/'.$availableGroup['slug']) }}">{{ $availableGroup['name'] }} - {{ $availableGroup['role'] }}</a>
                        @empty
                            <span class="muted">Nenhum grupo vinculado</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="action-group navbar-actions">
                <button class="button-primary" id="openAddTaskModal">Adicionar tarefa</button>
                <button class="button-secondary" id="themeToggle" aria-label="Alternar tema">Tema</button>
                <a href="{{ url('/groups') }}" class="button-secondary">Grupos</a>
                @if($isAdmin)
                    <a href="{{ url('/dashboard') }}" class="button-secondary">Dashboard</a>
                    <a href="{{ url('/admin/users') }}" class="button-secondary">Usuarios</a>
                @endif
                <form method="POST" action="{{ url('/logout') }}" style="margin:0; display:inline-flex;">
                    @csrf
                    <button class="button-secondary" type="submit">Sair</button>
                </form>
            </div>
        </nav>

        <div class="workspace-header">
            <div>Ola, <strong>{{ $loggedUser['name'] }}</strong> {{ $isAdmin ? '(Administrador)' : '' }}</div>
            <div>Cada usuario ve suas criacoes e o que foi compartilhado por grupo.</div>
        </div>

        <section class="filters" aria-label="Filtros do Kanban">
            <div class="filter-field">
                <label for="filterSearch">Buscar</label>
                <input id="filterSearch" type="search" placeholder="Titulo, descricao, secao...">
            </div>
            <div class="filter-field">
                <label for="filterGroup">Grupo</label>
                <select id="filterGroup">
                    <option value="all">Todos</option>
                    <option value="private">Privadas</option>
                    @foreach($availableGroups as $availableGroup)
                        <option value="{{ $availableGroup['id'] }}">{{ $availableGroup['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label for="filterAssignee">Responsavel</label>
                <select id="filterAssignee">
                    <option value="all">Todos</option>
                </select>
            </div>
            <div class="filter-field">
                <label for="filterStatus">Status</label>
                <select id="filterStatus">
                    <option value="all">Todos</option>
                    <option value="nao-iniciado">Nao iniciado</option>
                    <option value="em-andamento">Em andamento</option>
                    <option value="prorrogado">Prorrogado</option>
                    <option value="concluido">Concluido</option>
                </select>
            </div>
            <div class="filter-field">
                <label for="filterScope">Escopo</label>
                <select id="filterScope">
                    <option value="all">Tudo que posso ver</option>
                    <option value="mine">Minhas criacoes</option>
                    <option value="shared">Compartilhadas</option>
                </select>
            </div>
            <button class="button-secondary" id="clearFilters" type="button">Limpar</button>
        </section>

        <div class="board" id="kanbanBoard">
            <section class="column" data-status="nao-iniciado"><div><div class="column-title"><h2>Nao iniciado</h2><span class="column-count" id="count-nao-iniciado">0</span></div><small>Novas tarefas aguardam definicao.</small></div><div class="column-body" id="board-nao-iniciado"></div></section>
            <section class="column" data-status="em-andamento"><div><div class="column-title"><h2>Em andamento</h2><span class="column-count" id="count-em-andamento">0</span></div><small>Tarefas em progresso.</small></div><div class="column-body" id="board-em-andamento"></div></section>
            <section class="column" data-status="prorrogado"><div><div class="column-title"><h2>Prorrogado</h2><span class="column-count" id="count-prorrogado">0</span></div><small>Tarefas adiadas.</small></div><div class="column-body" id="board-prorrogado"></div></section>
            <section class="column" data-status="concluido"><div><div class="column-title"><h2>Concluido</h2><span class="column-count" id="count-concluido">0</span></div><small>Itens finalizados.</small></div><div class="column-body" id="board-concluido"></div></section>
        </div>
    </div>

    <div class="modal-overlay" id="taskModalOverlay">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <button class="overlay-close" id="closeModal">x</button>
            <h2 id="modalTitle">Nova tarefa</h2>
            <p>Sem grupo, somente o criador visualiza. Com grupo, as permissoes dos membros passam a valer.</p>
            <div class="form-grid">
                <div class="full"><label for="taskTitle">Titulo</label><input id="taskTitle" type="text" placeholder="Digite o titulo"></div>
                <div><label for="taskRequester">Solicitado por</label><input id="taskRequester" type="text"></div>
                <div><label for="taskAssignee">Responsavel</label><input id="taskAssignee" type="text"></div>
                <div><label for="taskSection">Secao</label><input id="taskSection" type="text"></div>
                <div><label for="taskStatus">Status</label><select id="taskStatus"><option value="nao-iniciado">Nao iniciado</option><option value="em-andamento">Em andamento</option><option value="prorrogado">Prorrogado</option><option value="concluido">Concluido</option></select></div>
                <div class="full"><label for="taskGroup">Compartilhar com grupo</label><select id="taskGroup"><option value="">Somente eu</option>@foreach($editableGroups as $group)<option value="{{ $group['id'] }}">{{ $group['name'] }} - {{ $group['role'] }}</option>@endforeach</select></div>
                <div class="full"><label for="taskDescription">Descricao</label><textarea id="taskDescription" rows="5"></textarea></div>
            </div>
            <footer>
                <button class="button-secondary" id="cancelModal">Cancelar</button>
                <div class="action-group">
                    <button class="button-secondary" id="deleteTaskButton" hidden>Excluir</button>
                    <button class="button-primary" id="saveTaskButton">Salvar tarefa</button>
                </div>
            </footer>
            <input type="hidden" id="taskId">
        </div>
    </div>

    <script>
        let tasks = @json($tasks ?? []);
        const appBaseUrl = @json(url('/'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const board = {
            'nao-iniciado': document.getElementById('board-nao-iniciado'),
            'em-andamento': document.getElementById('board-em-andamento'),
            'prorrogado': document.getElementById('board-prorrogado'),
            'concluido': document.getElementById('board-concluido')
        };
        const modalOverlay = document.getElementById('taskModalOverlay');
        const modalTitle = document.getElementById('modalTitle');
        const taskIdInput = document.getElementById('taskId');
        const taskTitle = document.getElementById('taskTitle');
        const taskRequester = document.getElementById('taskRequester');
        const taskAssignee = document.getElementById('taskAssignee');
        const taskSection = document.getElementById('taskSection');
        const taskStatus = document.getElementById('taskStatus');
        const taskGroup = document.getElementById('taskGroup');
        const taskDescription = document.getElementById('taskDescription');
        const saveTaskButton = document.getElementById('saveTaskButton');
        const deleteTaskButton = document.getElementById('deleteTaskButton');
        const filterSearch = document.getElementById('filterSearch');
        const filterGroup = document.getElementById('filterGroup');
        const filterAssignee = document.getElementById('filterAssignee');
        const filterStatus = document.getElementById('filterStatus');
        const filterScope = document.getElementById('filterScope');
        const clearFilters = document.getElementById('clearFilters');
        const counters = {
            'nao-iniciado': document.getElementById('count-nao-iniciado'),
            'em-andamento': document.getElementById('count-em-andamento'),
            'prorrogado': document.getElementById('count-prorrogado'),
            'concluido': document.getElementById('count-concluido')
        };

        const requestJson = async (url, options = {}) => {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    ...(options.headers || {})
                }
            });
            if (!response.ok) throw new Error('Operacao nao permitida.');
            return response.status === 204 ? null : response.json();
        };

        const formatStatusLabel = status => ({'nao-iniciado':'Nao iniciado','em-andamento':'Em andamento','prorrogado':'Prorrogado','concluido':'Concluido'}[status] || status);
        const text = value => String(value || '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));

        const createCard = task => {
            const card = document.createElement('article');
            card.className = `task-card status-${task.status}-card`;
            card.draggable = !!task.canEdit;
            card.dataset.editable = task.canEdit ? 'true' : 'false';
            card.dataset.id = task.id;
            const edit = task.canEdit ? `<button type="button" data-action="edit">Editar</button>` : '';
            const remove = task.canDelete ? `<button type="button" class="danger" data-action="delete">Excluir</button>` : '';
            card.innerHTML = `
                <div class="task-head">
                    <h3>${text(task.title)}</h3>
                    <div class="task-actions">${edit}${remove}</div>
                </div>
                <p>${text(task.description || 'Sem descricao definida.')}</p>
                <div class="properties">
                    <div class="property"><span>Dono</span><strong>${text(task.owner || '-')}</strong></div>
                    <div class="property"><span>Grupo</span><strong>${text(task.groupName || 'Privado')}</strong></div>
                    <div class="property"><span>Solicitado por</span><strong>${text(task.requester || '-')}</strong></div>
                    <div class="property"><span>Responsavel</span><strong>${text(task.assignee || '-')}</strong></div>
                    <div class="property"><span>Secao</span><strong>${text(task.section || '-')}</strong></div>
                    <div class="property"><span>Data</span><strong>${text(task.createdAt || '')}</strong></div>
                </div>
                <div style="margin-top:14px;"><span class="status-pill status-${task.status}">${formatStatusLabel(task.status)}</span></div>
            `;
            if (task.canEdit) {
                card.addEventListener('dragstart', event => event.dataTransfer.setData('text/plain', task.id));
                card.querySelector('[data-action="edit"]')?.addEventListener('click', () => openModal(task));
            }
            if (task.canDelete) {
                card.querySelector('[data-action="delete"]')?.addEventListener('click', () => deleteTask(task.id));
            }
            return card;
        };

        const normalize = value => String(value || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

        const refreshAssigneeOptions = () => {
            const current = filterAssignee.value;
            const assignees = [...new Set(tasks.map(task => task.assignee).filter(Boolean))]
                .sort((a, b) => a.localeCompare(b));

            filterAssignee.innerHTML = '<option value="all">Todos</option>';
            assignees.forEach(assignee => {
                const option = document.createElement('option');
                option.value = assignee;
                option.textContent = assignee;
                filterAssignee.appendChild(option);
            });

            if (assignees.includes(current)) {
                filterAssignee.value = current;
            }
        };

        const getFilteredTasks = () => {
            const search = normalize(filterSearch.value);
            const group = filterGroup.value;
            const assignee = filterAssignee.value;
            const status = filterStatus.value;
            const scope = filterScope.value;

            return tasks.filter(task => {
                const haystack = normalize([
                    task.title,
                    task.description,
                    task.requester,
                    task.assignee,
                    task.section,
                    task.owner,
                    task.groupName
                ].join(' '));

                const matchesSearch = !search || haystack.includes(search);
                const matchesGroup = group === 'all' || (group === 'private' ? !task.groupId : String(task.groupId) === group);
                const matchesAssignee = assignee === 'all' || task.assignee === assignee;
                const matchesStatus = status === 'all' || task.status === status;
                const matchesScope = scope === 'all'
                    || (scope === 'mine' && task.isOwner)
                    || (scope === 'shared' && !task.isOwner && task.groupId);

                return matchesSearch && matchesGroup && matchesAssignee && matchesStatus && matchesScope;
            });
        };

        const renderBoard = () => {
            Object.values(board).forEach(column => column.innerHTML = '');
            Object.values(counters).forEach(counter => counter.textContent = '0');

            const visibleTasks = getFilteredTasks();
            visibleTasks.forEach(task => {
                board[task.status]?.appendChild(createCard(task));
                if (counters[task.status]) {
                    counters[task.status].textContent = Number(counters[task.status].textContent) + 1;
                }
            });
        };

        const openModal = (task = null) => {
            modalOverlay.classList.add('active');
            modalTitle.textContent = task ? 'Editar tarefa' : 'Nova tarefa';
            taskIdInput.value = task?.id || '';
            taskTitle.value = task?.title || '';
            taskRequester.value = task?.requester || '';
            taskAssignee.value = task?.assignee || '';
            taskSection.value = task?.section || '';
            taskStatus.value = task?.status || 'nao-iniciado';
            taskGroup.value = task?.groupId || '';
            taskDescription.value = task?.description || '';
            deleteTaskButton.hidden = !task?.canDelete;
        };

        const closeModal = () => modalOverlay.classList.remove('active');

        const formPayload = () => ({
            title: taskTitle.value.trim(),
            requester: taskRequester.value.trim(),
            assignee: taskAssignee.value.trim(),
            section: taskSection.value.trim(),
            status: taskStatus.value,
            group_id: taskGroup.value ? Number(taskGroup.value) : null,
            description: taskDescription.value.trim()
        });

        const saveTask = async () => {
            if (!taskTitle.value.trim()) return taskTitle.focus();
            const id = taskIdInput.value;
            const saved = await requestJson(id ? `${appBaseUrl}/tasks/${id}` : `${appBaseUrl}/tasks`, {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(formPayload())
            });
            tasks = id ? tasks.map(task => task.id === saved.id ? saved : task) : [saved, ...tasks];
            closeModal();
            refreshAssigneeOptions();
            renderBoard();
        };

        const deleteTask = async id => {
            if (!confirm('Excluir esta tarefa?')) return;
            await requestJson(`${appBaseUrl}/tasks/${id}`, { method: 'DELETE' });
            tasks = tasks.filter(task => task.id !== Number(id));
            closeModal();
            refreshAssigneeOptions();
            renderBoard();
        };

        const updateTaskStatus = async (taskId, status) => {
            const updated = await requestJson(`${appBaseUrl}/tasks/${taskId}/status`, { method: 'PATCH', body: JSON.stringify({ status }) });
            tasks = tasks.map(task => task.id === updated.id ? updated : task);
            renderBoard();
        };

        Object.entries(board).forEach(([status, column]) => {
            const section = column.parentElement;
            section.addEventListener('dragover', event => {
                event.preventDefault();
                section.classList.add('drag-over');
            });
            section.addEventListener('dragleave', () => section.classList.remove('drag-over'));
            section.addEventListener('drop', event => {
                event.preventDefault();
                const taskId = event.dataTransfer.getData('text/plain');
                if (taskId) updateTaskStatus(taskId, status).catch(alert);
                section.classList.remove('drag-over');
            });
        });

        document.getElementById('openAddTaskModal').addEventListener('click', () => openModal());
        const groupsMenuButton = document.getElementById('groupsMenuButton');
        const groupsMenu = groupsMenuButton.closest('.navbar-section');
        groupsMenuButton.addEventListener('click', event => {
            event.stopPropagation();
            const isOpen = groupsMenu.classList.toggle('open');
            groupsMenuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        document.addEventListener('click', event => {
            if (!groupsMenu.contains(event.target)) {
                groupsMenu.classList.remove('open');
                groupsMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('cancelModal').addEventListener('click', closeModal);
        saveTaskButton.addEventListener('click', () => saveTask().catch(alert));
        deleteTaskButton.addEventListener('click', () => deleteTask(taskIdInput.value).catch(alert));
        [filterSearch, filterGroup, filterAssignee, filterStatus, filterScope].forEach(field => {
            field.addEventListener('input', renderBoard);
            field.addEventListener('change', renderBoard);
        });
        clearFilters.addEventListener('click', () => {
            filterSearch.value = '';
            filterGroup.value = 'all';
            filterAssignee.value = 'all';
            filterStatus.value = 'all';
            filterScope.value = 'all';
            renderBoard();
        });
        modalOverlay.addEventListener('click', event => { if (event.target === modalOverlay) closeModal(); });
        document.getElementById('themeToggle').addEventListener('click', () => {
            const next = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
            document.documentElement.dataset.theme = next;
            localStorage.setItem('kanbanTheme', next);
        });
        document.documentElement.dataset.theme = localStorage.getItem('kanbanTheme') || 'dark';
        refreshAssigneeOptions();
        renderBoard();
    </script>
</body>
</html>
