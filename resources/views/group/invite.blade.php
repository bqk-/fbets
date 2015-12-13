@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-8">
            <h3>{{{ $group->name  }}} - {{ trans('groups.propose_title') }}</h3>
            <p>
                {!! Form::open(array('url' => 'group/recommand', 'class' => 'accept-autocomplete', 'data-path' => 'recommand')) !!}
                {!! Form::text('users', null, array('class' => 'form-control autocomplete', 'data-type' => 'list', 'data-target' => 'users_actual', 'placeholder' => trans('forms.gnames_ph'))) !!}
            <p class="users_actual"></p>
            {!! Form::hidden('ids', '[]') !!}
            {!! Form::hidden('id_group', Request::segment(3)) !!}
            {!! Form::textarea('message', null, array('class' => 'form-control', 'placeholder' => trans('forms.ginvite_message_ph'))) !!}
            <input type="submit" value="{{ trans('forms.recommand') }}" class="ajax btn-sm btn-default" />
            {!! Form::close() !!}
            </p>
        </div>
    </div>
@stop
