<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalDetails;
use App\Models\CompanyInfo;
use App\Models\GeneralIndustry;
use App\Models\SubIndustryModel;
// use Illuminate\Database\Query\Builder;

class RegisterController extends Controller
{
    
    public function fetchGeneralIndustry() {
        $items = GeneralIndustry::all();
        return response()->json($items);
    }

    public function fetchSubIndustry($id) {
        // $items = SubIndustryModel::findOrFail($id);
        $items = \DB::table('sub_industries')
                ->where('general_industry_id', '=', $id)
                ->get();
       
        return response()->json($items);
    }

    public function save(Request $request) {

        //save with respect to table relationship
        // 1. Company Info
        // 2. Personal Details

        $general_industry_id = $request['industry_details']['generalIndustry'];
        $sub_industry_id = 321; //to be updated
        $workers_size = $request['industry_details']['workerSize'];

        //1. Save Company Info
        $companyInfo = new CompanyInfo();

        $companyInfo->name = $request['company_details']['companyName'];
        $companyInfo->location = $request['company_details']['companyLocation'];
        $companyInfo->last_fiscal_year_end_date = $request['company_details']['lastFiscalYearEndDate'];
        $companyInfo->workers_size = $workers_size;
        $companyInfo->general_industry_id = $general_industry_id;
        $companyInfo->sub_industry_id = $sub_industry_id;

        $companyInfo->save();

        $company_id = $companyInfo->company_info_id;

        //2. Save Personal Info
        $personalDetails = new PersonalDetails();

        $personalDetails->first_name = $request['appointment_details']['firstName'];
        $personalDetails->last_name = $request['appointment_details']['lastName'];
        $personalDetails->email_address = $request['appointment_details']['email'];
        $personalDetails->company_info_id = $company_id;
        
        if($personalDetails->save()){
            return true;
        }
        

       return response()->json(['success' => $request['appointment_details']]);
    }
}
