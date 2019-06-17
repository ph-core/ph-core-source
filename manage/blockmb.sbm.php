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
            case 'add' :
            $this->get_add();
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
        $req = Method::request('POST','mode,idx,id,memo');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // add
    //////////////////////////////
    public function get_add(){
        global $req;

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Valid::notnull('id',$req['id'],'');
        Valid::notnull('memo',$req['memo'],'');

        $sql->query(
            $sql->scheme->member('select:id_chk'),
            array(
                $req['id']
            )
        );

        if($sql->getcount()<1){
            Valid::error('id','존재하지 않는 회원 id입니다.');
        }

        $mb_id = $req['id'];
        $mb_idx = $sql->fetch('mb_idx');

        $sql->query(
            $sql->scheme->blocked('select:chk_mb'),
            array(
                $mb_idx,
                $mb_id
            )
        );

        if($sql->getcount()>0){
            Valid::error('id','이미 등록된 회원 입니다.');
        }

        $sql->query(
            $sql->scheme->blocked('insert:blockmb'),
            array(
                $mb_idx,
                $mb_id,
                $req['memo']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 추가 되었습니다.'
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

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->blocked('select:blockrec'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Valid::error('','등록되지 않은 차단 정보입니다.');
        }

        $sql->query(
            $sql->scheme->blocked('delete:blockrec'),
            array(
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->reload'
            )
        );
        Valid::success();
    }

}
