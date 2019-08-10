<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no" />
<?php
switch(USE_MOBILE){
  case "Y" :
    $use_respd = true;
    break;
  case "N" :
    $use_respd = false;
    break;
  case "C" :
    if($CONF['use_mobile']=="Y"){
      $use_respd = true;
    }else{
      $use_respd = false;
    }
    break;
}
if($use_respd===true){
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />'.PHP_EOL;
}else{
  echo '<meta name="viewport" content="width=1400,user-scalable=yes" />'.PHP_EOL;
}
?>
<meta name="description" content="<?php echo $CONF['description']; ?>" />
<meta property="og:type" content="<?php echo $CONF['og_type']; ?>" />
<meta property="og:description" content="<?php echo $CONF['og_description']; ?>" />
<meta property="og:url" content="<?php echo $CONF['og_url']; ?>" />
<?php if($CONF['og_image']){ ?>
<meta property="og:image" content="<?php echo PH_DOMAIN?><?php echo PH_DATA_DIR?>/manage/<?php echo $CONF['og_image']; ?>" />
<?php } ?>
<?php if($CONF['naver_verific']){ ?>
<meta name="naver-site-verification" content="<?php echo $CONF['naver_verific']; ?>" />
<?php } ?>
<?php if($CONF['google_verific']){ ?>
<meta name="google-site-verification" content="<?php echo $CONF['google_verific']; ?>" />
<?php } ?>
<meta name="EnlighterJS" content="Advanced javascript based syntax highlighting" data-indent="4" data-selector-block="pre" data-selector-inline="code" data-language="javascript" />
<?php
if($CONF['meta']){
  echo $CONF['meta'].PHP_EOL;
}
?>
<?php if($CONF['favicon']){ ?>
<link rel="icon" href="<?php echo PH_DATA_DIR."/manage/".$CONF['favicon']?>" />
<link rel="shortcut icon" href="<?php echo PH_DATA_DIR."/manage/".$CONF['favicon']; ?>" />
<?php } ?>
<link rel="stylesheet" href="<?php echo PH_DIR; ?>/layout/css/jquery.common.css" />
<link rel="stylesheet" href="<?php echo PH_DIR; ?>/layout/css/common.css" />
<link rel="stylesheet" href="<?php echo PH_THEME_DIR; ?>/layout/css/layout.css" />
<?php if($use_respd===true){ ?>
<link rel="stylesheet" href="<?php echo PH_THEME_DIR; ?>/layout/css/respond.css" />
<link rel="stylesheet" href="<?php echo PH_PLUGIN_DIR; ?>/<?php echo PH_PLUGIN_CKEDITOR; ?>/contents_view.css" />
<?php } ?>
<?php
//모듈별 CSS
foreach($MODULE as $key => $value){
  $file = PH_THEME_PATH."/mod-".$value."/style.css";
  if(file_exists($file)){
    echo '<link rel="stylesheet" href="'.PH_THEME_DIR.'/mod-'.$value.'/style.css'.'"/>'.PHP_EOL;
	}
}
?>
<script type="text/javascript">
var PH_DIR = '<?php echo PH_DIR; ?>';
var PH_DOMAIN = '<?php echo PH_DOMAIN; ?>';
var PH_PLUGIN_DIR = '<?php echo PH_PLUGIN_DIR; ?>';
</script>
<script src="<?php echo PH_DIR; ?>/layout/js/jquery.min.js"></script>
<script src="<?php echo PH_DIR; ?>/layout/js/jquery.common.js"></script>
<script src="<?php echo PH_DIR; ?>/layout/js/common.js"></script>
<script src="<?php echo PH_DIR; ?>/layout/js/global.js"></script>
<script src="<?php echo PH_THEME_DIR; ?>/layout/js/layout.js"></script>
<script src="<?php echo PH_PLUGIN_DIR; ?>/<?php echo PH_PLUGIN_CKEDITOR; ?>/ckeditor.js"></script>
<?php
//모듈별 JS
foreach($MODULE as $key => $value){
  $file = PH_THEME_PATH."/mod-".$value."/script.js";
  if(file_exists($file)){
    echo '<script src="'.PH_THEME_DIR.'/mod-'.$value.'/script.js'.'"></script>'.PHP_EOL;
	}
}
//Script 소스코드 설정 반영
if($CONF['script']){
  echo $CONF['script'].PHP_EOL;
}
?>
</head>
<body>
