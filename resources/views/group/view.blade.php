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
                @if($isMember)
                    <a href="/group/invite/{{ $group->id }}">{{ trans('groups.invite') }}</a><br/>
                    <a href="/group/history/{{ $group->id }}">{{ trans('groups.history') }}</a><br/>
                    <a href="/group/polls/{{ $group->id }}">{{ trans('groups.polls') }}</a><br/>
                    <a href="/group/games/{{ $group->id }}">{{ trans('groups.games') }}</a><br/>
                    <a href="/group/ladder/{{ $group->id }}">{{ trans('groups.ladder') }}</a><br/>
                @elseif($hasApplication)
                    <span class="glyphicon glyphicon-check"></span> {{trans('groups.appli_submitted')}}
                @else
                    <a href="/group/apply/{{ $group->id }}">{{ trans('groups.apply') }}</a><br/>
                @endif
            </div>
        </div>
@stop