<?php namespace App\Models\Services;

class UserBrief
{
    public $Id;
    public $Pseudo;
    
    public function __construct($id, $name)
    {
        $this->Id = $id;
        $this->Pseudo = $name;
    }
}

