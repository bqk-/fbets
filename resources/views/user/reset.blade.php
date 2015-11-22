@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2>{{ trans('general.page.resetpw') }}</h2>
      {!! Form::open(array('url' => 'reset', 'role' => 'form')) !!}
      <div>
        <div class="form-group">
          <label for="inputPassword" class="col-lg-2 control-label">{{ trans('forms.pass') }}</label>
          <div class="col-lg-10">
            {{ Form::password('password', array('class' => 'form-control', 'id' => 'inputPassword', 'placeholder' => trans('forms.pass_ph'))) }} 
          </div>
       
          <label for="inputPassword2" class="col-lg-2 control-label">{{ trans('forms.repeatpass') }}</label>
          <div class="col-lg-10">
            {{ Form::password('password_confirmation', array('class' => 'form-control', 'id' => 'inputPassword2', 'placeholder' => trans('forms.pass_ph'))) }} 
          </div>
          @if($errors->first('password'))
          <div class="text-danger col-md-offset-3">{{ $errors->first('password') }}</div>
          @endif
        </div>

        <div class="form-actions text-center">
        {{ Form::submit(trans('forms.resetbtn'), array('class' => 'btn btn-primary')) }}
        </div>
          {!! Form::close() !!}
      </div>
      </div>
    </div>
  </div>

@stop