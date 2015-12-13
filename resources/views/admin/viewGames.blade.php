@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Games</h2>

            <div class="col-md-8 admin">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{{ $championship->name }}}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach($games as $g)
                                <li class="list-group-item">
                                    {{ $g->team1()->name }} - {{ $g->team2()->name }} | {{ $g->date }}
                                </li>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop