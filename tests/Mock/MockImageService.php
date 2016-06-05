<?php namespace Mock;

use App\Services\Contracts\IImageService;

class MockImageService implements IImageService
{
    private $id = 0;
    public function Get($id)
    {
        
    }

    public function GetAllImages()
    {
        
    }

    public function GetImagePath($id)
    {
        
    }

    public function GuessLogo($team)
    {
        
    }

    public function UploadLogo($url)
    {
        return $this->id++;
    }

}

