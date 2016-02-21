<?php namespace App\Services;

use App\Models\Data\Image;
use App\Services\Contracts\IImageService;

/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 29/07/15
 * Time: 12:28
 */

class ImageService implements IImageService
{
    private $uploadPath;
    private $storagePath;
        
    public function __construct()
    {
        $this->uploadPath = storage_path('app');
        $this->storagePath = public_path('uploads');
    }
    
    public function Get($id)
    {
        $image = Image::find($id);
        if($image == null)
        {
            throw new \Exception('Image not found: ' . $id);
        }

        return $image;
    }

    public function GetAllImages()
    {
        $i = Image::all();
        return $i;
    }

    public function UploadLogo($url)
    {
        $data = file_get_contents($url);
        $file = fopen($this->uploadPath . '/temp.img', "w+");
        fputs($file, $data);
        fclose($file);
        list($width, $height, $ext) = getimagesize($this->uploadPath . '/temp.img');
        if($width > $height && 32 < $height)
        {
            $newheight = $height / ($width / 32);
        }
        else if ($width < $height && 32 < $width)
        {
            $newwidth = $width / ($height / 32);
        }
        else
        {
            $newwidth = $width;
            $newheight = $height;
        }

        $thumb = imagecreatetruecolor(32, 32);
        $source = imagecreatefromstring(file_get_contents($this->uploadPath . '/temp.img'));
        imagecopyresized($thumb, $source, 0, 0, 0, 0, 32, 32, $newwidth, $newheight);
        imagepng($thumb, $this->uploadPath . '/temp32.png');
        $i = new Image;
        $i->w = 32;
        $i->h = 32;
        $i->ext = 'png';
        $i->save();
        
        rename($this->uploadPath . '/temp32.png', $this->storagePath . '/' . $i->id . '.' . $i->ext);
        unlink($this->uploadPath . '/temp.img');
        return $i->id;
    }

    public function GetImagePath($id)
    {
        return \URL::to('uploads') . '/' . $id . '.png';
    }

    public function GuessLogo($team)
    {
        $id = intval(\DB::table('games')->where('team1', '=', $team)->max('logo1'));
        if($id > 0)
            return $id;
        else{
            $id = intval(\DB::table('games')->where('team2', '=', $team)->max('logo2'));
            return $id;
        }
    }
}