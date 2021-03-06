<section class="">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Customer Statement
            <small class="pull-right">Date: {{ date("Y/m/d")}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
      <div class="form-group col-xs-3">
            <label for="">Filter By Customer</label>

            <select class="form-control input-sm" id="customer-state" name="state" >
              <option value="0">Please select a customer</option>
             @foreach ($customers as $customer)
                  <option value="{{$customer->customerID}}">{{$customer->customerName}}</option>
            @endforeach
                  
            </select>

      </div>

      <a href="/supplierstatement-excel/{{ $param = 'CST' }}">
            <button type="button" class="btn btn-success pull-right">
              <i class="fa fa-file-excel-o"></i> Export to Excel
            </button>
          </a>
          <a href="/customerStatement_pdf">
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
              <i class="fa fa-download"></i> Generate PDF 
            </button>
          </a>
      	
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table id="domains_table" class="table table-striped">
            <thead>
            <tr>
              <th>Customer Name</th>
              <th>Invoice Code</th>
              <th>Invoice Date</th>
              <th>Invoice No</th>
              <th>Currency</th>
              <th>Invoice Amount</th>
              <th>Balance Amount</th>
            </tr>
            </thead>
            <tbody>
            
            @include('reports_customerstatement_data')
            
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
         
          
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="/customerstatement-print" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          
        </div>
      </div>
    </section>