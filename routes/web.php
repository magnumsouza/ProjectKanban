<?php

use App\Models\Group;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$currentUser = function (): ?User {
    $sessionUser = session('user');

    return $sessionUser ? User::find($sessionUser['id']) : null;
};

$redirectTo = fn (string $path) => redirect()->to(url($path));
$loginRedirect = fn () => redirect()->to(url('/login'))->with('error', 'Acesso restrito.');

$groupRole = function (User $user, Group $group): ?string {
    if ($user->is_admin) {
        return 'admin';
    }

    $member = $user->groups()->where('groups.id', $group->id)->first();

    return $member?->pivot?->role;
};

$canManageGroup = function (User $user, Group $group): bool {
    return $user->is_admin || (int) $group->created_by === (int) $user->id;
};

$canEditTask = function (User $user, Task $task) use ($groupRole): bool {
    if ((int) $task->created_by === (int) $user->id || $user->is_admin) {
        return true;
    }

    if (! $task->group) {
        return false;
    }

    return in_array($groupRole($user, $task->group), ['editor', 'admin'], true);
};

$canDeleteTask = function (User $user, Task $task) use ($groupRole): bool {
    if ($user->is_admin) {
        return true;
    }

    if (! $task->group) {
        return (int) $task->created_by === (int) $user->id;
    }

    return $groupRole($user, $task->group) === 'admin';
};

$canUseGroupForTask = function (User $user, ?int $groupId) use ($groupRole): bool {
    if (! $groupId) {
        return true;
    }

    $group = Group::find($groupId);

    if (! $group) {
        return false;
    }

    return in_array($groupRole($user, $group), ['editor', 'admin'], true);
};

$visibleTasks = function (User $user) {
    return Task::with(['creator', 'group'])
        ->when(! $user->is_admin, function ($query) use ($user) {
            $query->where('created_by', $user->id)
                ->orWhereHas('group.users', fn ($groupUsers) => $groupUsers->where('users.id', $user->id));
        })
        ->orderByDesc('created_at')
        ->get();
};

$serializeTask = function (Task $task, User $user) use ($canEditTask, $canDeleteTask): array {
    return [
        'id' => $task->id,
        'title' => $task->title,
        'description' => $task->description,
        'status' => $task->status,
        'requester' => $task->requester,
        'assignee' => $task->assignee,
        'section' => $task->section,
        'createdAt' => $task->created_at?->format('Y-m-d'),
        'owner' => $task->creator?->name,
        'isOwner' => (int) $task->created_by === (int) $user->id,
        'groupId' => $task->group_id,
        'groupName' => $task->group?->name,
        'canEdit' => $canEditTask($user, $task),
        'canDelete' => $canDeleteTask($user, $task),
    ];
};

$groupPayload = function (User $user) use ($groupRole) {
    $groups = $user->is_admin
        ? Group::with('users')->orderBy('name')->get()
        : Group::with('users')->whereHas('users', fn ($query) => $query->where('users.id', $user->id))->orderBy('name')->get();

    return $groups->map(fn (Group $group) => [
        'id' => $group->id,
        'name' => $group->name,
        'slug' => $group->slug,
        'role' => $groupRole($user, $group),
    ])->values();
};

Route::get('/', fn () => view('login'));
Route::get('/login', fn () => view('login'));

Route::post('/login', function (Request $request) {
    $request->validate([
        'user' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->user)->orWhere('name', $request->user)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Usuario ou senha incorretos.');
    }

    session(['user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'is_admin' => (bool) $user->is_admin,
    ]]);

    return redirect()->to(url('/sistema'));
});

Route::post('/logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->to(url('/login'));
});

Route::get('/password/reset', fn () => view('password-reset'));

Route::get('/admin/users', function () {
    $sessionUser = session('user');

    if (! $sessionUser || ! $sessionUser['is_admin']) {
        return redirect()->to(url('/login'))->with('error', 'Acesso restrito ao administrador.');
    }

    return view('admin-users', [
        'users' => User::with('groups')->orderBy('id')->get(),
        'groups' => Group::orderBy('name')->get(),
    ]);
});

Route::post('/admin/users', function (Request $request) {
    $sessionUser = session('user');

    if (! $sessionUser || ! $sessionUser['is_admin']) {
        return redirect()->to(url('/login'))->with('error', 'Acesso restrito ao administrador.');
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8',
        'is_admin' => 'nullable|in:1',
        'group_roles' => 'nullable|array',
        'group_roles.*' => 'nullable|in:none,viewer,editor,admin',
    ]);

    $newUser = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'],
        'is_admin' => isset($data['is_admin']),
    ]);

    $sync = collect($data['group_roles'] ?? [])
        ->filter(fn ($role) => in_array($role, ['viewer', 'editor', 'admin'], true))
        ->mapWithKeys(fn ($role, $groupId) => [(int) $groupId => ['role' => $role]])
        ->all();

    $newUser->groups()->sync($sync);

    return back()->with('success', 'Usuario criado com sucesso.');
});

Route::put('/admin/users/{user}', function (Request $request, User $user) {
    $sessionUser = session('user');

    if (! $sessionUser || ! $sessionUser['is_admin']) {
        return redirect()->to(url('/login'))->with('error', 'Acesso restrito ao administrador.');
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        'password' => 'nullable|string|min:8',
        'is_admin' => 'nullable|in:1',
        'group_roles' => 'nullable|array',
        'group_roles.*' => 'nullable|in:none,viewer,editor,admin',
    ]);

    $payload = [
        'name' => $data['name'],
        'email' => $data['email'],
        'is_admin' => isset($data['is_admin']),
    ];

    if ((int) $sessionUser['id'] === (int) $user->id) {
        $payload['is_admin'] = true;
    }

    if (! empty($data['password'])) {
        $payload['password'] = $data['password'];
    }

    $user->update($payload);

    $sync = collect($data['group_roles'] ?? [])
        ->filter(fn ($role) => in_array($role, ['viewer', 'editor', 'admin'], true))
        ->mapWithKeys(fn ($role, $groupId) => [(int) $groupId => ['role' => $role]])
        ->all();

    $user->groups()->sync($sync);

    if ((int) $sessionUser['id'] === (int) $user->id) {
        session(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => true,
        ]]);
    }

    return back()->with('success', 'Usuario atualizado com sucesso.');
});

Route::get('/sistema', function () use ($currentUser, $loginRedirect, $visibleTasks, $serializeTask, $groupPayload) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    $groups = $groupPayload($user);

    return view('sistema', [
        'user' => session('user'),
        'groups' => $groups,
        'shareGroups' => $groups->whereIn('role', ['editor', 'admin'])->values(),
        'tasks' => $visibleTasks($user)->map(fn (Task $task) => $serializeTask($task, $user))->values(),
    ]);
});

Route::get('/dashboard', function () use ($currentUser) {
    $user = $currentUser();

    if (! $user || ! $user->is_admin) {
        return redirect()->to(url('/login'))->with('error', 'Acesso restrito ao administrador.');
    }

    $statuses = [
        'nao-iniciado' => 'Nao iniciado',
        'em-andamento' => 'Em andamento',
        'prorrogado' => 'Prorrogado',
        'concluido' => 'Concluido',
    ];

    $tasks = Task::with(['creator', 'group'])->get();
    $statusTotals = collect($statuses)
        ->mapWithKeys(fn ($label, $status) => [$status => $tasks->where('status', $status)->count()]);

    $groups = Group::with('users')->orderBy('name')->get();
    $groupRows = $groups->map(function (Group $group) use ($statuses, $tasks) {
        $groupTasks = $tasks->where('group_id', $group->id);

        return [
            'name' => $group->name,
            'slug' => $group->slug,
            'members' => $group->users->count(),
            'total' => $groupTasks->count(),
            'statuses' => collect($statuses)->mapWithKeys(fn ($label, $status) => [$status => $groupTasks->where('status', $status)->count()]),
        ];
    });

    $privateTasks = $tasks->whereNull('group_id');
    $privateRow = [
        'name' => 'Privadas',
        'slug' => null,
        'members' => '-',
        'total' => $privateTasks->count(),
        'statuses' => collect($statuses)->mapWithKeys(fn ($label, $status) => [$status => $privateTasks->where('status', $status)->count()]),
    ];

    $users = User::with('groups')->orderBy('name')->get();
    $userRows = $users->map(function (User $listedUser) use ($statuses, $tasks) {
        $userTasks = $tasks->where('created_by', $listedUser->id);

        return [
            'name' => $listedUser->name,
            'email' => $listedUser->email,
            'is_admin' => $listedUser->is_admin,
            'groups' => $listedUser->groups->count(),
            'total' => $userTasks->count(),
            'statuses' => collect($statuses)->mapWithKeys(fn ($label, $status) => [$status => $userTasks->where('status', $status)->count()]),
        ];
    });

    return view('dashboard', [
        'user' => session('user'),
        'statuses' => $statuses,
        'statusTotals' => $statusTotals,
        'totalTasks' => $tasks->count(),
        'totalGroups' => $groups->count(),
        'totalUsers' => $users->count(),
        'privateTasks' => $privateTasks->count(),
        'groupRows' => $groupRows->push($privateRow),
        'userRows' => $userRows,
    ]);
});

Route::get('/kanban', fn () => redirect()->to(url('/sistema')));

Route::get('/groups', function () use ($currentUser, $loginRedirect) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    $groups = Group::with('users')
        ->when(! $user->is_admin, fn ($query) => $query->where('created_by', $user->id))
        ->orderBy('name')
        ->get();

    return view('admin-groups', [
        'groups' => $groups,
        'users' => User::orderBy('name')->get(),
        'currentUser' => $user,
    ]);
});

Route::get('/admin/groups', fn () => redirect()->to(url('/groups')));

Route::post('/groups', function (Request $request) use ($currentUser, $loginRedirect) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:groups,slug',
        'description' => 'nullable|string|max:1000',
    ]);

    $slug = $data['slug'] ?: Str::slug($data['name']);

    if (Group::where('slug', $slug)->exists()) {
        return back()->withInput()->with('error', 'Ja existe um grupo com este endereco.');
    }

    $group = Group::create([
        'name' => $data['name'],
        'slug' => $slug,
        'description' => $data['description'] ?? null,
        'created_by' => $user->id,
    ]);

    $group->users()->sync([$user->id => ['role' => 'admin']]);

    return back()->with('success', 'Grupo criado e provisionado com sucesso.');
});

Route::post('/groups/{group}/users', function (Request $request, Group $group) use ($currentUser, $loginRedirect, $canManageGroup) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    if (! $canManageGroup($user, $group)) {
        return back()->with('error', 'Somente o administrador do grupo pode alterar membros.');
    }

    $data = $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'role' => 'required|in:viewer,editor,admin',
    ]);

    $group->users()->syncWithoutDetaching([
        $data['user_id'] => ['role' => $data['role']],
    ]);

    return back()->with('success', 'Usuario incluido no grupo com sucesso.');
});

Route::put('/groups/{group}/users/{member}', function (Request $request, Group $group, User $member) use ($currentUser, $loginRedirect, $canManageGroup) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    if (! $canManageGroup($user, $group)) {
        return back()->with('error', 'Somente o administrador do grupo pode alterar membros.');
    }

    $data = $request->validate([
        'role' => 'required|in:viewer,editor,admin',
    ]);

    if ((int) $member->id === (int) $group->created_by) {
        $data['role'] = 'admin';
    }

    $group->users()->syncWithoutDetaching([
        $member->id => ['role' => $data['role']],
    ]);

    return back()->with('success', 'Membros do grupo atualizados com sucesso.');
});

Route::delete('/groups/{group}/users/{member}', function (Group $group, User $member) use ($currentUser, $loginRedirect, $canManageGroup) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    if (! $canManageGroup($user, $group)) {
        return back()->with('error', 'Somente o administrador do grupo pode remover membros.');
    }

    if ((int) $member->id === (int) $group->created_by) {
        return back()->with('error', 'O criador do grupo nao pode ser removido.');
    }

    $group->users()->detach($member->id);

    return back()->with('success', 'Usuario removido do grupo com sucesso.');
});

Route::put('/admin/groups/{group}/users', fn () => redirect()->to(url('/groups')));

Route::get('/kanban/{group}', function ($group) use ($currentUser, $loginRedirect, $groupRole) {
    $user = $currentUser();

    if (! $user) {
        return $loginRedirect();
    }

    $groupModel = Group::where('slug', $group)->first();

    if (! $groupModel || ! $groupRole($user, $groupModel)) {
        return redirect()->to(url('/sistema'))->with('error', 'Acesso restrito ao grupo.');
    }

    return redirect()->to(url('/sistema').'?group='.$groupModel->slug);
});

Route::post('/tasks', function (Request $request) use ($currentUser, $canUseGroupForTask, $serializeTask) {
    $user = $currentUser();

    abort_if(! $user, 401);

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:5000',
        'status' => 'required|in:nao-iniciado,em-andamento,prorrogado,concluido',
        'requester' => 'nullable|string|max:255',
        'assignee' => 'nullable|string|max:255',
        'section' => 'nullable|string|max:255',
        'group_id' => 'nullable|integer|exists:groups,id',
    ]);

    abort_if(! $canUseGroupForTask($user, $data['group_id'] ?? null), 403);

    $task = Task::create([
        ...$data,
        'created_by' => $user->id,
        'group_id' => $data['group_id'] ?? null,
    ])->load(['creator', 'group']);

    return response()->json($serializeTask($task, $user), 201);
});

Route::put('/tasks/{task}', function (Request $request, Task $task) use ($currentUser, $canEditTask, $canUseGroupForTask, $serializeTask) {
    $user = $currentUser();

    abort_if(! $user, 401);
    abort_if(! $canEditTask($user, $task->load('group')), 403);

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:5000',
        'status' => 'required|in:nao-iniciado,em-andamento,prorrogado,concluido',
        'requester' => 'nullable|string|max:255',
        'assignee' => 'nullable|string|max:255',
        'section' => 'nullable|string|max:255',
        'group_id' => 'nullable|integer|exists:groups,id',
    ]);

    abort_if(! $canUseGroupForTask($user, $data['group_id'] ?? null), 403);

    $task->update([
        ...$data,
        'group_id' => $data['group_id'] ?? null,
    ]);

    return response()->json($serializeTask($task->fresh(['creator', 'group']), $user));
});

Route::patch('/tasks/{task}/status', function (Request $request, Task $task) use ($currentUser, $canEditTask, $serializeTask) {
    $user = $currentUser();

    abort_if(! $user, 401);
    abort_if(! $canEditTask($user, $task->load('group')), 403);

    $data = $request->validate([
        'status' => 'required|in:nao-iniciado,em-andamento,prorrogado,concluido',
    ]);

    $task->update(['status' => $data['status']]);

    return response()->json($serializeTask($task->fresh(['creator', 'group']), $user));
});

Route::delete('/tasks/{task}', function (Task $task) use ($currentUser, $canDeleteTask) {
    $user = $currentUser();

    abort_if(! $user, 401);
    abort_if(! $canDeleteTask($user, $task->load('group')), 403);

    $task->delete();

    return response()->noContent();
});
