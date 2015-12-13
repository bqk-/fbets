@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Reload games</h2>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{{ $championship->name }}}</h3>
                </div>
                <div class="panel-body">
                    Enter parameters for constructor:
                    {!! Form::open(array('url' => 'admin/reload-games', 'role' => 'form')) !!}
                    <input name="championship" type="hidden" value="{{ $championship->id }}" />
                    <?php $k = 0; ?>
                    @foreach($params as $p)
                        {{ $p->name }}: {!! Form::text('param[]', $dbParams == null ? null : $dbParams[$k++]) !!}<br />
                    @endforeach
                    <input class="btn btn-success" type="submit" value="Submit" />
                    <a class="btn btn-danger" href="{{ URL::to('/admin') }}">Cancel</a>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop