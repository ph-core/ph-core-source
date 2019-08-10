<?php
use Manage\Func as Manage;

$manage = new Manage();
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=1400"/>
<?php if($CONF['favicon']){ ?>
<link rel="icon" href="<?php echo PH_DATA_DIR."/manage/".$CONF['favicon']; ?>" />
<link rel="shortcut icon" href="<?php echo PH_DATA_DIR."/manage/".$CONF['favicon']; ?>" />
<?php } ?>
<link rel="stylesheet" href="<?php echo PH_DIR; ?>/layout/css/jquery.common.css" />
<link rel="stylesheet" href="<?php echo PH_DIR; ?>/manage/css/common.css" />
<link rel="stylesheet" href="<?php echo PH_DIR; ?>/manage/css/global.css" />
<link rel="stylesheet" href="<?php echo PH_PLUGIN_DIR; ?>/<?php echo PH_PLUGIN_CKEDITOR; ?>/contents_view.css" />
<script type="text/javascript">
var PH_DIR = '<?php echo PH_DIR; ?>';
var PH_DOMAIN = '<?php echo PH_DOMAIN; ?>';
var PH_PLUGIN_DIR = '<?php echo PH_PLUGIN_DIR; ?>';
var PH_MANAGE_DIR = '<?php echo PH_MANAGE_DIR; ?>';
var PH_MN_HREF_TYPE = '<?php echo $manage->href_type(); ?>';
var PH_MN_HREF_MOD = '<?php echo $PARAM['mod']; ?>';
var PH_MN_HREF_HREF = '<?php echo $PARAM['href']; ?>';
</script>
<script src="<?php echo PH_DIR; ?>/layout/js/jquery.min.js"></script>
<script src="<?php echo PH_DIR; ?>/layout/js/jquery.common.js"></script>
<script src="<?php echo PH_DIR; ?>/layout/js/common.js"></script>
<script src="<?php echo PH_DIR; ?>/manage/js/global.js"></script>
<script src="<?php echo PH_PLUGIN_DIR; ?>/<?php echo PH_PLUGIN_CKEDITOR; ?>/ckeditor.js"></script>
</head>
<body>
<div id="<?php if(defined("MAINPAGE")){ echo "main"; }else{ echo "sub"; } ?>">
