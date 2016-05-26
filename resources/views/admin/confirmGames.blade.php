@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Event: {{ $championship->name }}</h2>
            {!! Form::open(array('url' => 'admin/save-champ', 'role' => 'form')) !!}
            <input type="hidden" name="id_champ" value="{{ $championship->id }}" />

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Params</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @for($i = 0; $i < count($params); $i++)
                                <a href="#" class="list-group-item">
                                    {{ $params[$i]->name }}:
                                    {!! Form::text('param[]', $usedParams[$i]) !!}
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Teams</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach ($teams as $t)
                                <a href="#" class="list-group-item">
                                    @if(array_key_exists($t->Id, $relations))
                                        Local: {{ $relations[$t->Id] }}
                                    @else
                                        {!! Form::select('action['.$t->Id.']', $existingTeams) !!}
                                    @endif
                                    <img style="width: 32px; height: 32px;" src="{{ $t->LogoUrl }}" /> {{ $t->Id }}: {{ $t->Name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Games</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach ($games as $game)
                            <a href="#" class="list-group-item"><img style="width: 32px; height: 32px;" src="{{ $game->TeamHome->LogoUrl }}" /> {{ $game->TeamHome->Name
                                }} {{ $game->Score() }} {{ $game->TeamVisit->Name }} <img style="width: 32px; height: 32px;" src="{{ $game->TeamVisit->LogoUrl }}" /> | {{
                                $game->Date }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <ul class="pager">
                    <li><a class="btn btn-danger" href="{{ URL::to('admin/view-championship/' . $championship->id)
                    }}">Cancel</a></li>
                    <li><input class="btn btn-success" type="submit" value="Add" /></li>
                </ul>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop