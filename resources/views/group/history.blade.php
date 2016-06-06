@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <h3>{{{ $group->name }}} - {{ trans('groups.notifications_title') }}</h3>
            @foreach($notifications as $a)
                <p>{!! $a !!}</p>
            @endforeach
        </div>
    </div>
@stop