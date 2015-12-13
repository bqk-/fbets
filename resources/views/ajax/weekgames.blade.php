 <ul class="list-group">
          <?php $needBtn = false; ?>
          @foreach(App\Models\Game::where('week','=',$week)->where('id_championship','=',$champ)->orderBy('date')->get() as $game)
              <li class="list-group-item">
                <div class="row">
                  <div class="col-md-4"><img src="{{ $game->plogo1() }}" alt="{{ $game->team1 }}" /> {{ $game->team1 }}</div>
                  <div class="col-md-4"><p class="text-center">
                  @if($game->timestamp() < time())
                      @if($game->getScore())
                        <strong>{{ $game->getScore()[0] }} - {{ $game->getScore()[1] }}</strong><br />
                      @else
                        <strong>{{ trans('general.noresult') }}</strong><br />
                      @endif
                    @if($game->getBet())
                      <small>{{ trans('general.yourbet') }}<br />
                      {{$game->getBet()->score1}} - {{$game->getBet()->score2}} {{ $game->echoPoints() }}</small>
                    @else
                      <small>{{ trans('general.nobet') }}</small>
                    @endif
                  @elseif($game->getBet())
                    <strong>{{ trans('general.notplayed') }}</strong><br />
                    <small>{{ trans('general.yourbet') }}<br />
                    {{$game->getBet()->score1}} - {{$game->getBet()->score2}}</small>
                  @else
                     <strong>{{ trans('general.notplayed') }}</strong><br />
                      <small>{{ trans('general.yourbet') }}<br />
                      <?php $needBtn = true; ?>
                    <input type="number" min="0" class="text-center" name="games[{{$game->id}}][team1]" id="games[{{$game->id}}][team1]" /> - <input type="number" min="0" class="text-center" name="games[{{$game->id}}][team2]" id="games[{{$game->id}}][team2]" /></p>
                    </small>
                  @endif

                  </div>
                  <div class="col-md-4"><p class="text-right">{{ $game->team2 }} <img src="{{ $game->plogo2() }}" alt="{{ $game->team2 }}" /></p></div>
                  </div>
                <div class="row">
                  <div class="col-md-12"><p class="text-center">{{ $game->dateToString() }}</p></div>
                </div>
              </li>
          @endforeach
            </ul>
             @if($needBtn)
                <input class="btn btn-info col-md-4 col-md-offset-4" type="submit" value="{{ trans('forms.validatebet') }}" />
             @endif