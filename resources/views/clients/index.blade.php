@extends('layouts.master')
@section('heading')

@stop

@section('content')
<div class="well">
<form name="frm-example" id="frm-example" method="post" action="{{ action('IctbroadcastcController@Ictbroadcast') }}" >

 <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" name="contact" value="clients">


 <div style="text-align:center">
    <label >Bulk Action</label>
    <select name="action" disabled id="action">
    <option value='' >---Select Action---</option>
        <!-- <option value='ictbroadcast'  onclick="this.form.submit()">ICTBroadcast</option>-->
          <option value='ictbroadcast'  onclick="this.form.submit()">ICTBroadcast</option>


    </select>
    </div>
    <table class="table table-hover " id="clients-table">
        <thead>
        <tr>
                 <th><input name="select_all" value="1" id="example-select-all" type="checkbox"></th>

            <th>{{ __('Name') }}</th>
            <th>{{ __('Company') }}</th>
            <th>{{ __('Mail') }}</th>
            <th>{{ __('Number') }}</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
    </table>
</form>
</div>
@stop

@push('scripts')
<script>
    $(function () {
          var from = jQuery('select[name=action]');
       var table = $('#clients-table').DataTable({
          "pageLength": 25,
            processing: true,
            serverSide: true,
            ajax: '{!! route('clients.data') !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'namelink', name: 'name'},
                {data: 'company_name', name: 'company_name'},
                {data: 'email', name: 'email'},
                {data: 'primary_number', name: 'primary_number'},
                @if(Entrust::can('client-update'))   
                { data: 'edit', name: 'edit', orderable: false, searchable: false},
                @endif
                @if(Entrust::can('client-delete'))   
                { data: 'delete', name: 'delete', orderable: false, searchable: false},
                @endif

            ],
         'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
            // return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
              return '<input type="checkbox" name="id[]"  value="' + $('<div/>').text(data).html() + '">';
         }
      }],
        });
        $( "#clients-table_paginate" ).click(function() {
         $('#example-select-all').prop('checked', false); // Unchecks it

  //alert( "Handler for .click() called." );
});
  // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){

   // alert(44444444);
    from.removeAttr("disabled");
      // Get all rows with search applied
      var rows = table.rows({ 'search': 'applied' }).nodes();
      // Check/uncheck checkboxes for all rows in the tab
      $('input[type="checkbox"]', rows).prop('checked', this.checked);


       if(!this.checked){
        from.attr('disabled', 'disabled');
        //alert('no');
    }
   });

   // Handle click on checkbox to set state of "Select all" control
   $('#clients-table tbody').on('change', 'input[type="checkbox"]', function(){
   // alert(656);

      //var from = jQuery('select[name=action]');
    
    from.removeAttr("disabled");
      // If checkbox is not checked
      if(!this.checked){
        from.attr('disabled', 'disabled');
       // alert('no');
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
    });

 $( "#action" ).click(function() {

var val = $( "#action option:selected" ).text();
if(val=='ICTBroadcast'){
  this.form.submit();
}
  //alert( "Handler for .click() called." );
});
</script>
@endpush
