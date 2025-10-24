<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use Flasher\Laravel\Facade\Flasher;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(): Response
    {
        $roles = Role::with('permissions')->get();
        
        return Inertia::render('admin/roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): Response
    {
        $permissions = Permission::all();
        
        return Inertia::render('admin/roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create($request->only('name', 'description'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }

        Flasher::addSuccess('Role created successfully.');

        return redirect()->route('admin.roles.index');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): Response
    {
        $role->load('permissions');
        $permissions = Permission::all();
        
        return Inertia::render('admin/roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
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
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update($request->only('name', 'description'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }

        Flasher::addSuccess('Role updated successfully.');

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        Flasher::addSuccess('Role deleted successfully.');

        return redirect()->route('admin.roles.index');
    }
}
