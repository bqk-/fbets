@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Liste sports</h2>

            <div class="col-md-8 admin">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Liste sports</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach($sports as $s)
                                <li class="list-group-item">
                                    <img src="{{ App\Helpers\ViewHelper::getImagePathFromId($s->logo) }}" />
                                    <a href="{{ URL::to('admin/view-sport') }}/{{ $s->id }}">{{ $s->name
                                    }}</a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop