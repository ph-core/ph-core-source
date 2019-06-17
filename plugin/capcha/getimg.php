<?php
if(!isset($PLUGIN_CAPCHA_CONF['id'])){
  $PLUGIN_CAPCHA_CONF['id'] = "capcha";
}
?>

<img id="<?=$PLUGIN_CAPCHA_CONF['id']?>-img" class="plugin-capcha-img" src="<?=PH_PLUGIN_DIR?>/capcha/zmSpamFree.php?zsfimg" alt="코드를 바꾸시려면 클릭해 주세요." title="코드를 바꾸시려면 클릭해 주세요." style="cursor: pointer;" />
<input name="<?=$PLUGIN_CAPCHA_CONF['id']?>" id="<?=$PLUGIN_CAPCHA_CONF['id']?>" type="text" title="스팸방지 코드" maxlength="4" class="plugin-capcha" />
