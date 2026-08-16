<?php

namespace App\Http\Controllers\User;

use App\Actions\PhoneMetaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AvatarUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Branch\BranchMinWithCountryMinResource;
use App\Http\Resources\User\UserResource;
use App\Models\Branch\Branch;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\HasControllerRoutes;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasControllerRoutes;

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function index()
    {
        $users = $this->userRepository->listWithBranchInfo();
        return Inertia::render(
            'user/Index',
            [
                'users' => UserResource::collection($users)->resolve(),
                'count' => $this->userRepository->countAll(),
                'branch' => $this->getBranches(),
                'roles' => Role::all(['id', 'name'])->toArray(),
            ]
        );
    }

    public function create()
    {

        return Inertia::render('user/Create', ['branch' => $this->getBranches(),
            'roles' => Role::all(['id', 'name'])->toArray()]);
    }

    public function store(StoreUserRequest $request)
    {
        if (! $request->user()->can('users.create')) {
            abort(403, 'Недостаточно прав для создания пользователя');
        }

        $data = $request->validated();
        // Гарантируем наличие ключа 'password' (даже если он пустой)
        // Если поле не установлено в форме, мутатор не сработает
       //$data['password'] = $data['password'] ?? '';
        unset($data['role_ids']);
        $data['password'] = \Hash::make('password');
        $data['requires_password_change']= true;
        $user = User::create($data);

        if ($request->has('role_ids')) {
            $user->assignRole($request->role_ids); // массив ID ролей
        }


        return to_route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = $this->userRepository->findWithTrashedAndBranchInfo($user->id);

        if ($user->trashed()) {
            return Inertia::render('user/Show', [
                'user' => (new UserResource($user))->resolve(),
                'isDeleted' => true
            ]);
        }
        // Обычная модель (не удалена)
        return Inertia::render('user/Show', [
            'user' => (new UserResource($user))->resolve(),
            'isDeleted' => false
        ]);
    }

    public function edit(User $user)
    {
        $user = $this->userRepository->find($user->id);

        return Inertia::render('user/Edit', [
            'user' => (new UserResource($user))->resolve(),
            'branches' => $this->getBranches(),
            'roles' => Role::all(['id', 'name'])->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (! $request->user()->can('users.edit')) {
            abort(403, 'Недостаточно прав для создания пользователя');
        }

        $data = $request->validated();
        $roleIds = $validated['role_ids'] ?? [];
        unset($data['role_ids']);

        // Обновляем только если пароль передан
        if (!empty($validated['password'])) {
            $user->update([
                              'password'                 => Hash::make($validated['password']),
                              'requires_password_change' => true, // если админ сам задал пароль — требование снимается
                          ]);
        } else {
            $user->update($data);
        }



        if ($request->has('role_ids')) {
            $user->syncRoles($request->role_ids); // синхронизация: старые роли уберутся, новые добавятся
        }


        return to_route('users.index');
    }

    public function avatar(AvatarUserRequest $request, User $user)
    {
        $request->validated();
        if (isset($user->avatar)) {
            $user->update(['avatar' => null]);
        }
    }

    public function archive()
    {
        $users = $this->userRepository->listOnlyTrashed();

        return Inertia::render('user/Archive', [
            'users' => UserResource::collection($users)->resolve(),
            'count' => $this->userRepository->countAll(),
            'branch' => $this->getBranches(),
        ]);
    }

    public function softDelete(string $id)
    {
        $resource = $this->userRepository->find($id);
        $resource->delete();
        return response()->json(['success' => true, 'message' => 'User has been deleted', 'code' => 200]);
    }

    public function bulkSoftDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        User::whereIn('id', $ids)->delete();
        return response()->json(
            [
                'success' => true,
                'count' => count($ids),
                'message' => 'Move to the basket.',
                'code' => 200
            ]
        );
    }

    public function forceDelete(string $id)
    {
        $user = $this->userRepository->findWithTrashedAndBranchInfo($id);
        $extension = explode('/', $user->avatar);
        $avatar = end($extension);
        Storage::disk('public')->delete('/avatars/' . $avatar);
        $user->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $user->id . ' ' . $user->surname . ' deleted',
                                    'code' => 200
                                ]);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $users = User::withTrashed()->whereIn('id', $ids)->get();

        foreach ($users as $user) {
            $extension = explode('/', $user->avatar);
            $avatar = end($extension);
            Storage::disk('public')->delete('/avatars/' . $avatar);
        }


        User::withTrashed()->whereIn('id', $ids)->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'Users have been deleted',
                                    'count' => count($ids)
                                ]);
    }

    public function restore($id)
    {
        $user = $this->userRepository->onlyTrashed($id);
        $user->restore();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $user->id . ' ' . $user->surname . ' restored.',
                                    'code' => 200
                                ]);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        User::onlyTrashed()->whereIn('id', $ids)->restore();
        return response()->json([
                                    'success' => true,
                                    'code' => 200,
                                    'message' => 'Users restored'
                                ]);
    }

    public function formMeta(): JsonResponse
    {
        try {
            $branchId = request()->input('branch_id');
            $meta = PhoneMetaAction::getByBranchId($branchId);

            return response()->json(['success' => true, 'data' => $meta]);
        } catch (Exception $e) {
            return response()->json([
                                        'success' => false,
                                        'message' => $e->getMessage(),
                                    ], 400);
        }
    }

    /*public function assignRoles(Request $request, User $user)
    {
        $request->validate(['role_ids' => 'array|integer']);
        $user->syncRoles($request->role_ids ?? []);
        return response()->json(['success' => true]);
    }*/


    private function getBranches()
    {
        return BranchMinWithCountryMinResource::collection(
            Branch::query()
                ->with(['company.country'])
                ->where('status', 1)
                ->whereHas('company.country', function ($query) {
                    $query->where('is_primary', 1);
                })
                ->get(['id', 'name', 'company_id'])
        )->resolve();
    }

}
