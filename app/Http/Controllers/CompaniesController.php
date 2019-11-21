<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Resources\Company as CompanyResource;
use App\Company;
use App\CompanyType;
use App\Exports\CompanyExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class CompaniesController extends Controller
{
    public function index()
    {
        $company = Company::paginate(5);

        return CompanyResource::collection($company);
    }

    public function all()
    {
        $company = Company::all();

        return CompanyResource::collection($company);
    }

    public function export()
    {
        return Excel::download(new CompanyExport, 'companies.xlsx');
    }

    public function fileUpload(Request $request) {
        $file =  $request->file('file')->getRealPath();
        $data = array_map('str_getcsv', file($file));
        return $data;
    }

    function fileSave(Request $request) {
        $companies = $request->input('companies');
        if (Company::insert($companies))
            return "success";
        return "error";
    } 
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            //'email' => 'email',
        ]);

        $company = new Company;

        $company->name = $request->input('name');
        $company->address = $request->input('address');
        $company->email = $request->input('email');
        $company->contact_no = $request->input('contact_no');
        $company->companytype_id = $request->input('companytypes');
        
        if ($company->save()) {
            return new CompanyResource($company);
        }
    }
    public function show($id)
    {
        $company = Company::findOrFail($id);

        return new CompanyResource($company);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            //'email' => 'email',
        ]);

        $company = Company::findOrFail($id);

        $company->name = $request->input('name');
        $company->address = $request->input('address');
        $company->email = $request->input('email');
        $company->contact_no = $request->input('contact_no');
        $company->companytype_id = $request->input('companytypes');
        
        if ($company->save()) {
            return new CompanyResource($company);
        }
    }
    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->delete()) {
            return new CompanyResource($company);
        }
    }
}
