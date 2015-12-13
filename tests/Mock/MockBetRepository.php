<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockBetRepository implements \App\Repositories\Contracts\IBetRepository
{
    public function Create($score1, $score2, $idGame, $userId) {
        
    }

    public function Get($id) {
        
    }

    public function GetAllForUser($id) {
        
    }

    public function GetTopBettors($min, $max) {
        
    }

    public function GetUserIncomingBets($id, $days = 0) {
        
    }

}