<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>{{ Lang::get('general.site_title') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
    <link href="{{ URL::to('/') }}/css/bootstrap.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/css/style.css" type="text/css" rel="stylesheet"> 
    <link href="{{ URL::to('/') }}/css/app.css" type="text/css" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Lobster|Jura|Spinnaker' rel='stylesheet' type='text/css'>
 </head>
 
 <body>
  <div class="container-fluid  maincont">
    <div class="fold col-xs-1">
      <div class="portrait">
        <img class="img-circle center-block" src="{{ URL::to('/') }}/css/images/hibou.jpg">
        <p class="name">Isaac Hibou <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></p> 
        
      </div> 
      <div class="mainmenu">
        <ul class="list">
          <a class="home" href="#"><li><span class="glyphicon glyphicon-home" aria-hidden="true"></span>home</li></a>
          <a class="bets" href="#"><li><a class="bets" href="#"><span class="glyphicon glyphicon-euro" aria-hidden="true"></span>bets</li></a>
          <a class="leagues" href="#"><li><a class="leagues" href="#"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>leagues</li></a>
          <a class="leagues" href="#"><li><a class="leagues" href="#"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span>whatever</li></a>
          <a class="leagues" href="#"><li><a class="leagues" href="#"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>hot stuff</li></a>
        </ul>
      </div> 
    </div>  
  </div>

  <script src="{{ URL::to('/') }}/js/jquery-1.10.2.min.js"s></script>
  <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
  <script src="{{ URL::to('/') }}/js/bootstrap.min.js"></script>
  <script src="{{ URL::to('/') }}/js/masonry.js"></script>
  <script type="text/javascript" src="{{ URL::to('/') }}/js/isaac.js"></script>
  @if(App\Http\Controllers\AdminController::isAdmin())
    <script src="{{ URL::to('/javascript/admin') }}"></script>
  @endif
 </body>
</html>