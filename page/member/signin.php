<?php
use Corelib\Method;
use Corelib\Func;

class Signin extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/signin.tpl.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','html');
        $form->set('action',PH_DIR.'/page/member/signin.sbm.php');
        $form->run();
    }

    public function _make(){
        $req = Method::request('GET','redirect');

        if(IS_MEMBER){
            Func::err_location(SET_ALRAUTH_MSG,PH_DOMAIN);
        }

        $id_val = '';
        $save_checked = '';
        if(isset($_COOKIE['MB_SAVE_ID']) && $_COOKIE['MB_SAVE_ID']!=''){
            $id_val = $_COOKIE['MB_SAVE_ID'];
            $save_checked = 'checked';
        }

        $this->set('redirect',$req['redirect']);
        $this->set('id_val',$id_val);
        $this->set('save_checked',$save_checked);
    }

}
