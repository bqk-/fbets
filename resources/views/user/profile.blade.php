@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
    @if(Auth::user()->pseudo == $user)
      <h2>{{ trans('general.page.myprofile') }}</h2>
      <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ trans('profile.midtitle',array('display'=>$user->display)) }}</h3>
            </div>
            <div class="panel-body">
                <b>{{ trans('profile.pseudolb') }}</b> : {{{ $user->pseudo }}}<br />
                <b>{{ trans('profile.displaylb') }}</b> : {{{ $user->display }}}<br />
                <b>{{ trans('profile.emaillb') }}</b> : {{{ $user->email }}}<br />
                <b>{{ trans('profile.registerlb') }}</b> : {{ $user->created_at }}<br />
            </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">{{ trans('profile.righttitle') }}</h3>
            </div>
            <div class="panel-body">
              <a href="{{ URL::to('recover') }}">{{ trans('profile.changepw') }}</a>
            </div>
        </div>
      </div>
    @else
      <h2>{{ trans('general.page.profile',array('name'=>$user->display)) }}</h2>
        <div class="col-md-8">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h3 class="panel-title">{{ trans('profile.midtitle',array('display'=>$user->display)) }}</h3>
              </div>
              <div class="panel-body">
                  <b>{{ trans('profile.pseudolb') }}</b> : {{{ $user->pseudo }}}<br />
                  <b>{{ trans('profile.displaylb') }}</b> : {{{ $user->display }}}<br />
                  <b>{{ trans('profile.emaillb') }}</b> : {{{ $user->email }}}<br />
                  <b>{{ trans('profile.registerlb') }}</b> : {{ $user->created_at }}<br />
              </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">{{ trans('profile.righttitle') }}</h3>
              </div>
              <div class="panel-body">
                <a href="#">{{ trans('profile.addfriend') }}</a>
              </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@stop
