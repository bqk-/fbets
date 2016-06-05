@extends('layouts.default')
@section('content')
        <h2>{{ trans('groups.view_title') }}</h2>
        <div class="row">
            <div class="col-md-8">
                <h3>{{{ $group->name  }}}</h3>
                <code>
                    {{{ $group->description }}}
                </code>
            </div>
            <div class="col-md-4">
                <a href="/group/invite/{{ $group->id }}">{{ trans('groups.invite') }}</a><br/>
                <a href="/group/history/{{ $group->id }}">{{ trans('groups.history') }}</a><br/>
                <a href="/group/request/{{ $group->id }}">{{ trans('groups.request') }}</a><br/>
                <a href="/group/games/{{ $group->id }}">{{ trans('groups.games') }}</a><br/>
                <a href="/group/ladder/{{ $group->id }}">{{ trans('groups.ladder') }}</a><br/>
            </div>
        </div>
@stop