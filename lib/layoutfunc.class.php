<?php
namespace Make\View;

use Corelib\Func;

class Layout{

    public function logo_title(){
        global $CONF;
        return $CONF['title'];
    }

    public function site_href(){
        return PH_DOMAIN;
    }

    public function logo_src(){
        global $CONF;
        if($CONF['logo']){
            return PH_DATA_DIR.'/manage/'.$CONF['logo'];
        }else{
            return PH_THEME_DIR.'/layout/images/logo.png';
        }
    }

    public function signin_href(){
        $link = PH_DIR.'/member/signin?redirect='.Func::thisuriqry();
        if(Func::thisdir()=='/member'){
            $link = PH_DIR.'/member/signin?redirect=/';
        }
        return $link;
    }

}
