<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\JobCard;
use App\Model\Supplier;
use App\Model\Maintenance;
use App\Model\Company;
use App\Model\Property;
use App\Model\SupplierInvoice;
use App\Model\JobcardPayment;
use App\Model\PaymentType;
use Redirect;
use Sentinel;
use App;

class JobcardPaymentController extends Controller
{
	function createPayment(Request $request){
		$payment = new JobcardPayment();
		$invoice = SupplierInvoice::find($request->invoiceID);
		$payment->supplierID = $request->supplierID;
		$payment->supplierInvoiceID = $request->invoiceID;
		$payment->invoiceSystemCode = $invoice->invoiceSystemCode;
		$payment->jobcardID = $invoice->jobcardID;
		$payment->supplierInvoiceCode = $invoice->supplierInvoiceCode;
		$payment->SupplierInvoiceDate = date("Y-m-d", strtotime($invoice->invoiceDate));
		$payment->supplierInvoiceAmount = $invoice->amount;
		$payment->paymentAmount = $request->paymentAmount;
		$payment->paymentTypeID = $request->paymentTypeID;
		$payment->lastUpdatedByUserID = Sentinel::getUser()->id;
		$payment->save();

		return Redirect::to('jobcard/edit/'.$request->jobcardID.'/payment');
	}

	function index($jobcard){
		$jobcard = JobCard::find($jobcard);
		$suppliers = Supplier::all();
		$payments = JobcardPayment::all();
		$paymentTypes = PaymentType::all();
		return view('jobcard_payment', [
            'jobcard' => $jobcard,
            'suppliers' => $suppliers,
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
            
	    ]);
	}

	function getInvoiceItems($supplier){
		$invoice = SupplierInvoice::where('supplierID', $supplier)->get();
		return $invoice;
	}

	function getInvoiceAmount($invoice){
		// Check if payment has been made before
		$invoiceAmount = SupplierInvoice::find($invoice)->amount;
		$paidAmount = 0;
		if(JobcardPayment::where('supplierInvoiceID', $invoice)){
			$paidAmount = JobcardPayment::where('supplierInvoiceID', $invoice)->sum('paymentAmount');
		}
		$finalAmount = $invoiceAmount - $paidAmount;
		return $finalAmount;		
	}

	function generatePDF($id){
		$pdf = App::make('dompdf.wrapper');
		$payment = JobcardPayment::find($id);
		$jobcard = JobCard::find($payment->jobCardID);
    	$company = Company::find(Sentinel::getUser()->companyID);
		$data = array(
			'jobcard' => $jobcard,
            'company' => $company,
            'payment' => $payment,
		);

		$pdf->loadView('pdf/jobcard_payment_pdf', $data , $data);
		return $pdf->stream();
	}


	function delete($paymentID){
		$payment = JobcardPayment::find($paymentID);
		$jobcardID = $payment->jobCardID;

		$payment->delete();
		return Redirect::to('jobcard/edit/'.$jobcardID.'/payment');
	}
}