@extends('layouts.default')
@section('content')
        <h2>{{ trans('groups.view_title') }}</h2>
        <div class="row">
            <div class="col-md-8">
                <h3>{{{ $group->name  }}}</h3>
                <code>
                    {{ dd($games) }}
                </code>
            </div>
            <div class="col-md-4">
                {{ trans('groups.howto_add') }}
            </div>
        </div>
@stop