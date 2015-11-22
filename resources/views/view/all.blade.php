@extends('layouts.default')
@section('content')
<div class="row">
    @foreach($champs as $champ)
      <div class="col-md-6">
        <div class="panel panel-default homebox">
          <div class="panel-heading">
          <div class="row">
            <span class="col-md-9 homeboxtitle">
            <a href="{{ URL::to('view/championship') }}/{{ $champ->id }}">
              <img src="{{ $champ->sport()->first()->logo }}" alt="Sport" /> {{ $champ->name }}
              </a>
            </span>
            <span class="col-md-3 pull-right"><a href="{{ URL::to('view/championship/' . $champ->id) }}" class="btn btn-info moreindex">{{ trans('general.more') }}</a></span>
          </div>
          </div>
          <div class="panel-body ajax-content">
                {{ $champ->games()->count() }} games.
          </div>
        </div>
      </div>
      @endforeach
  </div>

@stop