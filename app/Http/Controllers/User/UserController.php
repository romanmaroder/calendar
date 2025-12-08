<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Upload\AvatarUploadController;
use App\Http\Requests\Client\AvatarUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Branch\Branch;
use App\Models\User;
use App\Traits\HasControllerRoutes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    use HasControllerRoutes;

    public function index()
    {
        $count = User::count();
        $branch = Branch::all(['id', 'name']); //TODO добавление филиалов
        $routes = $this->routeMerge(
            $this->getRoutesByController(AvatarUploadController::class),
            $this->getControllerRoutes()
        );
        return Inertia::render(
            'user/Index',
            [
                'users' => User::with('branch')->paginate($count)->collect(),
                'count' => $count,
                'branch' => $branch,
                'routes' => $routes
            ]
        );
    }

    public function create()
    {
        $branch = Branch::all(['id', 'name']); //TODO добавление филиалов
        $routes = $this->routeMerge(
            $this->getRoutesByController(AvatarUploadController::class),
            $this->getControllerRoutes()
        );
        return Inertia::render('user/Create', ['branch' => $branch, 'routing' => $routes]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        User::create($data);
        return to_route('users');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('branch');
        if ($user->trashed()) {
            return Inertia::render('user/Show', [
                'user' => $user,
                'isDeleted' => true
            ]);
        }

        // Обычная модель (не удалена)
        return Inertia::render('user/Show', [
            'user' => $user,
            'isDeleted' => false
        ]);
    }

    public function edit(User $user)
    {
        $branch = Branch::all(['id', 'name']); //TODO добавление филиалов
        return Inertia::render('user/Edit', [
            'user' => $user,
            'branch' => $branch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        $data = $request->validated();
        //dd($data);
        $user->update($data);

        return to_route('users');
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
        $count = User::onlyTrashed()->count();
        $routes = $this->routeMerge(
            $this->getRoutesByController(AvatarUploadController::class),
            $this->getControllerRoutes()
        );
        return Inertia::render('user/Archive', [
            'users' => User::onlyTrashed()->with('branch')->latest('created_at')->paginate($count)->collect(),
            'count' => $count,
            'routes' => $routes
        ]);
    }

    public function softDelete(string $id)
    {
        $resource = User::findOrFail($id);
        $resource->delete();
        return response()->json(['success' => true, 'message' => 'User has been deleted', 'code' => 200]);
    }

    public function bulkSoftDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        User::whereIn('id', $ids)->delete();
        return response()->json(
            ['success' => true, 'count' => count($ids), 'message' => 'Move to the basket.', 'code' => 200]
        );
    }

    public function forceDelete(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
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
        $user = User::onlyTrashed()->findOrFail($id);
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

}
