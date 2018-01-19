@extends('layouts.master')
@section('heading')
    <h1>{{ __('Create ICTBroadcast Campaign') }}</h1>
@stop

@section('content')

   <!-- {!! Form::open([
            'route' => 'leads.store'
            ]) !!}-->
<?php if($extention_data=='' && $campaign_list==''){
 ?>

<div class="alert alert-danger">
  <strong>Danger!</strong> Some thing Wrong Please check Integration or ictbroadcast Add Extension.
</div>
<?php } ?>
            <form name="frm-example" id="frm-example" method="post" action="{{ action('IctbroadcastcController@Ictbroadcastcampaign') }}" enctype="multipart/form-data">

             <input type="hidden" name="_token" value="{{ csrf_token() }}">

             <input type="hidden" name="ids" value="{{ $ids }}">

              <input type="hidden" name="contact" value="{{ $contact }}">
 <div class="form-group" id="rdio">
         {!! Form::label('title', __('Campaign'), ['class' => 'control-label']) !!}

        <label class="radio-inline">
        <input type="radio" name="camp"  value="newc" checked> New Campaign
    </label>
     <label class="radio-inline">
        <input type="radio" name="camp" value="extc"> Existing Campaign
    </label>
    
    </div>
    <div id="newcampaignpart">
    <div class="form-group">
        {!! Form::label('title', __('Group'), ['class' => 'control-label']) !!}
        <input type="text" name="group" class="form-control">
    </div>



    <div class="form-group">
        {!! Form::label('campaign', __('Campaign Type'), ['class' => 'control-label']) !!}
        <select name="campaign_type" id = "c_type" class="form-control main-element"> 
        <option value="voice" selected="">Message Campaign</option>
       <!-- <option value="voice_agent">Agent Campaign</option>-->
        <option value="voice_interactive">Interactive Campaign</option>

      <!--  <option value="voice_ivr">IVR Campaign</option>-->
        <option value="fax">Fax Campaign</option>
</select> 

  <div class="form-group"  id="press1" style="display:none">
                        <label class="control-label" data-name="campaign_type">Select Extention</label>
                        <select name="extension" id="extension" class="select2 form-control">
                        <!--{foreach $Campaign_type as $Campaign_types}
                        <option value="{$Campaign_types->extension_id}">{$Campaign_types->name}</option>
                        {/foreach}-->
                        <?php if($extention_data!=''){ foreach($extention_data as $extentions){ ?>
                        <option value="<?php echo $extentions->extension_id ?>"><?php echo $extentions->name ?></option>
                        <?php } } ?>
                        </select>
                        </div>
</div>
<div class="form-group"  id="file">
                                
                        <label class="control-label" data-name="campaign_type">Choose File</label>
                                <input type="file" name="fle" id="fle"  class="input-large" >

                            </div>
</div>
     <div id="extsc" style="display:none;">
<h1>Campaign List</h1>
 <div class="form-group"  id="press1">
                        <label class="control-label" data-name="campaign_type">Select Campaign</label>
                        <select name="campl" id="campl" class="select2 form-control">
                        <!--{foreach $Campaign_type as $Campaign_types}
                        <option value="{$Campaign_types->extension_id}">{$Campaign_types->name}</option>
                        {/foreach}-->
                        <option value="">---Select Campaign---</option>option>
                       <?php if($campaign_list!=''){ foreach($campaign_list as $campaign_name){ ?>
                        <option value="<?php echo $campaign_name->campaign_id ?>"><?php echo $campaign_name->name ?></option>
                        <?php } } ?>
                        </select>
                        </div>

</div>


     

<?php if($extention_data !='' && $campaign_list !=''){ ?>

    {!! Form::submit(__('Create new Campaign'), ['class' => 'btn btn-primary']) !!}

   <?php  } ?>

    {!! Form::close() !!}
@stop
@push('scripts')
<script>

jQuery(document).ready(function() {


$("#rdio").click(function() {     
        var camp = $("input[name=camp]:checked").val();
        if(camp == 'newc'){
            
         $("#newcampaignpart").show();
         $("#extsc").hide();
        }
        if(camp == 'extc'){
             $("#extsc").show();
             $("#newcampaignpart").hide();
        }
    });



 $("#c_type").change(function()
    {
        //alert(434);
        var id=$(this).val();

        var dataString = 'id='+ id;
        // alert(id); 
        if(id=='voice_interactive'){
            // alert(id); 
          $("#file").show();
          $("#press1").show();

        }else{

            $("#file").show();
            $("#press1").hide();

        }
       
    });
});
</script>
@endpush