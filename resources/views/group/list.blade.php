@extends('layouts.default')
@section('content')
<a href="{{URL::to('group')}}">< {{trans('general.back')}}</a>
  <div class="row">
    <h2>{{ trans('general.page.groups') }}</h2>
      @foreach($groups as $g)
      <div class="row">
          <div class="col-md-4">
               <a href="{{URL::to('group/view/' . $g->id)}}">{{{$g->name}}}</a>
        </div>
          <div class="col-md-4">
              {{$g->users()->count()}} {{$g->users()->count() > 1 ? trans('groups.member') : trans('groups.members')}}
            </div>
            <div class="col-md-4">
              @if($g->hasApplication)
                <span class="glyphicon glyphicon-check"></span> {{trans('groups.appli_submitted')}} 
              @endif
        </div>
      @endforeach
  </div>
@stop