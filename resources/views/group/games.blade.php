@extends('layouts.default')
@section('content')
        <h2>{{ trans('groups.view_title') }}</h2>
        <div class="row">
                <h3>{{{ $model->Group->name  }}}</h3>
                <ul class="list-group">
                @foreach($model->Games as $game)
                <li class="list-group-item" style="border:0">
                  <div class="row">

                          @if($game->UserStatus == \App\Models\Types\GameStates::NONE)
                            <a href="{{ URL::to('bet/' .  $game->Id . '/' . 
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
                      
                            <a href="{{ URL::to('bet/' .  $game->Id . '/' . 
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
                          
                        <a href="{{ URL::to('bet/' .  $game->Id . '/' . 
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
                        <img style="width: 32px; height: 32px;" src="{{ $game->Sport->Logo }}" alt="Sport" />
                        {{ $game->Sport->Name }}
                    </div>
                    <div class="col-md-1">
                        
                        <p class="text-center pull-right">
                            <small>
                            {{ \App\Helpers\DateHelper::sqlDateToHourOnly($game->Date) }}
                            </small>
                        </p>
                        
                    </div>
                    
                  </div>
                </li>
                
                @endforeach
                </ul>
        </div>
@stop