<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','email');

        Valid::isemail('email',$req['email'],1,'올바르게 입력하세요.');

        $sql->query(
            $sql->scheme->member('select:email_inspt'),
            array(
                $req['email']
            )
        );

        if($sql->getcount()>0){
            Valid::error('email','이미 존재하는 이메일입니다.');
        }

        //return
        Valid::set(
            array(
                'return' => 'ajax-validt',
                'msg' => '사용할 수 있는 이메일입니다.'
            )
        );
        Valid::success();
    }

}
