<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Resources\Company as CompanyResource;
use App\Company;
use App\CompanyType;
use DB;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $companytypes = CompanyType::all();
        // return view('companies.create')->with('companytypes', $companytypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);

        return new CompanyResource($company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->delete()) {
            return new CompanyResource($company);
        }
    }
}
