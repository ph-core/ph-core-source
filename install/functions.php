<?php
function permschk($dir){
    return is_writable($dir);
}

function phpversions(){
    $version = (string)phpversion();
    if($version > '5.3.0'){
        return true;
    }else{
        return false;
    }
}

function extschk($exts){
    $loaded = extension_loaded($exts);
    if($loaded!==false){
        return true;
    }else{
        return false;
    }
}

function step1_chk(){
    if(
        permschk('../data/')!==false &&
        phpversions()!==false &&
        extschk('GD')!==false &&
        extschk('mbstring')!==false &&
        extschk('PDO')!==false
    ){
        return true;
    }else{
        return false;
    }
}
