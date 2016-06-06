@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <h3>{{{ $group->name  }}} - {{ trans('groups.apply_title') }}</h3>
            <p>
                {!! Form::open(array('url' => 'group/apply', 'class' => 'accept-autocomplete', 'data-path' => 'recommand')) !!}
                {!! Form::hidden('id_group', $group->id) !!}
                {!! Form::textarea('message', null, array('class' => 'form-control', 'placeholder' => trans('forms.gapply_message_ph'))) !!}
                <input type="submit" value="{{ trans('forms.apply') }}" class="ajax btn-sm btn-default" />
                {!! Form::close() !!}
            </p>
        </div>
    </div>
@stop
