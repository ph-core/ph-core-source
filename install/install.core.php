<?php
use Corelib\Func;

include_once '../lib/pdo.class.php';
include_once '../lib/functions.class.php';
include_once '../lib/method.class.php';
include_once '../lib/valid.class.php';
include_once './functions.php';
if(file_exists('../data/dbconn.set.php')){
    Func::location('../');
}
