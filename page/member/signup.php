<?php
use Corelib\Func;

class Signup extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/signup.tpl.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','static');
        $form->set('action',PH_DIR.'/member/signup2');
        $form->set('method','POST');
        $form->set('target','view');
        $form->run();
    }

    public function _make(){
        if(IS_MEMBER){
            Func::err_location(SET_ALRAUTH_MSG,PH_DOMAIN);
        }
    }

}
