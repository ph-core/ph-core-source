<?php
namespace Make\Library;

use Corelib\Func;

class Uploader{

    public $path;
    public $file;
    public $intdict = SET_INTDICT_FILE;
    public $file_idx = 0;

    //파일 유무 검사
    public function isfile($file){
        if(@is_file($file)){
            return true;
        }else{
            return false;
        }
    }

    //디렉토리 유무 검사
    public function isdir($dir){
        if(@is_dir($dir)){
            return true;
        }else{
            return false;
        }
    }

    //파일 검사
    public function chkfile($type){
        $intd = explode(',',$this->intdict);
        $f_type = Func::get_filetype($this->file['name']);
        $chk = true;
        for($i=0;$i<=count($intd)-1;$i++){
            if($f_type==$intd[$i]){
                $chk = false;
            }
        }
        if($type=='notmatch'){
            if($chk===false){
                return false;
            }else{
                return true;
            }
        }else if($type=='match'){
            if($chk===false){
                return true;
            }else{
                return false;
            }
        }

    }

    //첨부 파일명 변환
    public function replace_filename($file){
        $tstamp = date('ymdhis',time());
        $file_name = $tstamp.$this->file_idx.'_'.$file;
        $file_name = str_replace(' ','_',$file_name);
        $this->file_idx++;
        return $file_name;
    }

    //파일 byte 검사
    public function chkbyte($limit){
        $chked = true;
        if($this->file['size']>$limit){
            $chked = false;
        }
        return $chked;
    }

    //저장 위치 검사 및 생성
    public function chkpath(){
        if(!is_dir($this->path)){
            @mkdir($this->path,0707);
            @chmod($this->path,0707);
        }
    }

    //copy
    public function filecopy($old_file,$file){
        @copy($old_file,$file);
    }

    //save
    public function upload($filename){
        $chked = true;
        if(!$this->file_upload = move_uploaded_file($this->file['tmp_name'],$this->path.'/'.$filename)){
            $chked = false;
        }
        return $chked;
    }

    //delete
    public function drop($file){
        if($this->isfile($this->path.'/'.$file)){
            unlink($this->path.'/'.$file);
        }
    }

    //delete directory
    public function dropdir(){
        if($this->isdir($this->path)){
            $dir = dir($this->path);
            while(($entry=$dir->read())!==false){
                if($entry!='.' && $entry!='..'){
                    @unlink($this->path.'/'.$entry);
                }
            }
            $dir->close();
            @rmdir($this->path);
        }
    }

    //에디터 사진 삭제
    public function edt_drop($article){
        $this->path = PH_PATH.'/ckeditor/';
        preg_match_all("/ckeditor\/[a-zA-Z0-9-_\.]+.(jpg|gif|png|bmp)/i",$article,$sEditor_images_ex);
        for($i=0;$i<count($sEditor_images_ex[0]);$i++){
            $this->name = str_replace('ckeditor4/','',$this->sEditor_images_ex[0][$i]);
            if($this->isfile($this->name)){
                $this->filedrop($this->name);
            }
        }
    }

}
