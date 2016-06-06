@extends('layouts.default')
@section('content')
  <div class="row">
    <h2>{{ trans('general.page.groups') }}</h2>
      <div class="col-md-12">
        <h3>{{ trans('groups.description_title') }}</h3>
        <p>{{ trans('groups.description_text') }}</p>
      </div>

      <div class="col-md-6">
        <h3>{{ trans('groups.join_title') }}</h3>
        <p>{{ trans('groups.join_text') }}</p>
        <a href="{{URL::to('group/list')}}" class="btn btn-default">{{ trans('groups.join_button') }}</a>
      </div>

      <div class="col-md-6">
        <h3>{{ trans('groups.create_title') }}</h3>
        <p>{{ trans('groups.create_text') }}</p>
        <a href="{{URL::to('group/create')}}" class="btn btn-default">{{ trans('groups.create_button') }}</a>
      </div>
    
    <h2>{{ trans('general.groups') }}</h2>
            <ul class="pnlist">
                @if(Auth::check())
                    @foreach(Auth::user()->groups as $g)
                        <li class="pnli"><a href="/group/view/{{ $g->id }}">{{{ $g->name }}}</a></li>
                    @endforeach
                @endif
            </ul>
  </div>
@stop