@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Administration</h2>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Menu</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="{{ URL::to('admin/view-championships') }}" class="list-group-item">Championnats</a>
                            <a href="{{ URL::to('admin/view-sports') }}" class="list-group-item">Sports</a>
                            <a href="{{ URL::to('admin/enter-results') }}" class="list-group-item">Entrer des résultats</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Raccourcis</h3>
                    </div>
                    <div class="panel-body">
                        <a href="#" class="list-group-item">Changer nom</a>
                        <a href="{{ URL::to('admin/new-game') }}" class="list-group-item">Ajouter événement unique</a>
                        <a href="{{ URL::to('admin/new-champ') }}" class="list-group-item">Ajouter championnat</a>
                        <a href="{{ URL::to('admin/new-sport') }}" class="list-group-item">Ajouter sport</a>
                        <a href="{{ URL::to('admin/controller') }}" class="list-group-item">Testeur de fonction</a>
                        <a href="#" class="list-group-item">Autre</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Divers</h3>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Images</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="{{ URL::to('admin/images') }}" class="list-group-item">Bibliotheque Images</a>
                            <a href="{{ URL::to('admin/mailsender') }}" class="list-group-item">Envoyer newsletter</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Utilisateurs</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="{{ URL::to('admin/mailsender') }}" class="list-group-item">Envoyer newsletter</a>
                            <a href="{{ URL::to('admin/suggestions') }}" class="list-group-item">Voir suggestions @if($suggestions>0) ({{ $suggestions }} nouv.) @endif</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Information</h4>
                </div>
                <div class="modal-body">
                    <p class="ajax-modal"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@stop