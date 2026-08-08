<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Services\Admin\Role\RoleService;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct(
        private readonly RoleService $roleService,
    ) {
    }

    public function index()
    {
        $roles = Role::with('permissions')->paginate(20);
        $permissions = Permission::all();

        return Inertia::render('admin/Role/Index', [
            'roles' => $roles->items(),
            'pagination' => [
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'total' => $roles->total(),
            ],
            'permissions' => $permissions->toArray(),
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();
        return Inertia::render('admin/Role/Create', [
            'permissions' => $permissions->toArray(),
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleService->create($request->validated());

        return redirect()->route('admin.roles.index')->with('success', 'Роль создана');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();


        return Inertia::render('admin/Role/Edit', [
            'role' => $role->toArray(),
            'assignedPermissions' => $assignedPermissions,
            'permissions' => $permissions->toArray(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->update($role, $request->validated());

        return redirect()->route('admin.roles.index')->with('success', 'Роль обновлена');
    }

    public function show()
    {
        
    }
    
    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Роль удалена');
    }
}
