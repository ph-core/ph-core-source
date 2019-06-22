<?php
use Corelib\Func;

if(!isset($rewritepage) && isset($_GET['rewritepage'])){
    $rewritepage = $_GET['rewritepage'];
}

include_once "./lib/ph.core.php";

if(!isset($rewritepage) || !$rewritepage){
    $rewritepage = "index";
}

$fpath = substr($rewritepage,0,strpos($rewritepage,"/"));
$tpath = PH_PATH;
$topen = opendir($tpath);
while($dir = readdir($topen)){
    if($dir!="." && $dir!="..") $page_pos[$dir] = $dir;
}

//URI에 root 디렉토리가 포함되어 있는 경우 rewrite 적용 안함
if(in_array($fpath,$page_pos)===false){
    $file = PH_PATH."/page/".$rewritepage.".php";
}else{
    if((isset($OUTLOAD) && $OUTLOAD==1) || (isset($_GET['OUTLOAD']) && $_GET['OUTLOAD']==1)){
        $file = PH_PATH."/".$rewritepage.".php";
    }else{
        Func::location(PH_DIR."/error_404");
    }
}

//URI에 '/mod/'가 포함된 경우 class load 위한 namespace 설정
if(strpos($file,"/mod/")!==false){
    $namespc = str_replace(PH_PATH."/mod/","/module/",$file);
    $namespc = str_replace(substr($namespc,strpos($namespc,"/controller/")),"",$namespc)."/";
    $namespc = str_replace("/","\\",$namespc);
}else{
    $namespc = "";
}

//최종 경로에 Controller 파일이 있으면 include
if(file_exists($file)){
    include_once $file;
}else{
    Func::location(PH_DIR."/error_404");
    exit;
}

//Controller의 Class 호출
if(isset($_GET['CALL_CLASSNAME'])){
    $rewritepage = $_GET['CALL_CLASSNAME'];
}else{
    $rewritepage = $namespc.basename($rewritepage);
}
$rewritepage = str_replace("-","_",$rewritepage);
$rewritepage = str_replace(".","_",$rewritepage);

$$rewritepage = new $rewritepage();
$$rewritepage->_init();
