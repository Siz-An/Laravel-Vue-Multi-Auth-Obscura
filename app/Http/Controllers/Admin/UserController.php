<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): Response
    {
        $users = User::with('roles')->get();
        
        return Inertia::render('admin/users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): Response
    {
        $roles = Role::all();
        return Inertia::render('admin/users/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_super_admin' => 'boolean',
        ]);

        $user = User::create($request->only('name', 'email', 'is_super_admin') + [
            'password' => bcrypt($request->password),
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): Response
    {
        $user->load('roles');
        $roles = Role::all();
        
        return Inertia::render('admin/users/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_super_admin' => 'boolean',
        ]);

        $userData = $request->only('name', 'email', 'is_super_admin');
        
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting the current user
        if (Auth::check() && $user->is(Auth::user())) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
