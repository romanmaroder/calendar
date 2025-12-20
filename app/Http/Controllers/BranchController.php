<?php

namespace App\Http\Controllers;

use App\Exceptions\BranchUserException;
use App\Http\Requests\Branch\AvatarBranchRequest;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch\Branch;
use App\Models\User;
use App\Services\BranchUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BranchController extends Controller
{

    protected BranchUserService $branchUserService;

    public function __construct(BranchUserService $branchUserService)
    {
        $this->branchUserService = $branchUserService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::with('users')->paginate(20);
        return Inertia::render('branch/Index', [
            'branches' => $branches->collect(),
            'count' => $branches->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('branch/Create', ['user' => $this->getUsers()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        $validated = $request->validated();

        Branch::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $branch->load('users');

        if ($branch->trashed()) {
            return Inertia::render('branch/Show', [
                'branch' => $branch,
                'isDeleted' => true
            ]);
        }
        return Inertia::render('branch/Show', ['branch' => $branch, 'isDeleted' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return Inertia::render('branch/Edit', [
            'branch' => $branch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $data = $request->validated();
        //dd($data);
        $branch->update($data);

        return to_route('branch.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    /* public function destroy(Branch $branch)
     {
         $branch->delete();
         return response()->json(['success' => true, 'message' => 'Branch Deleted Successfully', 'code' => 200]);
     }*/


    public function avatar(AvatarBranchRequest $request, Branch $branch)
    {
        $request->validated();
        if (isset($branch->avatar)) {
            $branch->update(['avatar' => null]);
        }
    }


    public function archive()
    {
        $branches = Branch::onlyTrashed()->latest('created_at')->paginate(20);
        return Inertia::render('branch/Archive', [
            'branches' => $branches->collect(),
            'count' => $branches->total(),
        ]);
    }

    /**
     * @throws BranchUserException
     */
    public function softDelete(string $id)
    {
        $branch = Branch::findOrFail($id);
        $usersIds = $branch->users->pluck('id')->toArray();
        if ($usersIds){
            $this->branchUserService->unsubscribeUsers($usersIds, $branch->id);
        }
        $branch->delete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'Branch has been deleted',
                                    'code' => 200
                                ]);
    }

    /**
     * @throws BranchUserException
     */
    public function bulkSoftDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $branches = Branch::whereIn('id', $ids)->with('users')->get();

        foreach ($branches as $branch) {

            $usersIds = $branch->users->pluck('id')->toArray();
            $this->branchUserService->unsubscribeUsers($usersIds, $branch->id);
        }

        Branch::whereIn('id', $ids)->delete();

        return response()->json(
            [
                'success' => true,
                'count' => count($ids),
                'message' => 'Move to the basket.',
                'code' => 200
            ]
        );
    }

    /**
     * @throws BranchUserException
     */
    public function forceDelete(string $id)
    {
        $branch = Branch::withTrashed()->findOrFail($id);

        $extension = explode('/', $branch->avatar);
        $avatar = end($extension);
        Storage::disk('public')->delete('/avatars/' . $avatar);
        $branch->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $branch->id . ' ' . $branch->name . ' deleted',
                                    'code' => 200
                                ]);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $branches = Branch::withTrashed()->whereIn('id', $ids)->get();

        foreach ($branches as $branch) {
            $extension = explode('/', $branch->avatar);
            $avatar = end($extension);
            Storage::disk('public')->delete('/avatars/' . $avatar);
        }


        Branch::withTrashed()->whereIn('id', $ids)->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'Branches have been deleted',
                                    'count' => count($ids)
                                ]);
    }

    public function restore($id)
    {
        $branch = Branch::onlyTrashed()->findOrFail($id);
        $branch->restore();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $branch->id . ' ' . $branch->name . ' restored.',
                                    'code' => 200
                                ]);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        Branch::onlyTrashed()->whereIn('id', $ids)->restore();
        return response()->json([
                                    'success' => true,
                                    'code' => 200,
                                    'message' => 'Branches restored'
                                ]);
    }


    public function unsubscribeUsers(Request $request, Branch $branch)
    {
        try {
            $userIds = $request->input('ids', []);
            $count = $this->branchUserService->unsubscribeUsers($userIds, $branch->id);

            return response()->json([
                                        'success' => true,
                                        'message' => $count > 1
                                            ? 'Пользователи успешно отписаны от филиала'
                                            : 'Пользователь успешно отписан от филиала',
                                        'count' => $count
                                    ]);

        } catch (BranchUserException $e) {
            return response()
                ->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return response()
                ->json(['success' => false, 'message' => 'Произошла ошибка при отписке пользователей'], 500);
        }
    }


    private function getUsers()
    {
        return User::all();
    }
}
