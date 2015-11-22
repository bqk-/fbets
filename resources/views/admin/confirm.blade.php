@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Confirm action</h2>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        Confirm action: <strong>{{ $action }}</strong>?
                        {!! Form::open(array('url' => 'admin/' . $action, 'role' => 'form')) !!}
                            <input name="hidden" type="hidden" value="{{ $hidden }}" />
                            <input class="btn btn-success" type="submit" value="Yes" />
                            <a class="btn btn-danger" href="{{ URL::to('/admin') }}">No</a>
                        {!! Form::close() !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop