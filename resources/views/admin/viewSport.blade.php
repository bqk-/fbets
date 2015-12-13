@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Sport</h2>

            <div class="col-md-8 admin">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{{ $sport->name }}}</h3>
                    </div>
                    <div class="panel-body">
                        Id: {{ $sport->id }}<br />
                        Name: {{ $sport->name }}<br />
                        Logo: <img src="{{ App\Helpers\ViewHelper::getImagePathFromId($sport->logo) }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop