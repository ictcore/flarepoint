@extends('layouts.master')
@section('heading')
    <h1>{{__('All leads')}}</h1>
@stop

@section('content')
<div class="well">
<form name="frm-example" id="frm-example" method="post" action="{{ action('IctbroadcastcController@Ictbroadcast') }}" >

 <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input type="hidden" name="contact" value="leads">


 
<div style="text-align:center">
    <label >Bulk Action</label>
    <select name="action" disabled>
    <option value='' >---Select Action---</option>
        <option value='ictbroadcast'  onclick="this.form.submit()">ICTBroadcast</option>

    </select>
    </div>
    <table class="table table-hover" id="leads-table">
        <thead>
        <tr>
         <th><input name="select_all" value="1" id="example-select-all" type="checkbox"></th>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Created by') }}</th>
            <th>{{ __('Deadline') }}</th>
            <th>{{ __('Assigned') }}</th>

        </tr>
        </thead>
    </table>
   <!-- <p class="form-group">
   <button type="submit" class="btn btn-primary">Submit</button>
</p>
<p class="form-group">
   <b>Data submitted to the server:</b>
   </p><pre id="example-console"></pre>
<p></p>-->
</form>




</div>
@stop

@push('scripts')
<script>
    $(function () {
        var from = jQuery('select[name=action]');

     var table =   $('#leads-table').DataTable({
            "pageLength": 25,
            processing: true,
            serverSide: true,
            ajax: '{!! route('leads.data') !!}',
            columns: [
                 {data: 'id', name: 'id'},
                {data: 'titlelink', name: 'title'},
                {data: 'user_created_id', name: 'user_created_id'},
                {data: 'contact_date', name: 'contact_date',},
                {data: 'user_assigned_id', name: 'user_assigned_id'},


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


$( "#leads-table_paginate" ).click(function() {
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
   $('#leads-table tbody').on('change', 'input[type="checkbox"]', function(){
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

</script>
@endpush