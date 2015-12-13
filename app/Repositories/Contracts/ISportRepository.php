<?php namespace App\Repositories\Contracts;

interface ISportRepository
{

    public function Create($name, $idLogo);

    public function GetAll();

    public function GetSports();
    
    public function Get($id);
}