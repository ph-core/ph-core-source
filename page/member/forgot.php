<?php
use Corelib\Func;

class Forgot extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/forgot.tpl.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','html');
        $form->set('action',PH_DIR.'/page/member/forgot.sbm.php');
        $form->run();
    }

    public function _make(){
        if(IS_MEMBER){
            Func::err_location(SET_ALRAUTH_MSG,PH_DOMAIN);
        }
    }

}
