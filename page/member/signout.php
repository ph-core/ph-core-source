<?php
use Corelib\Method;
use Corelib\Func;
use Corelib\Session;

class Signout extends \Controller\Make_Controller{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        Method::security('REFERER');

        if(!IS_MEMBER){
            Func::err_location(SET_NOAUTH_MSG,PH_DOMAIN);
        }

        //로그인 session 삭제
        Session::empty_sess('MB_IDX');

        //로그아웃 후 페이지 이동
        Func::location(PH_DOMAIN);
    }

}
