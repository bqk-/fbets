<?php namespace App\Models\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of BetRates
 *
 * @author thibault
 */
class BetRates
{
    private $GameId,
            $HomeRate,
            $VisitRate,
            $DrawRate;
    
    public function __construct($id, $home, $visit, $draw)
    {
        if($home > 1)
        {
            throw new \App\Exceptions\InvalidArgumentException('home', $home);
        }
        
        if($visit > 1)
        {
            throw new \App\Exceptions\InvalidArgumentException('visit', $visit);
        }
        
        if($draw > 1)
        {
            throw new \App\Exceptions\InvalidArgumentException('draw', $draw);
        }
        
        if($home + $visit + $draw > 1)
        {
            throw new \App\Exceptions\InvalidOperationException('Cannot have sum rate > 1');
        }
        
        $this->HomeRate = $home;
        $this->VisitRate = $visit;
        $this->DrawRate = $draw;
        $this->GameId = $id;
    }
}
