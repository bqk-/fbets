@extends('layouts.default')
@section('content')
  <div class="row">
    <h2>{{ trans('general.page.top') }}</h2>
      <div class="col-md-12">
      <ul class="nav nav-tabs">
          <li class="default"><a href="#default" data-toggle="tab" aria-expanded="true">0-9</a></li>
          <li class=""><a href="#10" data-toggle="tab" aria-expanded="false">10-24</a></li>
          <li class=""><a href="#25" data-toggle="tab" aria-expanded="false">25-49</a></li>
          <li class=""><a href="#50" data-toggle="tab" aria-expanded="false">50-99</a></li>
          <li class=""><a href="#100" data-toggle="tab" aria-expanded="false">100-499</a></li>
          <li class=""><a href="#500" data-toggle="tab" aria-expanded="false">500-999</a></li>
          <li class=""><a href="#1000" data-toggle="tab" aria-expanded="false">1000-9999</a></li>
          <li class=""><a href="#10000" data-toggle="tab" aria-expanded="false">10000+</a></li>
        </ul>

        <div class="tab-content">
          @foreach($pages as $page)
                <div class="tab-pane fade" id="{{ $page['name'] }}">
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
                        @if($page['users'] != null)
                            @foreach($page['users'] as $user)
                                <tr @if(Auth::check() && $user->id == Auth::User()->id) class="info" @endif >
                                    <td>{{ $p++ }}</td>
                                    <td>{{ $user->pseudo }}</td>
                                    <td><div class="progress progress-striped" style="margin-top:6px;margin-bottom:-6px;">
                                            <div class="progress-bar progress-bar-success" style="width:{{ $user->percent*100 }}%"></div>
                                        </div></td>
                                    <td>{{ $user->percent*100 }}%</td>
                                    <td>{{ $user->points }}</td>
                                    <td>{{ $user->nb }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
          @endforeach
        </div>

      </div>
  </div>
@stop