@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Events list</h2>

            <div class="col-md-8 admin">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Events list</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach($championships as $c)
                                <li class="list-group-item">
                                    @if($c->active == 1)
                                        <span class="glyphicon glyphicon-ok-circle text-success active" data-id="{{ $c->id }}"></span>
                                    @else
                                        <span class="glyphicon glyphicon-remove-circle text-danger active" data-id="{{ $c->id }}"></span>
                                    @endif
                                    <a href="{{ URL::to('admin/view-championship') }}/{{ $c->id }}">{{ $c->name }}</a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop