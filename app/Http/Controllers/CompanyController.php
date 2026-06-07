<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\AvatarCompanyRequest;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Country\CountryResource;
use App\Models\Company\Company;
use App\Models\Country\Country;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{

    public function __construct(protected CompanyRepositoryInterface $companyRepository)
    {
    }

    /**
     * Display a listing of the resource.
     * countries нужны для передачи в компоненты  (мобильная версия)
     */
    public function index()
    {
        $companies = $this->companyRepository->listWithCountryAndBranchesInfo();
        return Inertia::render(
            'company/Index',
            [
                'companies' => CompanyResource::collection($companies)->resolve(),
                'countries' => $this->getCountries()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('company/Create', ['countries' => $this->getCountries()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $validated = $request->validated();

        Company::create($validated);
        return to_route('company.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company = $this->companyRepository->findWithBranchesInfo($company->id);

        if ($company->trashed()) {
            return Inertia::render('company/Show', [
                'company' => (new CompanyResource($company))->resolve(),
                'isDeleted' => true
            ]);
        }
        return Inertia::render(
            'company/Show',
            ['company' => (new CompanyResource($company))->resolve(), 'isDeleted' => false]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return Inertia::render('company/Edit', [
            'company' => (new CompanyResource($company))->resolve(),
            'countries' => $this->getCountries(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();
        $company->update($data);

        return to_route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    /*public function destroy(Company $company)
    {
      $company->delete();
        return response()->json(['success' => true, 'message' => 'Company Deleted Successfully', 'code' => 200]);
    }*/

    public function avatar(AvatarCompanyRequest $request, Company $company)
    {
        $request->validated();
        if (isset($company->avatar)) {
            $company->update(['avatar' => null]);
        }
    }

    public function archive()
    {
        $companies = $this->companyRepository->listOnlyTrashed();
        return Inertia::render('company/Archive', [
            'companies' => CompanyResource::collection($companies)->resolve(),
            'count' => $companies->total(),
        ]);
    }

    public function softDelete(string $id)
    {
        $this->companyRepository->findOrFail($id)->delete();

        return response()->json([
                                    'success' => true,
                                    'message' => 'Company has been deleted',
                                    'code' => 200
                                ]);
    }

    public function bulkSoftDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        Company::whereIn('id', $ids)->delete();

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
        $company = $this->companyRepository->withTrashed($id);

        $extension = explode('/', $company->avatar);
        $avatar = end($extension);
        Storage::disk('public')->delete('/avatars/' . $avatar);
        $company->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $company->id . ' ' . $company->name . ' deleted',
                                    'code' => 200
                                ]);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $companies = Company::withTrashed()->whereIn('id', $ids)->get();

        foreach ($companies as $company) {
            $extension = explode('/', $company->avatar);
            $avatar = end($extension);
            Storage::disk('public')->delete('/avatars/' . $avatar);
        }


        Company::withTrashed()->whereIn('id', $ids)->forceDelete();
        return response()->json([
                                    'success' => true,
                                    'message' => 'Companies have been deleted',
                                    'count' => count($ids)
                                ]);
    }

    public function restore($id)
    {
        $company = $this->companyRepository->onlyTrashed($id);
        $company->restore();
        return response()->json([
                                    'success' => true,
                                    'message' => 'ID:' . $company->id . ' ' . $company->name . ' restored.',
                                    'code' => 200
                                ]);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        Company::onlyTrashed()->whereIn('id', $ids)->restore();
        return response()->json([
                                    'success' => true,
                                    'code' => 200,
                                    'message' => 'Companies restored'
                                ]);
    }

    private function getCountries()
    {
        return CountryResource::collection(Country::all(['id', 'name', 'phone_regex', 'phone_mask']))->resolve();
    }
}
