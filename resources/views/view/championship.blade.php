@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ $champ->name }}</h2>
      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
            <table class="table table-striped table-hover">
                @foreach ($champ->games as $g)
                        <tr style="height:36px;">
                            <td style="padding-top:13px;"><img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($g->team1()->first()->logo) }}" /></td>
                            <td style="padding-top:13px;">{{ $g->team1()->first()->name }}</td>
                            <td class="text-center small">
                                @if($g->score()->first() != null)
                                  {{ $g->score()->first()->team1 }} - {{ $g->score()->first()->team2 }}
                              @elseif(\App\Helpers\DateHelper::getTimestampFromSqlDate($g->date) < time())
                                  {{ trans('general.noresult') }}
                              @endif
                                                          <br />
                                @if(array_key_exists($g->id, $bets))
                                    <span class="text-muted">
                                        {{ trans('general.yourbet') }}:
                                        {{ $bets[$g->id]->score1 }} - {{ $bets[$g->id]->score2 }}
                                    </span>
                                    @if($g->score()->first() != null)
                                        {{ \App\Helpers\ViewHelper::getPointsFromScore($g->score()->first(), $bets[$g->id]) }}</td>
                                    @endif
                                @endif
                            <td style="padding-top:13px;">{{ $g->team2()->first()->name }}</td>
                            <td style="padding-top:13px;"><img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($g->team2()->first()->logo) }}" /></td>
                            <td style="padding-top:13px;">{{ \App\Helpers\DateHelper::sqlDateToStringHuman($g->date) }}</td>
                        </tr>
                @endforeach
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>

@stop