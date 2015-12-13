@extends('layouts.default')
@section('content')
  <div class="row">
  	<div class="col-md-6 col-md-offset-3">
	  	<h2>Password Reset</h2>

			<div>
				To reset your password, complete this form: <br />
				{!! Form::open(array('url' => 'recover', 'role' => 'form')) !!}
	            <div class="form-group">
	    			<label class="sr-only" for="emInput">Email address</label>
	    			{{ Form::email('email', null, array('class' => 'form-control', 'id' => 'emInput', 'placeholder' => 'Enter email')) }} 
	            </div>
				<div class="form-actions text-center">
	            {{ Form::submit('Send reset link', array('class' => 'btn btn-default')) }}
	            </div>
	            {!! Form::close() !!}
			</div>
		</div>
  </div>
@stop