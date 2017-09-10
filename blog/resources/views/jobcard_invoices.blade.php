@extends('admin_template')
@section('content')
<title>IDSS | Jobcard Invoice</title>
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
          <th>Invoice System Code</th>
          <th>Supplier Name</th>
          <th>Invoice Number</th>
          <th>Invoice Date</th>
          <th>Amount</th>
          <th>Payment Status</th>
          <th>Actions</th>
        </tr>
        @foreach($supplierInvoices as $index => $supplierInvoice)
          <tr class="maintenance-item">
            <td> {{++$index}} </td>
            <td> {{$supplierInvoice->invoiceSystemCode}} </td>
            <td data-supplier-val="{{$supplierInvoice->supplierID}}">
              @if(App\Model\Supplier::find($supplierInvoice->supplierID) && $supplierInvoice->supplierID != 0)
                 {{App\Model\Supplier::find($supplierInvoice->supplierID)->supplierName}}
              @endif
            </td>
            <td class="invoice-code"> {{$supplierInvoice->supplierInvoiceCode}} </td>
            <td class="invoice-date format-date"> {{$supplierInvoice->invoiceDate}} </td>
            <td><?= number_format((float)$supplierInvoice->amount, 3, '.', '') ?></td>
            <td> 
              @if ($supplierInvoice->paymentPaidYN == 0)
                Not Paid
              @elseif ($supplierInvoice->paymentPaidYN == 1)
                Partially Paid
              @else
                Fully Paid
              @endif
            </td>            
            <td class="edit-button"> 
              <div class="inner">
                <a href="#" data-id="{{$supplierInvoice->supplierInvoiceID}}" data-toggle="tooltip" title="Edit" class="btn bg-yellow supplier-edit-invoice btn-sm pull-left" data-toggle="modal" data-target="#supplierModal"><i class="fa fa-pencil" aria-hidden="true"></i> </a>
                <a href="/invoice/{{$supplierInvoice->supplierInvoiceID}}/display" data-toggle="tooltip" title="PDF" class="btn btn-info btn-sm btn-second pull-left"><i class="fa fa-file-text" aria-hidden="true"></i> </a>
              </div>
              </td>            
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
        <th>Invoice System Code</th>
        <th>Customer Name</th>
        <th>Invoice Date</th>
        <th>Amount</th>
        <th>Payment Status</th>
        <th>Actions</th>
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
            <td> <?= number_format((float)$customerInvoice->amount, 3, '.', '') ?></td>
            <td>
              @if ($customerInvoice->paymentReceivedYN == 0)
                Not Received
              @elseif ($customerInvoice->paymentReceivedYN == 1)
                Partially Received
              @else
                Fully Received
              @endif
            </td>            
            <td class="edit-button"> 
              <div class="inner">
                <a href="#" data-id="{{$customerInvoice->customerInvoiceID}}" class="btn bg-yellow customer-edit-invoice btn-sm pull-left" data-toggle="modal" data-target="#clientModal"><i class="fa fa-pencil" aria-hidden="true"></i> </a>
                 <a href="/customer/invoice/{{$customerInvoice->customerInvoiceID}}/display" class="btn btn-info btn-sm btn-second pull-left"><i class="fa fa-file-text" aria-hidden="true"></i> </a>
              </div>
             </td>            
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