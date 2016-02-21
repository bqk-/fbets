<?php namespace App\Services\Contracts;

interface IImageService
{
    public function Get($id);

    public function GetAllImages();

    public function UploadLogo($url);

    public function GetImagePath($id);

    public function GuessLogo($team);
}
