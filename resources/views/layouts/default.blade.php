<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>{{ Lang::get('general.site_title') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

     <!-- link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui
     .css" /-->
     <link href="{{ URL::to('/') }}/css/bootstrap.css" rel="stylesheet">
     <!-- link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' -->
     <link href="{{ URL::to('/') }}/css/style.css" type="text/css" rel="stylesheet">
     <link href="{{ URL::to('/') }}/css/style-isaac.css" type="text/css" rel="stylesheet">
 </head>
 
 <body>
     <nav role="navigation">
        <a id="nav_trigger">
           <img id='menu' src="{{ URL::to('/') }}/css/images/menus/menux24.png">
        </a>
        <div id="navmenu">
                <div id="title" class="col-md-2">
                    <h1>{{ trans('general.header_title') }}</h1>
                </div>
        </div>
    </nav>

    <div id="usrpnl">
     @if(!Auth::check())
        <div id="profile" class="eph">
        <img id="idpic" src="{{ URL::to('/') }}/css/images/hibou.jpg">
            <h1 style="margin: 6.5px 0 0 5%;">{{ trans('general.myprofile') }}</h1>
            <div class="row">
                <ul class="pnlul col-md-offset-2 col-md-8">
                    <li class="pnli"><span class="glyphicon glyphicon-plus-sign icon"></span><a href="/register">{{ trans('general.register') }}</a></li>
                    <li class="pnli"><span class="glyphicon glyphicon-play icon"></span><a href="/login">{{ trans('general.login') }}</a></li>
                </ul>
            </div>
        </div>
        @else
        <div id="profile" class="eph">
        <img id="idpic" src="{{ URL::to('/') }}/css/images/hibou.jpg">
            <h1 style="margin: 6.5px 0 0 5%;">{{ Auth::user()->display }}</h1>
            <div class="row">
                <ul class="pnlul col-md-offset-2 col-md-8">
                    <li class="pnli"><span class="glyphicon glyphicon-user icon" aria-hidden="true"></span><a href="/user/{{ Auth::user()->pseudo }}">{{ trans('general.myprofile') }}</a></li>
                    <li class="pnli"><span class="glyphicon glyphicon-off icon" aria-hidden="true"></span><a href="/logout">{{ trans('general.logout') }}</a></li>
                </ul>
            </div>
        </div>
      @endif

        <div id="bets" class="eph">
            <h1>Bets</h1>
            <ul class="pnlist">
                <li class="pnli"><a href="/">{{ trans('general.home') }}</a></li>
                <li class="pnli"><a href="/ladder">{{ trans('general.top_bet') }}</a></li>
                <li class="pnli"><a href="/view">{{ trans('general.view') }}</a></li>
                <li class="pnli"><a href="/group">{{ trans('general.group') }}</a></li>
                <!--<li class="pnli"><a href="/suggest">{{ trans('general.suggest') }}</a></li>-->
            </ul>
        </div>

        <div id="param" class="eph">
            <h1>{{ trans('general.groups') }}</h1>
            <ul class="pnlist">
                @if(Auth::check())
                    @foreach(Auth::user()->groups as $g)
                        <li class="pnli"><a href="/group/view/{{ $g->id }}">{{{ $g->name }}}</a></li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
      
 
  <div id="main" class="container layer"> 
 
    @if(Session::has('success'))
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ Session::get('success') }}
    </div>    
    @endif
 
    @if(Session::has('error'))
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ Session::get('error') }}
    </div>    
    @endif
    @yield('content')

  <footer>
     <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div>
                <p class="text-center">{{ date('H:i', time()) }}</p>
            </div>
        </div>
    </div>
</footer>
  </div>
  <script src="{{ URL::to('/') }}/js/jquery-1.10.2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
  <script src="{{ URL::to('/') }}/js/bootstrap.min.js"></script>
  <script src="{{ URL::to('/') }}/js/masonry.js"></script>
  <script type="text/javascript" src="{{ URL::to('/') }}/js/isaac.js"></script>
  @if(App\Http\Controllers\AdminController::isAdmin())
    <script src="{{ URL::to('/javascript/admin') }}"></script>
  @endif
 </body>
</html>