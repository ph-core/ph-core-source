<?php
$REAL_PATH = str_replace('\\','/',str_replace(basename(__FILE__),'',realpath(__FILE__)));
$REAL_PATH = str_replace('/lib/','',$REAL_PATH);
define('REAL_PATH',$REAL_PATH);
define('REAL_DIR',str_replace($_SERVER['DOCUMENT_ROOT'],'',REAL_PATH));

if(!file_exists(REAL_PATH.'/data/dbconn.set.php')){
    echo '<script type="text/javascript">location.href=\''.REAL_DIR.'/install/\';</script>';
    exit;
}

//////////////////////////////
// Ph-Core libraries
//////////////////////////////
include_once REAL_PATH.'/data/path.set.php';
include_once REAL_PATH.'/data/dbconn.set.php';
include_once REAL_PATH.'/lib/config.set.php';
include_once PH_PATH.'/lib/autoload.class.php';
include_once PH_PATH.'/lib/pdo.class.php';
include_once PH_PATH.'/lib/session.class.php';
include_once PH_PATH.'/lib/functions.class.php';
include_once PH_PATH.'/lib/paging.class.php';
include_once PH_PATH.'/lib/uploader.class.php';
include_once PH_PATH.'/lib/imgresize.class.php';
include_once PH_PATH.'/lib/mail.class.php';
include_once PH_PATH.'/lib/method.class.php';
include_once PH_PATH.'/lib/valid.class.php';
include_once PH_PATH.'/lib/controller.class.php';
include_once PH_PATH.'/lib/layoutfunc.class.php';
include_once PH_PATH.'/lib/variable.inc.php';
include_once PH_PATH.'/lib/statistic.class.php';
include_once PH_PATH.'/lib/blocked.class.php';
include_once PH_MANAGE_PATH.'/lib/functions.class.php';

//모듈별 기본 설정 파일
foreach($MODULE as $key=>$val){
    $file = PH_MOD_PATH.'/'.$val.'/lib/config.set.php';
    if(file_exists($file)){
        include_once $file;
    }
    $file = PH_MOD_PATH.'/'.$val.'/lib/lib.inc.php';
    if(file_exists($file)){
        include_once $file;
    }
    $file = PH_MOD_PATH.'/'.$val.'/lib/controller.class.php';
    if(file_exists($file)){
        include_once $file;
    }
    $file = PH_MOD_PATH.'/'.$val.'/lib/functions.class.php';
    if(file_exists($file)){
        include_once $file;
    }
}
