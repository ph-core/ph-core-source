<?php
use Corelib\Method;
use Corelib\Valid;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $MB;

        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id,name,pwd,pwd2,email');
        $manage->req_hidden_inp('POST');

        Valid::isidx('id',$req['id'],1,'');
        Valid::isnick('name',$req['name'],1,'');
        Valid::isemail('email',$req['email'],1,'');

        $sql->query(
            $sql->scheme->adminfo('select:id_chk'),
            array(
                $req['id']
            )
        );

        if($sql->getcount()>0){
            Valid::error('id','이미 존재하는 아이디입니다.');
        }

        $sql->query(
            $sql->scheme->adminfo('select:id_chk'),
            array(
                $req['email']
            )
        );

        if($sql->getcount()>0){
            Valid::error('email','다른 회원이 사용중인 email 입니다.');
        }

        if($req['pwd']!=$req['pwd2']){
            Valid::error('pwd2','비밀번호와 비밀번호 확인이 일치하지 않습니다.');
        }

        if($req['pwd']!=''){

            Valid::ispwd('pwd',$req['pwd'],1,'');

            $sql->query(
                $sql->scheme->adminfo('update:adminfo2'),
                array(
                    $req['id'],$req['name'],$req['pwd'],$req['email'],$MB['idx']
                )
            );

        }else{

            $sql->query(
                $sql->scheme->adminfo('update:adminfo'),
                array(
                    $req['id'],$req['name'],$MB['pwd'],$req['email'],$MB['idx']
                )
            );

        }

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::success();
    }

}
