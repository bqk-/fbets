@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Administration</h2>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ajouter un sport</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        {!! Form::open(array('url' => 'admin/new-sport', 'role' => 'form', 'files' => 'true')) !!}
                        <div class="form-group">
                            <label for="inputName" class="col-lg-2 control-label">Nom</label>
                            <div class="col-lg-10">
                                {!! Form::text('name', Session::get('name'), array('class' => 'form-control',
                                'id' => 'inputName', 'placeholder' => 'Nom')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('name') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="inputDeb" class="col-lg-2 control-label">Logo</label>
                            <div class="col-lg-10">
                                {!! Form::file('logo', array('class' => 'form-control', 'id' => 'inputDeb',
                                'placeholder' => 'Logo Sport')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('first') }}</div>
                        </div>
                        <div class="form-actions text-center">
                            {!! Form::submit('Ajouter >', array('class' => 'btn btn-primary')) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop