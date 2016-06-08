@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2>{{ trans('general.page.register') }}</h2>

      <div>
        {!! Form::open(array('url' => 'register', 'role' => 'form')) !!}
               
        <div class="form-group">
          <label for="inputEmail" class="col-lg-2 control-label">{{ trans('forms.email') }}</label>
          <div class="col-lg-10">
          {!! Form::email('email', null, array('class' => 'form-control', 'id' => 'inputEmail', 'placeholder' => trans('forms.email_ph'))) !!} 
          </div>
          <div class="text-danger col-md-offset-3">{{ $errors->first('email') }}</div>
        </div>
       
       <div class="form-group">
          <label for="inputName" class="col-lg-2 control-label">{{ trans('forms.name') }}</label>
          <div class="col-lg-10">
          {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => trans('forms.name_ph'))) !!} 
          </div>
          <div class="text-danger col-md-offset-3">{{ $errors->first('name') }}</div>
        </div>

        <div class="form-group">
          <label for="inputDisplay" class="col-lg-2 control-label">{{ trans('forms.display') }}</label>
          <div class="col-lg-10">
          {!! Form::text('display', null, array('class' => 'form-control', 'id' => 'inputDisplay', 'placeholder' => trans('forms.display_ph'))) !!} 
          </div>
          <div class="text-danger col-md-offset-3">{{ $errors->first('display') }}</div>
        </div>
       
       <div class="form-group">
          <label for="inputPassword" class="col-lg-2 control-label">{{ trans('forms.pass') }}</label>
          <div class="col-lg-10">
            {!! Form::password('password', array('class' => 'form-control', 'id' => 'inputPassword', 'placeholder' => trans('forms.pass_ph'))) !!} 
          </div>
       
          <label for="inputPassword2" class="col-lg-2 control-label">{{ trans('forms.repeatpass') }}</label>
          <div class="col-lg-10">
            {!! Form::password('password_confirmation', array('class' => 'form-control', 'id' => 'inputPassword2', 'placeholder' => trans('forms.pass_ph'))) !!} 
          </div>
          @if($errors->first('password'))
          <div class="text-danger col-md-offset-3">{{ $errors->first('password') }}</div>
          @endif
        </div>

        <div class="form-actions text-center">
        {!! Form::submit(trans('forms.registerbtn'), array('class' => 'btn btn-primary big-button')) !!}
        </div>
          {!! Form::close() !!}
      </div>
    </div>
  </div>

@stop