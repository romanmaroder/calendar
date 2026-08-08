<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Services\Admin\Permission\PermissionService;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function __construct(private readonly PermissionService $service)
    {
    }

    public function index()
    {
        $permissions = Permission::query()
            ->orderBy('name')
            ->paginate(20);

        return Inertia::render('admin/Permission/Index', [
            'permissions' => $permissions,
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/Permission/Create');
    }

    public function store(StorePermissionRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Разрешение создано');
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('admin/Permission/Edit', [
            'permission' => $permission->only('id', 'name'),
        ]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->service->update($permission, $request->validated());

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Разрешение обновлено');
    }

    public function show()
    {

    }

    public function destroy(Permission $permission)
    {
        $this->service->delete($permission);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Разрешение удалено');
    }
}
