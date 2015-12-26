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
 </head>
 
 <body>
  <div class="container-fluid  maincont">
    <div class="fold col-sm-2">
     
    </div>  
  </div>  
 
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