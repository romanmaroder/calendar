<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\AvatarCompanyRequest;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company\Company;
use App\Models\Country\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with('country')->paginate(20);
        return Inertia::render(
            'company/Index',
            ['companies' => $companies->collect(),
                'countries'=>$this->getCountries()]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('company/Create',['countries' => $this->getCountries()]);
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
        $company->load('country');
        if ($company->trashed()) {
            return Inertia::render('company/Show', [
                'company' => $company,
                'isDeleted' => true
            ]);
        }
        return Inertia::render('company/Show', ['company' => $company, 'isDeleted' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return Inertia::render('company/Edit', [
            'company' => $company,
            'countries' => $this->getCountries(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();
        //dd($data);
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
        $companies = Company::onlyTrashed()->latest('created_at')->paginate(20);
        return Inertia::render('company/Archive', [
            'companies' => $companies->collect(),
            'count' => $companies->total(),
        ]);
    }

    public function softDelete(string $id)
    {
        $company = Company::findOrFail($id);

        $company->delete();
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
        $company = Company::withTrashed()->findOrFail($id);

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
        $company = Company::onlyTrashed()->findOrFail($id);
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

    private function getCountries():Collection
    {
        return Country::all();
    }
}
