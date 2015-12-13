@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Administration</h2>
      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Entrer résultats</strong></h3>
            </div>
            <div class="panel-body">
            {!! Form::open(array('url' => 'admin/', 'role' => 'form', 'id' => 'enterresults')) !!}
            <?php $c = 0; ?>
                @foreach ($games as $g)
                  @if($c != $g->id_championship)
                    @if($c != 0)
                    </div>
                    @endif
                    <div class="list-group">
                     <a href="#" class="list-group-item active">
                        {{ $g->name }}
                      </a>
                  @endif
                        <a href="#" class="list-group-item"><img src="{{ $g->plogo1() }}" /> {{ $g->team1 }}
                        <input type="number" min="0" class="text-center" name="games[{{$g->id}}][team1]" id="games[{{$g->id}}][team1]" /> - <input type="number" min="0" class="text-center" name="games[{{$g->id}}][team2]" id="games[{{$g->id}}][team2]" />
                        {{ $g->team2 }} <img src="{{ $g->plogo2() }}" /> - {{ $g->date }} - 
                        <strong>{{ $g->bets }} paris sur ce match</strong>
                        </a>
                    <?php $c=$g->id_championship; ?>
                @endforeach
                {!! Form::close() !!}
                <ul class="pager">
                  <li><a href="{{ URL::to('admin/') }}">Retour</a></li>
                  <li><a href="#" onclick="document.getElementById('enterresults').submit(); return false;">Valider</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>

@stop