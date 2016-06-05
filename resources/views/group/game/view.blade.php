@extends('layouts.default')
@section('content')
        <h2>{{ trans('groups.view_game') }}</h2>
        <div class="row">
            <div class="col-md-8">
                <h3>{{{ $game->team1  }}} - {{{ $game->team1  }}}</h3>
                <code>
                    {{{ $group->description }}}
                </code>
            </div>
            <div class="col-md-4">
                
            </div>
        </div>
@stop