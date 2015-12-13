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
        <a href="" class="btn btn-default">{{ trans('groups.join_button') }}</a>
      </div>

      <div class="col-md-6">
        <h3>{{ trans('groups.create_title') }}</h3>
        <p>{{ trans('groups.create_text') }}</p>
        <a href="" class="btn btn-default">{{ trans('groups.create_button') }}</a>
      </div>
  </div>
@stop