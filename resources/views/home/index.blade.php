@extends('layouts.default')
@section('content')
  <div class="row">
    @if(Auth::check())
      <div class="col-md-12">
      @if(!$games->isEmpty())
        {!! Form::open(array('url' => '/', 'role' => 'form')) !!}
        <?php $date = null; $notFirst = false; $needBtn = false; ?>
        @foreach($model->Games as $game)
          @if($date != explode(' ',$game->Date)[0])
            @if($notFirst)
                        </ul>
            @else
              <?php $notFirst = true; ?>
            @endif
              <h3>
                 {{ \App\Helpers\DateHelper::sqlDateToStringOnlyDate($game->Date) }}
              </h3>
              <ul class="list-group">
          @endif
                <li class="list-group-item" style="border:0">
                  <div class="row">

                          @if($game->UserStatus > 0)
                            <a href="{{ URL::to('bet/' .  $game->id . '/' . 
                                        \App\Models\Types\GameStates::HOME) }}">
                                  <div class="col-md-2 team" data-team="{{ $game->Team1->Id }}">
                                    <div class="row">
                                      <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ $game->Team1->Logo }}"
                                         alt="{{ $game->Team1->Name }}" /> </div>
                                    <div class="col-md-8">{{ $game->Team1->Name }} <br> 
                                        <small><span class="glyphicon glyphicon-stats"></span>
                                            {{ $game->Rates->HomeRate }}</small>
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
                                                    {{ $game->Rates->DrawRate }}</small>
                                        </small>
                                    </p>
                                </div>
                            </a>
                          
                        <a href="{{ URL::to('bet/' .  $game->id . '/' . 
                                  \App\Models\Types\GameStates::VISITOR) }}">
                            <div class="col-md-2 team" data-team="{{ $game->Team2->Id }}">
                                <div class="row text-right">
                                    <div class="col-md-8">{{ $game->Team2->Name }} <br> 
                                        <small><span class="glyphicon glyphicon-stats"></span>
                                            {{ $game->Rates->VisitRate }}</small>
                                    </div>
                                    <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ $game->Team2->Logo }}"
                                         alt="{{ $game->Team2->Name }}" /></div>
                                </div>
                            </div>
                          </a>
                          @else
                            <div class="col-md-2 
                                 {{ $game->UserStatus == \App\Models\Types\GameStates::HOME ? 'bet-checked' : '' }}">
                                <div class="row">
                                    <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ $game->Team1->Logo }}"
                                                               alt="{{ $game->Team1->Name }}" /> </div>
                              <div class="col-md-8">{{ $game->Team1->Name }} <br> 
                                  <small><span class="glyphicon glyphicon-stats"></span>
                                      {{ $game->Rates->HomeRate }}</small>
                              </div>
                                </div>
                            </div>

                            <div class="col-md-2 bets-input 
                                 {{ $game->UserStatus == \App\Models\Types\GameStates::DRAW ? 'bet-checked' : '' }}">
                                <p class="text-center">
                                    <small class="text-muted">
                                         {{ trans('general.draw') }} <br> 
                                         <small><span class="glyphicon glyphicon-stats"></span>
                                             {{ $game->Rates->DrawRate }}</small>
                                    </small>
                                </p>
                            </div>
                          
                            <div class="col-md-2 
                                 {{ $game->UserStatus == \App\Models\Types\GameStates::VISITOR ? 'bet-checked' : '' }}">
                                <div class="row">
                                    <div class="col-md-8 text-right">{{ $game->Team2->Name }} <br> 
                                        <small><span class="glyphicon glyphicon-stats"></span>
                                            {{ $game->Rates->VisitRate }}</small>
                                    </div>
                                    <div class="col-md-4"><img style="width: 32px; height: 32px;" src="{{ $game->Team2->Logo }}"
                                         alt="{{ $game->Team2->Name }}" /></div>
                                </div>
                            </div>
                          @endif

                    <div class="col-md-3 text-center">
                        <img style="width: 32px; height: 32px;" src="{{ $game->Team2->Name }}" alt="Sport" />
                        {{ $game->Sport->Name }}
                    </div>
                    <div class="col-md-1">
                        <small>
                            <p class="text-center pull-right">
                                {{ \App\Helpers\DateHelper::sqlDateToHourOnly($game->Date) }}
                            </p>
                        </small>
                    </div>
                    <div class="col-md-1">
                        @if(Auth::User()->groups()->count() > 0)
                        <div class="btn-group">
                            <button type="button" style="width: 50px;" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              + <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach(Auth::User()->groups as $g)
                                    <li><a title="{{trans('general.addtogroup')}}" 
                                           href="{{URL::to('group/games/suggest/' . $game->Id)}}">{{$g->name}}</a></li>
                                @endforeach
                            </ul>
                          </div>
                        @endif
                    </div>
                  </div>
                </li>
                  <?php $date = explode(' ',$game->Date)[0]; ?>
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
         <a href="{{ URL::to('register') }}"><button class="btn btn-info btn-lg big-button">{{ trans('general.register') }}</button></a>
      </div>

      <div class="col-md-4 col-md-offset-2 registerHome">
        <a href="{{ URL::to('login') }}"><button class="btn btn-info btn-lg big-button">{{ trans('general.login') }}</button></a>
      </div>

    </div>
    @endif
  </div>
@stop