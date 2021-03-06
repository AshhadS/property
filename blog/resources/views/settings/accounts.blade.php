
<h3 class="title">Accounts</h3>
<div class="col-md-6">
  <table class="table table-bordered">
    <tbody>
      <tr>
        <th style="width: 10px">id</th>
        <th>Account Number</th>
        <th>Bank Name</th>
        <th>Chart of Account</th>
        <th>Actions</th>
      </tr>
      @foreach ($bankAccounts as $account)
      <tr>

        <td  >{{$account->bankAccountID}}</td>
        <td class='account item-editable' data-type="text" data-name="accountNumber" data-pk="{{$account->bankAccountID}}" >{{$account->accountNumber}}</td>
        <td class='account item-editable' data-bank="{{$account->bankmasterID}}" data-type="text" data-name="bankmasterID" data-pk="{{$account->bankAccountID}}" >
          @if(App\Model\Bank::where('bankmasterID', $account->bankmasterID)->first())
            {{App\Model\Bank::find($account->bankmasterID)->bankName}}
          @endif
        </td>
        <td class='item-editable accounts-chart' data-coa="{{$account->chartOfAccountID}}" data-type="select" data-name="chartOfAccountID" data-pk="{{$account->bankAccountID}}" >
          @if(App\Model\ChartOfAccount::where('chartOfAccountID', $account->chartOfAccountID)->first())
            {{App\Model\ChartOfAccount::find($account->chartOfAccountID)->accountDescription}}
          @endif
        </td>
        <td>
          <form class="delete-form clearfix" data-section="accounts" method="POST" action="bankaccount/{{$account->bankAccountID}}">
          <input type="hidden" name="bankAccountID" value="{{$account->bankAccountID}}">
            <a href="#" class="delete-btn-ajax btn btn-danger btn-sm button--winona">
              <span><i class="fa fa-trash" aria-hidden="true"></i> Delete</span><span class="after">Sure?</span>
            </a>
            <input type="hidden" name="_method" value="DELETE"> 
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <p class="text-muted">Click the table cell to edit an item</p>
  
</div>
<div class="col-md-6">
  <h4>Add Account</h4>
  <form class="form-horizontal ajax-process  pull" action="/account" method="POST">
      {{ csrf_field() }}
      <div class="form-group">
        <input type="text" name="accountNumber" placeholder="Account Number" class="input-req  form-control">
      </div>  
      <div class="form-group">
        <select class="form-control input-req" name="bankmasterID">
            <option value="">Select a Bank</option>
            @foreach ($banks as $bank)
              <option value="{{$bank->bankmasterID}}">{{ $bank->bankName }}</option>
            @endforeach
        </select>
      </div>
      <div class="form-group">
        <select class="form-control input-req" name="chartOfAccountID">
            <option value="">Select a chart ofaccount</option>
            @foreach ($chartofaccounts as $chartofaccount)
              <option value="{{$chartofaccount->chartOfAccountID}}">{{ $chartofaccount->accountDescription }}</option>
            @endforeach
        </select>
      </div>

      <button type="submit" class="btn btn-info " data-section="accounts"><i class="fa fa-plus"></i> Add</button>
  </form>
  
</div>
