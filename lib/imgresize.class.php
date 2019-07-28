<?php
namespace Make\Library;

use Corelib\Func;

class Imgresize{

    public $orgimg;
    public $newimg;
    public $width;
    public $quality = '80';
    public $type;
    private $tmporg;
    private $tmpnew;

    public function set($arr){
        foreach($arr as $key => $value){
            $this->{$key} = $value;
        }
    }

    private function make_tmporg(){
        switch($this->type){
            case 'png' :
            $this->tmporg = imagecreatefrompng($this->orgimg);
            break;

            case 'gif' :
            $this->tmporg = imagecreatefromgif($this->orgimg);
            break;

            default :
            $this->tmporg = imagecreatefromjpeg($this->orgimg);
        }
    }

    private function make_resampled(){
        $sizeinfo = getimagesize($this->orgimg);
        $org_width = $sizeinfo[0];
        $org_height = $sizeinfo[1];

        if($org_width > $this->width){
            $height = $this->width * ($org_height/$org_width);
        }else{
            $this->width = $org_width;
            $height = $org_height;
        }

        $this->tmpnew = imagecreatetruecolor($this->width,$height);
        imagecopyresampled($this->tmpnew,$this->tmporg,0,0,0,0,$this->width,$height,$org_width,$org_height);
    }

    private function destroy(){
        imagedestroy($this->tmporg);
        imagedestroy($this->tmpnew);
    }

    public function make(){
        $this->get_filetype();
        $this->make_tmporg();
        $this->make_resampled();

        switch($this->type){
            case 'png' :
            imagepng($this->tmpnew,$this->newimg);
            break;

            case 'gif' :
            imagegif($this->tmpnew,$this->newimg);
            break;

            default :
            imagejpeg($this->tmpnew,$this->newimg,$this->quality);
        }

        $this->destroy();
    }
}
