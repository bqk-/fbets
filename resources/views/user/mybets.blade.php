@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
      <h2>{{ trans('general.page.mybets') }}</h2>

      <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ trans('profile.oldbets') }}</h3>
            </div>
            <div class="panel-body">
                <b>{{ trans('profile.pseudolb') }}</b> : {{{ $user->pseudo }}}<br />
                <b>{{ trans('profile.displaylb') }}</b> : {{{ $user->display }}}<br />
                <b>{{ trans('profile.emaillb') }}</b> : {{{ $user->email }}}<br />
                <b>{{ trans('profile.registerlb') }}</b> : {{ $user->created_at }}<br />
            </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">{{ trans('profile.newbets') }}</h3>
            </div>
            <div class="panel-body">
              <a href="{{ URL::to('recover') }}">{{ trans('profile.changepw') }}</a>
            </div>
        </div>
      </div>
    </div>
  </div>

@stop