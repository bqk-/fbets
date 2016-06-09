@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>{{{ $model->Group->name }}} - {{ trans('groups.polls_title') }}</h3>
            <h2>{{ trans('groups.polls_active') }}</h2>
            @foreach($model->ActivesPolls as $p)
                <div class="row">
                    <div class="col-md-5">{{ $p->Text }}</div>
                    <div class="col-md-5">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" 
                                 style="width: {{$p->Status->Yes / $p->Status->Total * 100}}%">
                            </div>
                            <div class="progress-bar progress-bar-danger" 
                                 style="width: {{$p->Status->No / $p->Status->Total * 100}}%">
                            </div>
                        </div>
                    </div>
                    @if($p->MyVote == \App\Models\Types\VoteTypes::DONTCARE)
                        <div class="col-md-1">
                            <a href="{{ URL::to('group/polls/' . $model->Group->id . '/accept/' . $p->Id) }}">
                                <span class="btn btn-success glyphicon glyphicon-ok"></span>
                            </a>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ URL::to('group/polls/' . $model->Group->id . '/refuse/' . $p->Id) }}">
                                <span class="btn btn-danger glyphicon glyphicon-remove"></span>
                            </a>
                        </div>
                    @else
                        <div class="col-md-2 text-center">
                            @if($p->MyVote == \App\Models\Types\VoteTypes::YES)
                                <span class="text-success glyphicon glyphicon-ok"></span>
                            @else
                                <span class="text-danger glyphicon glyphicon-remove"></span>
                            @endif
                        </div>
                    @endif
                   
                </div>
            @endforeach
           
            
            <h2>{{ trans('groups.polls_expired') }}</h2>
  
            
           @foreach($model->ExpiredPolls as $p)
                <div class="row">
                    <div class="col-md-5">{{ $p->Text }}</div>
                    <div class="col-md-5">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" 
                                 style="width: {{$p->Status->Yes / $p->Status->Total * 100}}%">
                            </div>
                            <div class="progress-bar progress-bar-danger" 
                                 style="width: {{$p->Status->No / $p->Status->Total * 100}}%">
                            </div>
                        </div>
                    </div>
                    @if($p->MyVote == \App\Models\Types\VoteTypes::DONTCARE)
                        <div class="col-md-2">
                            
                            {{trans('polls.novote')}}
                            
                        </div>
                    @else
                        <div class="col-md-1">
                            @if($p->MyVote == \App\Models\Types\VoteTypes::YES)
                                <span class="text-success glyphicon glyphicon-ok"></span>
                            @else
                                -
                            @endif
                        </div>
                        <div class="col-md-1">
                            @if($p->MyVote == \App\Models\Types\VoteTypes::NO)
                                <span class="text-danger glyphicon glyphicon-remove"></span>
                            @else
                                -
                            @endif
                        </div>
                    @endif
                   
                </div>
            @endforeach

        </div>
    </div>
@stop