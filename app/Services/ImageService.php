<?php namespace App\Services;

use App\Models\Data\Image;
use App\Models\Data\Game;

/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 29/07/15
 * Time: 12:28
 */

class ImageService
{
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
        $file = fopen(storage_path().'/temp.img', "w+");
        fputs($file, $data);
        fclose($file);
        list($width, $height, $ext) = getimagesize(storage_path().'/temp.img');
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
        $source = imagecreatefromstring(file_get_contents(storage_path().'/temp.img'));
        imagecopyresized($thumb, $source, 0, 0, 0, 0, 32, 32, $newwidth, $newheight);
        imagepng($thumb, storage_path().'/temp32.png');
        $i = new Image;
        $i->w = 32;
        $i->h = 32;
        $i->ext = 'png';
        $i->save();
        rename(storage_path().'/temp32.png', public_path().'/images/i'.$i->id.'.'.$i->ext);
        unlink(storage_path().'/temp.img');
        return $i->id;
    }

    public function GetImagePath($id)
    {
        $image = $this->Get($id);
        return \URL::to('images') . '/i' . $image->id . $image->ext;
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

    public function GetTeam1WithoutImage() 
    {
        return Game::where('logo1','=',0)->groupBy('team1')->get();
    }

    public function GetTeam2WithoutImage() 
    {
        return Game::where('logo2','=',0)->groupBy('team2')->get();
    }
}