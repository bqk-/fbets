@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Suggestions de matchs</h2>
        <div class="list-group">
        <?php $notFirst = false; $date = null; ?>
              @foreach($suggestions as $s)
                @if($date != explode(' ',$s->date)[0])
                  @if($notFirst)
                              </ul>
                  @else
                    <?php $notFirst = true; ?>
                  @endif
                    <h4>
                       {{ $s->dateOnly() }} 
                    </h4>
                    <ul class="list-group">
                    <?php $date = explode(' ',$s->date)[0]; ?>
                @endif
                      <li class="list-group-item">
                        <div class="row">
                          <div class="col-md-2 state" style="padding-top:10px;">{{ trans('general.sug.state'.$s->state) }}</div>
                          <div class="col-md-3" style="padding-top:10px;"><img src="{{ Sport::find($s->id_sport)->plogo() }}" /> {{ $s->team1 }} - {{ $s->team2 }}</div>
                          <div class="col-md-2 text-center" style="padding-top:10px;">{{ $s->championship }}</div>
                          <div class="col-md-2" style="padding-top:10px;">{!! Form::select('id_champ', [0 => 'Choisir evenement'] + App\Models\Championship::lists('name','id'), null, array('id'=>'suggselect')) !!}</div>
                          <div class="col-md-1" style="padding-top:10px;"><small><p class="text-center pull-right">{{ $s->heureOnly() }}</p></small></div>
                          <div class="col-md-2">
                            @if($s->state == 2 || $s->state == 0)
                            <button class="btn btn-success" id="suggok" data-id="{{ $s->id }}"><span class="glyphicon glyphicon-ok"></span></button>
                            <button class="btn btn-danger" id="suggno" data-id="{{ $s->id }}"><span class="glyphicon glyphicon-remove"></span></button>
                            @endif
                          </div>
                        </div>
                      </li>
              @endforeach
              </div>
            </ul>
    </div>
  </div>

@stop