<?php namespace App\Models\Admin\TournamentClasses;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Team
{
    public $Id,
            $Name,
            $LogoUrl;
    
    public function __construct($id, $name, $url)
    {
        $this->Id = $id;
        $this->Name = $name;
        $this->LogoUrl = $url;
    }
}