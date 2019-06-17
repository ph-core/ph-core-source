<?php
use Corelib\Method;
use Corelib\Valid;

class submit extends \Corelib\Valid{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','pwd');

        Valid::ispwd('pwd',$req['pwd'],1,'올바르게 입력하세요.');

        //return
        Valid::set(
            array(
                'return' => 'ajax-validt',
                'msg' => '사용할 수 있는 비밀번호입니다.'
            )
        );
        Valid::success();
    }

}
