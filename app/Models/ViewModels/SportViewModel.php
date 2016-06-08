<?php namespace App\Models\ViewModels;

class SportViewModel
{
    public $Id;
    public $Name;
    public $Logo;
    
    public function __construct($id, $name, $logo)
    {
        $this->Id = $id;
        $this->Name = $name;
        $this->Logo = $logo;
    }
}
