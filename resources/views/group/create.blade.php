@extends('layouts.default')
@section('content')
    <div class="row">
        <h2>{{ trans('groups.create_title') }}</h2>
        <div class="col-md-12">
            {!! Form::open(array('url' => 'group/create', 'role' => 'form')) !!}
            <div class="form-group">
                <label for="inputName" class="col-lg-2 control-label">{{ trans('forms.gname') }}</label>
                <div class="col-lg-10">
                    {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => trans('forms.gname_ph'))) !!}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('name') }}</div>
            </div>
            <div class="form-group">
                <label for="inputDesc" class="col-lg-2 control-label">{{ trans('forms.description') }}</label>
                <div class="col-lg-10">
                    {!! Form::text('description', null, array('class' => 'form-control', 'id' => 'inputDesc', 'placeholder' => trans('forms.desc_ph'))) !!}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('description') }}</div>
            </div>
            
            <!-- <div class="form-group">
                <label for="inputStart" class="col-lg-2 control-label">{{ trans('forms.start') }}</label>
                <div class="col-lg-10">
                    {!! Form::date('start', null, array('class' => 'form-control', 'id' => 'inputStart', 'placeholder' => trans('forms.start_ph'))) !!}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('start') }}</div>
            </div>
             <div class="form-group">
                <label for="inputEnd" class="col-lg-2 control-label">{{ trans('forms.end') }}</label>
                <div class="col-lg-10">
                    {!! Form::date('end', null, array('class' => 'form-control', 'id' => 'inputEnd', 'placeholder' => trans('forms.end_ph'))) !!}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('end') }}</div>
            </div>-->
            
            <input class="center-block btn btn-default" type="submit" value="{{ trans('forms.create') }}" />
            {!! Form::close() !!}
        </div>
    </div>
@stop