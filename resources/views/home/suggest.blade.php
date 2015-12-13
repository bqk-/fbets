@extends('layouts.default')
@section('content')
  <div class="row">
          @if(Auth::check())
      <div class="col-md-6">
        @else
        <div class="col-md-12">
        @endif
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('general.page.suggest') }}</h3>
          </div>
          <div class="panel-body">
            {!! Form::open(array('url' => '/suggest', 'class' => 'form-horizontal')) !!}
             <div class="form-group">
                  <label for="inputEvent" class="col-lg-2 control-label">{{ trans('forms.event') }}</label>
                  <div class="col-lg-10">
                  {!! Form::text('event', Input::get('event'), array('class' => 'form-control', 'id' => 'inputEvent')) !!} 
                  </div>
                  <div class="text-danger col-md-offset-3">{{ $errors->first('event') }}</div>
                </div>

                <div class="form-group">
                  <label for="inputSport" class="col-lg-2 control-label">{{ trans('forms.sport') }}</label>
                  <div class="col-lg-10">
                  {!! Form::select('sport', $sports, Input::get('sport'), array('class' => 'form-control', 'id' =>
                      'inputSport')) !!}
                  </div>
                  <div class="text-danger col-md-offset-3">{{ $errors->first('sport') }}</div>
                </div>
            

                <div class="form-group">
                  <label for="inputTeam1" class="col-lg-2 control-label">{{ trans('forms.team1') }}</label>
                  <div class="col-lg-10">
                  {!! Form::text('team1', Input::get('team1'), array('class' => 'form-control team1', 'id' => 'inputTeam1', 'placeholder' => 'Equipe 1')) !!}
                  </div>
                  <div class="text-danger col-md-offset-3">{{ $errors->first('team1') }}</div>
                </div>
            
                <div class="form-group">
                  <label for="inputTeam2" class="col-lg-2 control-label">{{ trans('forms.team2') }}</label>
                  <div class="col-lg-10">
                  {!! Form::text('team2', Input::get('team2'), array('class' => 'form-control team2', 'id' => 'inputTeam2', 'placeholder' => 'Equipe 2')) !!} 
                  </div>
                  <div class="text-danger col-md-offset-3">{{ $errors->first('team2') }}</div>
                </div>
                
                <div class="form-group">
                  <label for="inputDate" class="col-lg-2 control-label">{{ trans('forms.date') }}</label>
                  <div class="col-lg-10">
                  {!! Form::input('date', 'date', Input::get('date'), array('class' => 'form-control', 'id' => 'inputDate', 'placeholder' => 'Date')) !!} 
                  </div>
                  <div class="text-danger col-md-offset-3">{{ $errors->first('date') }}</div>
                </div>
                <div class="form-group">
                  <label for="inputHeure" class="col-lg-2 control-label">{{ trans('forms.time') }}</label>
                  <div class="col-lg-10">
                  {!! Form::input('time', 'time', Input::get('time'), array('class' => 'form-control', 'id' => 'inputHeure', 'placeholder' => 'Heure')) !!} 
                  </div>
                  <div class="text-danger col-md-offset-3">{{ $errors->first('time') }}</div>
                </div>
                 <div class="form-actions text-center">
                   {!! Form::submit(trans('general.suggest'), array('class' => 'btn btn-primary')) !!}
                </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
      @if(Auth::check())
      <div class="col-md-6">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('general.mysuggestions') }}</h3>
          </div>
          <div class="panel-body">
          @foreach($suggestions as $s)
            {{ $s->team1 }} - {{ $s->team2 }} {{ $s->date }} <span class="pull-right">{{ trans('general.sug.state'.$s->state) }}</span>
          @endforeach
          </div>
        </div>
      </div>
      @endif
  </div>
@stop