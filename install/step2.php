<?php
use Corelib\Func;

include_once './install.core.php';
include_once './head.set.php';

if(step1_chk()===false){
    Func::err_location('Step 1 부터 진행해야 합니다.','./index.php');
}

require_once './html/step2.html';
include_once './foot.set.php';
