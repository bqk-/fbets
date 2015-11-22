@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <h3>{{{ $group->name  }}} - {{ trans('groups.request_title') }}</h3>
            @foreach($applications as $a)
            <p>{{{ $a->pseudo }}} - {{ $a->message }}</p>
            @endforeach
        </div>
    </div>
@stop