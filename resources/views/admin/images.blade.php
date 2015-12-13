@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-md-12">
      <h2>Bibliotheque Images</h2>

      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Bibliotheque Images</h3>
            </div>
            <div class="panel-body">
              <div class="list-group">
              @foreach($images as $i)
                <img src="{{ App\Helpers\ViewHelper::getImagePathFromId($i) }}" />
              @endforeach
              </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Images manquantes</h3>
            </div>
            <div class="panel-body">
              <div class="list-group">
              {!! Form::open(array('url' => 'admin/images')) !!}
              @foreach($games1 as $g)
                {{ $g->team1 }}<input type="text" name="url[{{ base64_encode($g->team1) }}]" /><br />
              @endforeach
              @foreach($games2 as $g)
                {{ $g->team2 }}<input type="text" name="url[{{ base64_encode($g->team2) }}]" /><br />
              @endforeach
              <input type="submit" value="Ajouter" class="text-center" />
              {!! Form::close() !!}
              </div>
            </div>
      </div>
  </div>

@stop