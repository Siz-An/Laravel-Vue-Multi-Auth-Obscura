<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use Flasher\Laravel\Facade\Flasher;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(): Response
    {
        $permissions = Permission::all();
        
        return Inertia::render('admin/permissions/Index', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): Response
    {
        return Inertia::render('admin/permissions/Create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
        ]);

        Permission::create($request->only('name', 'description'));

        Flasher::addSuccess('Permission created successfully.');

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): Response
    {
        return Inertia::render('admin/permissions/Edit', [
            'permission' => $permission,
        ]);
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($request->only('name', 'description'));

        Flasher::addSuccess('Permission updated successfully.');

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        Flasher::addSuccess('Permission deleted successfully.');

        return redirect()->route('admin.permissions.index');
    }
}
