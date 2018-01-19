@extends('layouts.master')

@section('content')

    <div class="row">
        <h3>{{ __('Integrations') }}</h3>
        <div class="col-sm-4">
            <img src="imagesIntegration/dinero-logo.png" width="50%" align="center" alt="">

            {!! Form::open([
               'route' => 'integrations.store'
           ]) !!}
            <div class="form-group">
                {!! Form::label('api_key', __('Api key'), ['class' => 'control-label']) !!}
                {!! Form::text('api_key', null, ['class' => 'form-control']) !!}
            </div>


            <div class="form-group">
                {!! Form::label('org_id',  __('Organization id'), ['class' => 'control-label']) !!}
                {!! Form::text('org_id', null, ['class' => 'form-control']) !!}
            </div>


            {!! Form::hidden('name', 'Dinero') !!}
            {!! Form::hidden('api_type', 'billing') !!}

            {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>



 <div class="col-sm-4">
            <img src="https://www.ictbroadcast.com/sites/default/files/ict_logo.png" width="50%" align="center" alt="" style="margin-top: 22px;">

            {!! Form::open([
               'route' => 'integrations.store'
           ]) !!}
            <div class="form-group">
                {!! Form::label('api_key', __('Api key'), ['class' => 'control-label']) !!}
                <!--{!! Form::text('api_key', null, ['class' => 'form-control']) !!}-->
                <textarea  name="api_key" class="form-control" >{{ $ictbroadcast[0]->api_key  }}</textarea> 

            </div>


            <div class="form-group">
                {!! Form::label('org_id',  __('URL'), ['class' => 'control-label']) !!}
                <!--{!! Form::text('org_id', null, ['class' => 'form-control']) !!}-->
                <input type="text" name="org_id" class="form-control" value="{{ $ictbroadcast[0]->org_id  }}">

            </div>


            {!! Form::hidden('name', 'ICTBroadcast') !!}

            {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>


        <div class="col-sm-4">

            <img src="imagesIntegration/billy-logo-final_blue.png" width="50%" align="center" alt="">
            {!! Form::open([

           ]) !!}
            <div class="form-group">
                {!! Form::label('api_key', __('Api key'), ['class' => 'control-label']) !!}
                {!! Form::text('api_key', null, ['class' => 'form-control']) !!}
            </div>


            {!! Form::hidden('name', 'Billy') !!}
            {!! Form::hidden('api_type', 'billing') !!}
            {!! Form::submit(__('Update'), ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>


@stop