@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Administration</h2>
      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Envoyer un mail</strong></h3>
            </div>
            <div class="panel-body">
            {!! Form::open(array('url' => 'admin/mailsender', 'role' => 'form', 'id' => 'mailsender')) !!}
              @foreach($errors->all('
              :message
              ') as $message) {{ $message }} @endforeach

              {{ Form:: label ('email', 'To') }}
              {{ Form:: email ('email', '', array('placeholder' => 'me@example.com')) }}
              <br />
              <br />
              {{ Form:: label ('subject', 'Subject') }}
              {{ Form:: text ('subject', '', array('placeholder' => 'Hello world :)')) }}
              <br />
              <br />
              {{ Form:: label ('message', 'Message' )}}
              {{ Form:: textarea ('message', '')}}

                {!! Form::close() !!}
                <ul class="pager">
                  <li><a href="{{ URL::to('admin/') }}">Retour</a></li>
                  <li><a href="#" onclick="document.getElementById('mailsender').submit(); return false;">Valider</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>

@stop