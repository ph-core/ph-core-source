<?php
function mod_autoloader($className){
    if(strpos($className,'Module\\')===false){
        return;
    }
    $className = strtolower($className);
    $className = str_replace('module','',$className);
    $className = str_replace('\\','/',$className);
    $file = basename($className);
    $loadfile = preg_replace("/($file(?!.*$file))/",'controller/'.$file,$className);
    $loadfile = PH_MOD_PATH.$loadfile.'.php';
    if(file_exists($loadfile)){
        include_once $loadfile;
    }
}
spl_autoload_register(function($className){
    if(strpos($className,'Scheme')===false){
        return;
    }
    $className = strtolower($className);
    $className = str_replace('\\','/',$className);
    $className = str_replace('module','mod',$className);
    $className = str_replace('core/','',$className);
    $className = str_replace('corelib/','',$className);
    $className = str_replace('scheme','lib/pdo.scheme/scheme.'.DB_ENGINE,$className);
    include_once PH_PATH.'/'.$className.'.php';
});
