<?php

namespace App\Models\Admin\Tournament;


interface ITournament {
    function getGames();

    function getGameTime($extIdGame);

    function getScore($extIdGame);

    function getTeams();
    
    function getGameStateFromScore($scoreH, $scoreV);
    
    function getType();
}