<?php
use Corelib\Func;
use Corelib\Method;
use Manage\Func as Manage;

include_once '../lib/ph.core.php';

$manage = new Manage();

$PARAM = Method::request('GET','mod,href,p,sort,ordtg,ordsc,where,keyword,page');

Func::add_title('Manager');
Func::getlogin('관리자만 접근 가능합니다.');
Func::chklevel(1);

$keyword = urldecode($PARAM['keyword']);

if(!$PARAM['href']){
    $load_href = 'main';
}else{
    $load_href = $PARAM['href'];
}

$searchby = '';
if(trim($PARAM['keyword'])!=''){
    $searchby = 'AND '.$PARAM['where'].' LIKE \'%'.$PARAM['keyword'].'%\'';
}

if(isset($PARAM['p']) && $PARAM['p']!=''){
    $load_href = $PARAM['p'];
}
if($manage->href_type()=='mod'){
    $inc = PH_MOD_PATH.'/'.$PARAM['mod'].'/manage.set/'.$load_href.'.php';
}else if($manage->href_type()=='def'){
    $inc = PH_MANAGE_PATH.'/'.$load_href.'.php';
    if(!file_exists($inc)){
        $inc = PH_MANAGE_PATH.'/main.php';
    }
}else{
    $inc = PH_MANAGE_PATH.'/main.php';
}

$PARAM['load_href'] = $load_href;

include_once $inc;
$$load_href = new $load_href();
$$load_href->_init();
