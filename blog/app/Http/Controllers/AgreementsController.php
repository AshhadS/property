<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Unit;
use App\Model\Property;
use App\Model\Tenant;
use App\Model\Attachment;
use App\Model\DocumentMaster;
use App\Model\Agreement;
use App\Model\PaymentType;
use Datatables;
use Illuminate\Support\Facades\DB;
use Debugbar;
use Redirect;
use Sentinel;
use DateTime;

class AgreementsController extends Controller
{
    function index() {
    	$units = Unit::all();
    	$properties = Property::all();
    	$tenants = Tenant::all();
        $paymentypes = PaymentType::all();
    	
    	// Debugbar::info($tenants);
	    return view('agreement', [
	        'units' => $units,
	        'properties' => $properties,
	        'tenants' => $tenants,
	        'paymentypes' => $paymentypes,
	    ]);
    }
    
    function data(){
    	$t = DB::table('agreement')
    		->leftJoin('tenants', 'agreement.tenantID', '=', 'tenants.tenantsID')
    		->leftJoin('units', 'agreement.unitID', '=', 'units.unitID')
            ->leftJoin('properties', 'agreement.PropertiesID', '=', 'properties.PropertiesID')
    		->leftJoin('paymenttype', 'agreement.paymentTypeID', '=', 'paymenttype.paymentTypeID')
    		->select('agreement.agreementID', 'properties.pPropertyName', 'units.unitNumber' , 'tenants.firstName', 'agreement.dateFrom','agreement.dateTo','agreement.marketRent','agreement.actualRent', 'paymenttype.paymentDescription');
    	return Datatables::of($t)->make(true);

    }

    function create(Request $request) {
	    $agreement = new Agreement;
	    ($request->PropertiesID != 0) ? $agreement->PropertiesID = $request->PropertiesID : false; // do not save if 0 was selected
	    $agreement->rentalOwnerID = Property::where('PropertiesID', $request->PropertiesID)->first()->rentalOwnerID;
	    $agreement->tenantID = $request->tenantsID;
        $agreement->unitID = $request->unitID;
        $agreement->actualRent = $request->actualRent;
        $agreement->marketRent = $request->marketRent;
        $agreement->paymentTypeID  = $request->paymentTypeID;
        $agreement->companyID = Sentinel::getUser()->companyID;
        $agreement->isPDCYN  = (isset($request->pdcyn)) ? $request->pdcyn : '0';

        if($request->dateFrom)
            $agreement->dateFrom = date_create_from_format("j/m/Y", $request->dateFrom)->format('Y-m-d');
        
        if($request->dateTo)
            $agreement->dateTo = date_create_from_format("j/m/Y", $request->dateTo)->format('Y-m-d');
	
	    $agreement->save();

	    return Redirect::to('agreements');
    }

    function getFields($agreementid){
     //   $agreement = Agreement::where('agreementID', $agreementid)->get();
        $agreement = Agreement::where('agreementID', $agreementid)->firstOrFail();
        $propertylist = Property::pluck('pPropertyName', 'PropertiesID');
        $tenantlist = Tenant::pluck('firstName', 'tenantsID');
        $unitlist = Unit::pluck('unitNumber', 'unitID');
        $paymenttypelist = PaymentType::pluck('paymentDescription', 'paymentTypeID');
        $startDate=date_create_from_format("Y-m-d", $agreement->dateFrom)->format('j/m/Y');
        $endDate=date_create_from_format("Y-m-d", $agreement->dateTo)->format('j/m/Y');
        return view('agreements_edit', [
            'agreement' => $agreement,
            'propertylist' => $propertylist,
            'tenantlist' => $tenantlist,
            'unitlist' => $unitlist,
            'paymenttypelist' => $paymenttypelist,
            'startDate' => $startDate,
            'endDate' => $endDate,
           ]);
    }

  
    function update($id,Request $request){

    	//$agreement = Agreement::find($request->agreementID);
        $agreement= Agreement::where('agreementID', $id)->firstOrFail();
        ($request->PropertiesID != 0) ? $agreement->PropertiesID = $request->PropertiesID : false; // do not save if 0 was selected
        $agreement->rentalOwnerID = Property::where('PropertiesID', $request->PropertiesID)->first()->rentalOwnerID;
        $agreement->tenantID = $request->tenantsID;
        $agreement->unitID = $request->unitID;
        $agreement->actualRent = $request->actualRent;
        $agreement->marketRent = $request->marketRent;
        $agreement->paymentTypeID  = $request->paymentTypeID;
        $agreement->isPDCYN  = (isset($request->pdcyn)) ? $request->pdcyn : '0';

        if($request->dateFrom)
            $agreement->dateFrom = date_create_from_format("j/m/Y", $request->dateFrom)->format('Y-m-d');
        
        if($request->dateTo)
            $agreement->dateTo = date_create_from_format("j/m/Y", $request->dateTo)->format('Y-m-d');

        //$agreement->fill($request->all())->save();
        $agreement->save();

        return Redirect::to('agreements');
    	
    }

    

    function delete($agreement){
        $agreement = Agreement::find($agreement);
	    $agreement->delete();
	    return Redirect::to('agreement');
    }
}

