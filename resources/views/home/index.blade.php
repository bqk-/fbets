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

                          @if(!array_key_exists($game->id, $bets))
                            <a href="{{ URL::to('bet/' .  $game->id . '/' . 
                                        \App\Models\Types\GameStates::HOME) }}">
                                  <div class="col-md-2 team" data-team="{{ $game->team1()->first()->id }}">
                                    <div class="row">
                                      <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->team1()->first()->logo) }}"
                                         alt="{{ $game->team1()->first()->name }}" /> </div>
                                    <div class="col-md-8">{{ $game->team1()->first()->name }} <br> 
                                        <small><span class="glyphicon glyphicon-stats"></span>
                                            {{ $rates[$game->id]->HomeRate }}</small>
                                    </div>
                                  </div>
                                </div>
                              </a>
                      
                            <a href="{{ URL::to('bet/' .  $game->id . '/' . 
                                        \App\Models\Types\GameStates::DRAW) }}">
                                <div class="col-md-2 bets-input team">
                                    <p class="text-center">
                                        <small class="text-muted">
                                                {{ trans('general.draw') }} <br> 
                                                <small><span class="glyphicon glyphicon-stats"></span>
                                                    {{ $rates[$game->id]->DrawRate }}</small>
                                        </small>
                                    </p>
                                </div>
                            </a>
                          
                        <a href="{{ URL::to('bet/' .  $game->id . '/' . 
                                  \App\Models\Types\GameStates::VISITOR) }}">
                            <div class="col-md-2 team" data-team="{{ $game->team2()->first()->id }}">
                                <div class="row text-right">
                                    <div class="col-md-8">{{ $game->team2()->first()->name }} <br> 
                                        <small><span class="glyphicon glyphicon-stats"></span>
                                            {{ $rates[$game->id]->VisitRate }}</small>
                                    </div>
                                    <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->team2()->first()->logo) }}"
                                         alt="{{ $game->team2()->first()->name }}" /></div>
                                </div>
                            </div>
                          </a>
                          @else
                            <div class="col-md-2 
                                 {{ $bets[$game->id]->bet == \App\Models\Types\GameStates::HOME ? 'bet-checked' : '' }}">
                                <div class="row">
                                    <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->team1()->first()->logo) }}"
                                                               alt="{{ $game->team1()->first()->name }}" /> </div>
                              <div class="col-md-8">{{ $game->team1()->first()->name }} <br> 
                                  <small><span class="glyphicon glyphicon-stats"></span>
                                      {{ $rates[$game->id]->HomeRate }}</small>
                              </div>
                                </div>
                            </div>

                            <div class="col-md-2 bets-input 
                                 {{ $bets[$game->id]->bet == \App\Models\Types\GameStates::DRAW ? 'bet-checked' : '' }}">
                                <p class="text-center">
                                    <small class="text-muted">
                                         {{ trans('general.draw') }} <br> 
                                         <small><span class="glyphicon glyphicon-stats"></span>
                                             {{ $rates[$game->id]->DrawRate }}</small>
                                    </small>
                                </p>
                            </div>
                          
                            <div class="col-md-2 
                                 {{ $bets[$game->id]->bet == \App\Models\Types\GameStates::VISITOR ? 'bet-checked' : '' }}">
                                <div class="row">
                                    <div class="col-md-8 text-right">{{ $game->team2()->first()->name }} <br> 
                                        <small><span class="glyphicon glyphicon-stats"></span>
                                            {{ $rates[$game->id]->VisitRate }}</small>
                                    </div>
                                    <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->team2()->first()->logo) }}"
                                         alt="{{ $game->team2()->first()->name }}" /></div>
                                </div>
                            </div>
                          @endif

                    <div class="col-md-3 text-center">
                        <img style="width: 32px; height: 32px;" src="{{ \App\Helpers\ViewHelper::getImagePathFromId($game->championship()->first()->sport()->first()->logo) }}" alt="Sport" />
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
        <div class="bet-button"><input class="btn btn-info center-block" type="submit" value="{{ trans('forms.validatebet') }}" /></div>
        @endif
        {!! Form::close() !!}
      @else
        {{ trans('general.nogames') }}
      @endif
      </div>
    @else
    <div id="Welcome" class="col-md-offset-2 col-md-8">
      <h1>{{ trans('general.hometitle') }}</h1>
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