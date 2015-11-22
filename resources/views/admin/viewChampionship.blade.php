@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Event</h2>

            <div class="col-md-8 admin">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{{ $championship->name }}}</h3>
                    </div>
                    <div class="panel-body">
                        Id: {{ $championship->id }}<br />
                        Name: {{ $championship->name }}<br />
                        <br />
                        <a href="{{ URL::to('admin/view-games/' . $championship->id) }}" >View games</a>
                        <a href="{{ URL::to('admin/drop-games/' . $championship->id) }}" >Drop games</a>
                        <a href="{{ URL::to('admin/reload-games/' . $championship->id) }}" >Reload games</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop