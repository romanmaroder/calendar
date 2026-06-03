<?php

namespace App\Http\Controllers;

use App\Exceptions\BranchUserException;
use App\Http\Requests\Branch\AvatarBranchRequest;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CompanyResource;
use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Country\Country;
use App\Models\User;
use App\Repositories\Contracts\BranchRepositoryInterface;
use App\Services\BranchUserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BranchController extends Controller
{

    public function __construct(protected BranchUserService $branchUserService, protected BranchRepositoryInterface
    $branchRepository)
    {
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = $this->branchRepository->listWithCountryInfo(20);

        return Inertia::render('branch/Index', [
            'branches' => BranchResource::collection($branches)->resolve(),
            'count' => $branches->total(),
            'company' => $this->getCompany()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('branch/Create', ['user' => $this->getUsers(),'company' => $this->getCompany()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        $validated = $request->validated();

        Branch::create($validated);
        return to_route('branch.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $branch = $this->branchRepository->findWithCountryInfo($branch->id);

        if (!$branch) {
            abort(404);
        }

        $resource = new BranchResource($branch);
        $resolved = $resource->resolve();
        $transformedBranch = $resolved['data'] ?? $resolved;



        if ($branch->trashed()) {
            return Inertia::render('branch/Show', [
                'branch' => $transformedBranch,
                'isDeleted' => true
            ]);
        }

        return Inertia::render('branch/Show', [
            'branch' => $transformedBranch,
            'isDeleted' => false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $branch = $this->branchRepository->findWithCountryInfo($branch->id);

        if (!$branch) {
            abort(404);
        }
        $resource = new BranchResource($branch);
        $resolved = $resource->resolve();
        $transformedBranch = $resolved['data'] ?? $resolved;
        return Inertia::render('branch/Edit', [
            'branch' => $transformedBranch,
            'company' => $this->getCompany()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $data = $request->validated();
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
        $branches = Branch::onlyTrashed()->with('company')->latest('created_at')->paginate(20);
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
        if (!empty($usersIds)) {
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
            if (!empty($usersIds)) {
                $this->branchUserService->unsubscribeUsers($usersIds, $branch->id);
            }
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
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return response()
                ->json(['success' => false, 'message' => 'Произошла ошибка при отписке пользователей'], 500);
        }
    }


    private function getUsers()
    {
        return User::all();
    }

    private function getCompany()
    {
        /*return CompanyResource::collection(Company::with('country:id,phone_regex')->find(['id','name', 'country_id'])
                                               ->all())->resolve();*/
        return Company::with('country:id,name,phone_regex,phone_mask')
            ->get(['id','name', 'country_id']);
    }
}
