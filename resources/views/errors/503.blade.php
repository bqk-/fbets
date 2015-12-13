<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>{{ Lang::get('general.site_title') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
 
  <!-- Le styles -->
  <link href="{{ URL::to('/') }}/css/bootstrap.css" type="text/css" rel="stylesheet">  
  <link href="{{ URL::to('/') }}/css/style.css" type="text/css" rel="stylesheet"> 
 </head>
 
 <body style="background-color:#ecf0f1;">
  <div class="navbar navbar-default">
     <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">{{ trans('general.header_title') }}</a>
    </div>
  </div>
 
  <div id="main" class="container">
 
    <div class="jumbotron maintenance" style="background-color:white;">
      <h1>{{ trans('maintenance.mid_title') }}</h1>
      <br />
      <p>{{ trans('maintenance.message') }}</p>
      <br />
      <div class="progress progress-striped active">
        <div class="progress-bar" style="width: 0%"></div>
      </div>
      <p class="text-center text-warning">
        0%
      </p>
    </div>

  <footer>
     <div class="row">
        <div class="col-md-12">
            <div>
                <p>Miclo Thibault - 2014</p>
            </div>
        </div>
    </div>
</footer>
  </div> <!-- /container -->
  <!-- Le javascript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="{{ URL::to('/') }}/js/bootstrap.min.js"></script>
  <script type="text/javascript">
  $(function(){
      setInterval(function update() {
        $.ajax({
          type:'GET',
          url:'{{ URL::to('ajax/progress') }}',
          success:function(data){
              $('.maintenance').find('.text-center').html(data+'%');
              $('.maintenance').find('.progress-bar').css('width',data+'%');
              if(parseInt(data)==100)
                location.reload(true);
          }
        });
      },1000);
  });
  </script>
 </body>
</html>