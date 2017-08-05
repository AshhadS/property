@extends('admin_template')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-12">
            <a href="/jobcard/edit/{{$jobcard->jobcardID}}" class="btn btn-default"><i class="fa fa-angle-left" aria-hidden="true"></i> Back To Jobcard</a>
          <h2> 
            <i class="fa fa-briefcase" aria-hidden="true"></i> Jobcard 
          @if($jobcard->jobCardCode)
          -   {{$jobcard->jobCardCode}}
          @endif
          </h2>

        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h2 class='conrol-label'>{{ $jobcard->subject}}</h2>
        </div>
      </div>
    </div>
  </div>
      <div class="row">
        <div class="col-md-4">
          <h4><b>SUPPLIER</b></h4>
        </div>
      </div>

  
  <table class="m-item  table table-striped">
      <thead>
      </thead>
      <tbody>
        <tr class="t-head">
          <th class="amount-col">#</th>
          <th>System Code</th>
          <th>Invoice Code</th>
          <th>Supplier</th>
          <th>Amount</th>
          <th>Invoice Date</th>
          <th>Payment Made</th>
          <th>Edit</th>
          <th>Document</th>
        </tr>
        @foreach($supplierInvoices as $index => $supplierInvoice)
          <tr class="maintenance-item">
            <td> {{++$index}} </td>
            <td> {{$supplierInvoice->invoiceSystemCode}} </td>
            <td class="invoice-code"> {{$supplierInvoice->supplierInvoiceCode}} </td>
            <td data-supplier-val="{{$supplierInvoice->supplierID}}">
              @if(App\Model\Supplier::find($supplierInvoice->supplierID) && $supplierInvoice->supplierID != 0)
                 {{App\Model\Supplier::find($supplierInvoice->supplierID)->supplierName}}
              @endif
            </td>
            <td> {{$supplierInvoice->amount}} </td>
            <td class="invoice-date format-date"> {{$supplierInvoice->invoiceDate}} </td>
            <td> {{($supplierInvoice->paymentPaidYN) ? "Yes" : "No"}} </td>            
            <td> <a href="#" data-id="{{$supplierInvoice->supplierInvoiceID}}" class="btn bg-yellow supplier-edit-invoice" data-toggle="modal" data-target="#supplierModal"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>  
            <td> <a href="/invoice/{{$supplierInvoice->supplierInvoiceID}}/display" class="btn btn-info"><i class="fa fa-file-text" aria-hidden="true"></i> PDF</a></td>            
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="row">
      <div class="col-md-4">
        <h4><b>CLIENT</b></h4>
      </div>
    </div>
    <table class="m-item  table table-striped"  >
      <tr class="t-head">
        <th class="amount-col">#</th>
        <th>System Code</th>
        <th>Property Owner</th>
        <th>Invoice Date</th>
        <th>Payment Recieved</th>
        <th>Amount</th>
        <th>Edit</th>
        <th>Document</th>
      </tr>
      @foreach($customerInvoices as $index => $customerInvoice)
          <tr class="maintenance-item">
            <td> {{++$index}} </td>
            <td> {{$customerInvoice->CustomerInvoiceSystemCode}} </td>
            <td data-supplier-val="{{$customerInvoice->supplierID}}">
              @if(App\Model\RentalOwner::find($customerInvoice->propertyOwnerID) && $customerInvoice->propertyOwnerID != 0)
                 {{App\Model\RentalOwner::find($customerInvoice->propertyOwnerID)->firstName}} {{App\Model\RentalOwner::find($customerInvoice->propertyOwnerID)->lastName}}
              @endif
            </td>
            <td class="invoice-date format-date"> {{$customerInvoice->invoiceDate}} </td>
            <td> {{($customerInvoice->paymentReceivedYN) ? "Yes" : "No"}} </td>            
            <td> {{$customerInvoice->amount}} </td>
            <td> <a href="#" data-id="{{$customerInvoice->customerInvoiceID}}" class="btn bg-yellow customer-edit-invoice" data-toggle="modal" data-target="#clientModal"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
            <td> <a href="/customer/invoice/{{$customerInvoice->customerInvoiceID}}/display" class="btn btn-info"><i class="fa fa-file-text" aria-hidden="true"></i> PDF</a></td>            
          </tr>
        @endforeach
    </table>
</div>

<div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="box box-info">
        <div class="box-body">
          <form method="POST" action="/update/customer-invoice">            
            <input type="hidden" name="customerInvoiceID" >
            {{ csrf_field() }}
            <div class="form-group clearfix">
              <label class="col-sm-2 control-label">Invoice Date</label>
              <div class="col-sm-10">
                <input type="text" name="invoiceDate" class="form-control datepicker" placeholder="Invoice Date">
              </div>
            </div>
            <div class="box-footer">
              <div class="form-buttons">
                <input type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close" value="Cancel" />
                <button type="submit" class="btn btn-info pull-right">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="box box-info">
        <div class="box-body">
          <form method="POST" action="/update/supplier-invoice">            
            <input type="hidden" name="supplierInvoiceID" >
            {{ csrf_field() }}
            <div class="form-group clearfix">
              <label class="col-sm-2 control-label">Invoice Date</label>
              <div class="col-sm-10">
                <input type="text" name="invoiceDate" class="form-control datepicker" placeholder="Invoice Date">
              </div>
            </div>
            <div class="form-group clearfix">
              <label class="col-sm-2 control-label">Invoice Code</label>
              <div class="col-sm-10">
                <input type="text" name="supplierInvoiceCode" class="form-control" placeholder="Code Enterable by the system">
              </div>
            </div>
            <div class="box-footer">
              <div class="form-buttons">
                <input type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close" value="Cancel" />
                <button type="submit" class="btn btn-info pull-right">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
  <script>
    $(function() {
      $('.supplier-edit-invoice').on('click', function(){
        $('[name="invoiceDate"]').val($(this).closest('tr').find('.invoice-date').text());
        $('[name="supplierInvoiceCode"]').val($(this).closest('tr').find('.invoice-code').text());
        $('[name="supplierInvoiceID"]').val($(this).data('id'));

        // $('[name=" paymentPaidYN"]').val($(this).closest('tr').find('.paid'));

      });
      $('.customer-edit-invoice').on('click', function(){
        $('[name="invoiceDate"]').val($(this).closest('tr').find('.invoice-date').text());
        $('[name="customerInvoiceID"]').val($(this).data('id'));
      });
    });

  </script>
@endpush