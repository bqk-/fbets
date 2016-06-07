@extends('layouts.default')
@section('content')
  <div class="row">
    <h2>{{ trans('general.page.top') }}</h2>
      <div class="col-md-12">

        <table class="table table-striped table-hover ">
            <thead>
            <tr>
                <th>{{ trans('general.positionthead') }}</th>
                <th>{{ trans('general.pseudothead') }}</th>
                <th>{{ trans('general.percentthead') }}</th>
                <th></th>
                <th>{{ trans('general.pointsthead') }}</th>
                <th>{{ trans('general.betsthead') }}</th>
            </tr>
            </thead>
            <tbody>
            <?php $p = 1; ?>
                @foreach($users as $user)
                    <tr @if(Auth::check() && $user->id == Auth::User()->id) class="info" @endif >
                        <td>{{ $p++ }}</td>
                        <td>{{ $user->display }}</td>
                        <td><div class="progress progress-striped" style="margin-top:6px;margin-bottom:-6px;">
                                <div class="progress-bar progress-bar-success" style="width:{{ $user->w / ($user->w + $user->l) * 100 }}%"></div>
                            </div></td>
                        <td>{{ $user->w / ($user->w + $user->l) * 100 }}%</td>
                        <td>{{ $user->points }}</td>
                        <td>{{ $user->w + $user->l }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
          
      </div>
  </div>
@stop