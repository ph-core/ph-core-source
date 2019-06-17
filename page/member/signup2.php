<?php
use Corelib\Method;
use Corelib\Func;

class Signup2 extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/signup2.tpl.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','html');
        $form->set('action',PH_DIR.'/page/member/signup2.sbm.php');
        $form->run();
    }

    public function _make(){
        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','chk_policy,chk_private');

        if(IS_MEMBER){
            Func::err_location(SET_ALRAUTH_MSG,PH_DOMAIN);
        }

        //약관에 동의 했는지 검사
        if($req['chk_policy']!='checked' || $req['chk_private']!='checked'){
            Func::err_back('모든 약관에 동의해야 회원가입이 가능합니다.');
        }
    }

}
