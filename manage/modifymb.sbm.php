<?php
use Corelib\Method;
use Corelib\Valid;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['mode']){
            case 'mod' :
            $this->get_modify();
            break;

            case 'del' :
            $this->get_delete();
            break;
        }
    }

    public function _make(){
        global $req;

        $manage = new Manage();

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','mode,idx,pwd,pwd2,name,level,gender,phone,telephone,point,email,email_chk,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_exp');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // modify
    //////////////////////////////
    public function get_modify(){
        global $req;

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Valid::isnick('name',$req['name'],1,'');
        Valid::isemail('email',$req['email'],1,'');
        Valid::isphone('phone',$req['phone'],0,'');
        Valid::isphone('telephone',$req['telephone'],0,'');
        Valid::isnum('point',$req['point'],0,10,1,'');

        $sql->query(
            $sql->scheme->member('select:email_chk'),
            array(
                $req['email'],
                $req['idx']
            )
        );
        if($sql->getcount()>0){
            Valid::error('email','다른 회원이 사용중인 email 입니다.');
        }

        $sql->query(
            $sql->scheme->member('select:member'),
            array(
                $req['idx']
            )
        );
        $arr = $sql->fetchs();

        if($req['pwd']!=$req['pwd2']){
            Valid::error('pwd2','비밀번호와 비밀번호 확인이 일치하지 않습니다.');
        }
        if($req['pwd']!=''){
            Valid::ispwd('pwd',$req['pwd'],1,'');
        }

        if($req['point']!=$arr['mb_point']){
            $point_chg = $req['point'] - $arr['mb_point'];
            if($point_chg>0){
                Func::set_mbpoint($arr['mb_idx'],'in',$point_chg,'관리자에 의한 포인트 조정');
            }else if($point_chg<0){
                Func::set_mbpoint($arr['mb_idx'],'out',$point_chg/-1,'관리자에 의한 포인트 조정');
            }
        }

        $mb_exp = $sql->etcfd_exp(implode('|',$req['mb_exp']));

        if($req['pwd']!=''){

            $sql->query(
                $sql->scheme->member('update:member2'),
                array(
                    $req['pwd'],$req['name'],$req['gender'],$req['phone'],$req['telephone'],$req['point'],$req['level'],$req['email'],$req['email_chk'],$req['mb_1'],$req['mb_2'],$req['mb_3'],$req['mb_4'],$req['mb_5'],$req['mb_6'],$req['mb_7'],$req['mb_8'],$req['mb_9'],$req['mb_10'],$mb_exp,$req['idx']
                )
            );

        }else{

            $sql->query(
                $sql->scheme->member('update:member'),
                array(
                    $arr['mb_pwd'],$req['name'],$req['gender'],$req['phone'],$req['telephone'],$req['point'],$req['level'],$req['email'],$req['email_chk'],$req['mb_1'],$req['mb_2'],$req['mb_3'],$req['mb_4'],$req['mb_5'],$req['mb_6'],$req['mb_7'],$req['mb_8'],$req['mb_9'],$req['mb_10'],$mb_exp,$req['idx']
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

    //////////////////////////////
    // delete
    //////////////////////////////
    public function get_delete(){
        global $req;

        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->member('select:member'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Valid::error('','회원이 존재하지 않습니다.');
        }

        $sql->query(
            $sql->scheme->member('update:delete'),
            array(
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 탈퇴 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=mblist'.$manage->retlink('')
            )
        );
        Valid::success();
    }

}
