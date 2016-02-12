@extends('layouts.default')
@section('content')
  <div class="row">
    @if(Auth::check())
      <div class="col-md-12">
      @if(!$games->isEmpty())
        {!! Form::open(array('url' => '/', 'role' => 'form')) !!}
        <?php $date = null; $notFirst = false; $needBtn = false; ?>
        @foreach($games as $game)
          @if($date != explode(' ',$game->date)[0])
            @if($notFirst)
                        </ul>
            @else
              <?php $notFirst = true; ?>
            @endif
              <h3>
                 {{ \App\Helpers\DateHelper::sqlDateToStringOnlyDate($game->date) }}
              </h3>
              <ul class="list-group">
          @endif
                <li class="list-group-item" style="border:0">
                  <div class="row">
                    <div class="col-md-2" style="padding:0;">
                        <img src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->team1()->first()->logo) }}"
                             alt="{{ $game->team1()->first()->name }}" /> {{ $game->team1()->first()->name }}
                    </div>
                    <div class="col-md-2 bets-input">
                        <p class="text-center">

                          @if(\App\Helpers\DateHelper::getTimestampFromSqlDate($game->date) > time() && !array_key_exists($game->id, $bets))
                            <small>
                              <input type="text"
                                     min="0"
                                     class="text-center"
                                     name="games[{{$game->id}}][team1]"
                                     id="games[{{$game->id}}][team1]" />
                                -
                                <input
                                        type="text"
                                        min="0"
                                        class="text-center"
                                        name="games[{{$game->id}}][team2]"
                                        id="games[{{$game->id}}][team2]" />

                            </small>
                          <?php $needBtn = true; ?>
                          @elseif(\App\Helpers\DateHelper::getTimestampFromSqlDate($game->date) < time())
                            <small>{{ trans('general.noresultindex') }}</small><br />
                          @endif
                          @if(array_key_exists($game->id, $bets))
                            <small class="text-muted">{{ trans('general.yourbet') }}: {{$bets[$game->id]->score1}} -
                                {{$bets[$game->id]->score2}}
                            </small>
                          @endif
                        </p>
                    </div>
                    <div class="col-md-2" style="padding:0;">
                        <p class="text-right">{{ $game->team2()->first()->name }}
                            <img src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->team2()->first()->logo) }}"
                                 alt="{{ $game->team2()->first()->name }}" />
                        </p>
                    </div>
                    <div class="col-md-3 text-center">
                        <img src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->championship()->first()->sport()->first()->logo) }}" alt="Sport" />
                        {{ $game->name }}
                    </div>
                    <div class="col-md-2">
                        <small>
                            <p class="text-center pull-right">
                                {{ \App\Helpers\DateHelper::sqlDateToHourOnly($game->date) }}
                            </p>
                        </small>
                    </div>
                  </div>
                </li>
                  <?php $date = explode(' ',$game->date)[0]; ?>
        @endforeach
        @if($date != null)
            </ul>
        @endif
        @if($needBtn)
                <input class="btn btn-info center-block" type="submit" value="{{ trans('forms.validatebet') }}" />
        @endif
        {!! Form::close() !!}
      @else
        {{ trans('general.nogames') }}
      @endif
      </div>
    @else
    <div id="Welcome" class="col-md-offset-2 col-md-8">
      <h1 class="herotitle">{{ trans('general.brandtitle') }}</h1>
      <p>{{ trans('general.homemessage') }}</p>
      
      <div class="col-md-4 registerHome">
         <a href="{{ URL::to('register') }}"><button class="btn btn-info btn-lg">{{ trans('general.register') }}</button></a>
      </div>

      <div class="col-md-4 col-md-offset-2 registerHome">
        <a href="{{ URL::to('login') }}"><button class="btn btn-info btn-lg">{{ trans('general.login') }}</button></a>
      </div>

    </div>
    @endif
  </div>
@stop