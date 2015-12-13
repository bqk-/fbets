@extends('layouts.default')
@section('content')
  <div class="row">
    <h2>Testeur de fonction</h2>
      <div class="col-md-12">
      @if($rc)
          <?php
            foreach ($rc->getMethods() as $m) {
              $k=0;
              echo '<form method="POST" action="controller">';
              echo '<input type="hidden" name="method" value="'.$m->name.'" />';
              echo $m->name.' : ';
              if(!empty($m->getParameters()))
                foreach ($m->getParameters() as $p) {
                    echo '<input type="text" name="param['.$k++.']" />';
                }
              else
                echo 'Pas de paramètre';
              echo '<input type="submit" value="GO" />';
              echo '</form>';
              echo '<br />';
            }
          ?>
        @else
         <pre>
          {{ $return }}
          </pre>
        @endif
      </div>
  </div>
@stop