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
        </div>
@stop