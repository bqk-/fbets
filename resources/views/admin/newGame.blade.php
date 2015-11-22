@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Administration</h2>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ajouter un match</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        {!! Form::open(array('url' => 'admin/new-game', 'role' => 'form')) !!}
                        <div class="form-group">
                            <label for="inputName" class="col-lg-2 control-label">Evenement</label>
                            <div class="col-lg-10">
                                {!! Form::select('event', $championships,
                                Session::get('event'),
                                array('class' => 'form-control', 'id' => 'inputName')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('name') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="inputTeam1" class="col-lg-2 control-label">Equipe 1</label>
                            <div class="col-lg-10">
                                {!! Form::select('team1', $teams, Session::get
                                ('team1'),
                                array('class' => 'form-control team1', 'id' => 'inputTeam1', 'placeholder' => 'Equipe 1')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('team1') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="inputTeam2" class="col-lg-2 control-label">Equipe 2</label>
                            <div class="col-lg-10">
                                {!! Form::select('team2', $teams, Session::get
                                ('team2'),
                                array('class' => 'form-control team2', 'id' => 'inputTeam2', 'placeholder' => 'Equipe 2')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('team2') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="inputDate" class="col-lg-2 control-label">Date</label>
                            <div class="col-lg-10">
                                {!! Form::input('date', 'date', Session::get('date'), array('class' => 'form-control',
                                'id' => 'inputDate', 'placeholder' => 'Date')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('date') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="inputHeure" class="col-lg-2 control-label">Heure</label>
                            <div class="col-lg-10">
                                {!! Form::input('time', 'time', Session::get('time'), array('class' => 'form-control',
                                'id' => 'inputHeure', 'placeholder' => 'Heure')) !!}
                            </div>
                            <div class="text-danger col-md-offset-3">{{ $errors->first('time') }}</div>
                        </div>
                        <div class="form-actions text-center">
                            {!! Form::submit('Ajouter', array('class' => 'btn btn-primary')) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop