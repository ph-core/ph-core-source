<?php
$sbmpage = $_GET['sbmpage'];
include_once 'ph.core.php';
include_once PH_PATH.str_replace(PH_DIR,'',$sbmpage);
$submit = new \Submit();
$submit->_init();
