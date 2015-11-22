 @extends('layouts.default')
@section('content')
<div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Editer match</h3>
          </div>
          <div class="panel-body">
            <div class="list-group">
            {!! Form::open(array('url' => 'admin/new', 'role' => 'form')) !!}
            <div class="form-group">
                <label for="inputName" class="col-lg-2 control-label">Groupe</label>
                <div class="col-lg-10">
                {{ Form::select('groupe', App\Models\Data\Championship::lists('name', 'id'), $g->id_championship, array('class' => 'form-control', 'id' => 'inputName')) }}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('name') }}</div>
              </div>
              <div class="form-group">
                <label for="inputTeam1" class="col-lg-2 control-label">Equipe 1</label>
                <div class="col-lg-5">
                {{ Form::text('team1', $g->team1, array('class' => 'form-control team1', 'id' => 'inputTeam1', 'placeholder' => 'Equipe 1')) }} 
                </div>
                <div class="col-lg-5">
                 <label for="inputScore1" class="col-lg-2 control-label">Score</label>
                {{ Form::text('score1', $g->score1, array('class' => 'form-control', 'id' => 'inputScore1', 'placeholder' => 'Score')) }} 
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('team1') }}</div>
              </div>
              <div class="form-group">
                <label for="inputLogo1" class="col-lg-2 control-label">Logo 1</label>
                <div class="col-lg-10">
                  <span class="form-control logo1"></span>
                  {{ Form::hidden('hlogo1') }}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('logo1') }}</div>
              </div>
              <div class="form-group">
                <label for="inputTeam2" class="col-lg-2 control-label">Equipe 2</label>
                <div class="col-lg-5">
                {{ Form::text('team2', $g->team2, array('class' => 'form-control team2', 'id' => 'inputTeam2', 'placeholder' => 'Equipe 2')) }} 
                </div>
                <div class="col-lg-5">
                <label for="inputScore2" class="col-lg-2 control-label">Score</label>
                {{ Form::text('score2', $g->score2, array('class' => 'form-control', 'id' => 'inputScore2', 'placeholder' => 'Score')) }} 
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('team2') }}</div>
              </div>
              <div class="form-group">
                <label for="inputLogo2" class="col-lg-2 control-label">Logo 2</label>
                <div class="col-lg-10">
                  <span class="form-control logo2"></span>
                  {{ Form::hidden('hlogo2') }}
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('logo2') }}</div>
              </div>
              <div class="form-group">
                <label for="inputDate" class="col-lg-2 control-label">Date</label>
                <div class="col-lg-10">
                {{ Form::input('date', 'date', explode(' ',$g->date)[0], array('class' => 'form-control', 'id' => 'inputDate', 'placeholder' => 'Date')) }} 
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('date') }}</div>
              </div>
              <div class="form-group">
                <label for="inputHeure" class="col-lg-2 control-label">Heure</label>
                <div class="col-lg-10">
                {{ Form::input('time', 'time', explode(' ',$g->date)[1], array('class' => 'form-control', 'id' => 'inputHeure', 'placeholder' => 'Heure')) }} 
                </div>
                <div class="text-danger col-md-offset-3">{{ $errors->first('time') }}</div>
              </div>
               <div class="form-actions text-center">
                 {{ Form::hidden('id', $g->id) }} 
                 {{ Form::submit('Modifier', array('class' => 'btn btn-primary')) }}
              </div>
                {!! Form::close() !!}
              </div>
            </div>
        </div>
      </div>
</div>
@stop