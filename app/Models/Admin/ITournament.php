<?php

namespace App\Models\Admin;


interface ITournament {
    function getGames();

    function getGameTime($team1, $team2);

    function getScore($team1, $team2);

    function getTeams();
}