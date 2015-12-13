@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2>{{ trans('general.page.login') }}</h2>

      <div>
  {!! Form::open(array('url' => 'login', 'class' => 'form-horizontal')) !!}
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">{{ trans('forms.email') }}</label>
      <div class="col-lg-10">
      {!! Form::email('email', null, array('class' => 'form-control', 'autocomplete' => 'off', 'id' => 'inputEmail', 'placeholder' => trans('forms.email_ph'))) !!}
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword" class="col-lg-2 control-label">{{ trans('forms.pass') }}</label>
      <div class="col-lg-10">
        {!! Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off', 'id' => 'inputPassword', 'placeholder' => trans('forms.pass_ph'))) !!}
      </div>
    </div>
    <div class="checkbox col-md-offset-4">
          <label>
            <input type="checkbox" name="remember"> {{ trans('forms.rememberme') }}
          </label>
        </div>
        <br />
    <div class="form-actions text-center">
    {!! Form::submit(trans('forms.loginbutton'), array('class' => 'btn btn-default')) !!}
    </div>
    {!! Form::close() !!}
  <p>Not a member?  <a href="register">Register here</a>.</p>
</div>
</div>
</div>
@stop