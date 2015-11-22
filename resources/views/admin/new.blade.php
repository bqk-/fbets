@extends('layouts.default')
@section('content')
{{ Session::forget('matchs') }}
{{ Session::forget('name') }}
{{ Session::forget('first') }}
{{ Session::forget('last') }}
<div class="row">
    <div class="col-md-12">
        <h2>Administration</h2>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ajouter championnat</h3>
            </div>
            <div class="panel-body">
              <div class="list-group">
              {!! Form::open(array('url' => 'admin/new', 'role' => 'form')) !!}
              <div class="form-group">
                <label for="inputName" class="col-lg-2 control-label">Nom</label>
                <div class="col-lg-10">
                {!! Form::text('name', Session::get('name'), array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => 'Nom')) !!}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('name') }}</div>
              </div>
        <div class="form-group">
                <label for="inputSport" class="col-lg-2 control-label">Sport</label>
                <div class="col-lg-10">
                {!! Form::select('sport', App\Models\Sport::lists('name', 'id'), Session::get('sport'), array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => 'Sport')) !!}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('sport') }}</div>
              </div>
                  <div class="form-group">
                      <label for="inputName" class="col-lg-2 control-label">Class App\Models\Admin</label>
                      <div class="col-lg-10">
                          {!! Form::text('class', Session::get('class'), array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => 'Class')) !!}
                      </div>
                      <div class="text-danger col-md-offset-3">{{ $errors->first('class') }}</div>
                  </div>
                 <div class="form-actions text-center">
                 {!! Form::hidden('type', $type) !!}
                 {!! Form::submit('Suivant >', array('class' => 'btn btn-primary')) !!}
              </div>
                {!! Form::close() !!}
              </div>
            </div>
        </div>
    </div>
  </div>

@stop
