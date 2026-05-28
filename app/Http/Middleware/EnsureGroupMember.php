<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class EnsureGroupMember
{
    public function handle(Request $request, Closure $next, $groupSlug = null)
    {
        $sessionUser = session('user');

        if (! $sessionUser) {
            return redirect('/login')->with('error', 'Acesso restrito.');
        }

        $user = User::find($sessionUser['id']);

        if (! $user) {
            return redirect('/login')->with('error', 'Usuário não encontrado.');
        }

        if ($groupSlug) {
            $isMember = $user->groups()->where('slug', $groupSlug)->exists();
        } else {
            $routeGroup = $request->route('group') ?? $request->input('group');
            $isMember = $routeGroup && $user->groups()->where('slug', $routeGroup)->exists();
        }

        if (! $isMember) {
            return redirect('/login')->with('error', 'Acesso restrito ao grupo.');
        }

        return $next($request);
    }
}
