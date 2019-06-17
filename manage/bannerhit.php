<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;

include_once '../lib/ph.core.php';

$sql = new Pdosql();

$sql->scheme('Manage\\Scheme');

Method::security('REQUEST_GET');
$req = Method::request('GET','idx,key');

if(!$req['idx'] || !$req['key']){
    Func::location(PH_DOMAIN);
}

$sql->query(
    $sql->scheme->bannerhit('select:banner'),
    array(
        $req['idx'],
        $req['key']
    )
);

if($sql->getcount()<1){
    Func::location(PH_DOMAIN);
}

$link = $sql->fetch('link');

$sql->query(
    $sql->scheme->bannerhit('update:hit'),
    array(
        $req['idx'],
        $req['key']
    )
);

Func::location($link);
