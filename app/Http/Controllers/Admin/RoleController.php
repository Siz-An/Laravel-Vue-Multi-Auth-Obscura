<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(): Response
    {
        $roles = Role::all();
        
        return Inertia::render('admin/roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): Response
    {
        return Inertia::render('admin/roles/Create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
        ]);

        Role::create($request->only('name', 'description'));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): Response
    {
        return Inertia::render('admin/roles/Edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($request->only('name', 'description'));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
