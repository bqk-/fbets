@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Administration</h2>
      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ajouter championnat : <strong>{{ Session::get('name') }}</strong></h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                @foreach (Session::get('games') as $game)
                    <a href="#" class="list-group-item"><img src="{{ $game['logo1']}}" /> {{ $game['team1'] }} {{ $game['score'] }} {{ $game['team2'] }} <img src="{{ $game['logo2']}}" /> | {{ $game['date'] }}
                    </a>
                @endforeach
                </div>
                <ul class="pager">
                  <li><a href="{{ URL::to('admin/new-champ') }}">Retour</a></li>
                  <li><a href="{{ URL::to('admin/save-champ') }}">Valider</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>

@stop