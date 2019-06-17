<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $board_id,$clone_id,$board_title;

        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Module\\Board\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','board_id,clone_id');

        Valid::isidx('board_id',$req['board_id'],1,'');
        Valid::isidx('clone_id',$req['clone_id'],1,'');

        $board_id = $req['board_id'];
        $clone_id = $req['clone_id'];

        $sql->query(
            $sql->scheme->manage('select:config'),
            array(
                $board_id
            )
        );

        if($sql->getcount()<1){
            Valid::error('','복제할 게시판이 존재하지 않습니다.');
        }
        $board_title = addSlashes($sql->fetch('title'));


        $sql->query(
            $sql->scheme->manage('select:config'),
            array(
                $clone_id
            )
        );

        if($sql->getcount()>0){
            Valid::error('clone_id','생성할 게시판 id가 이미 존재하는 id입니다.');
        }

        $sql->query(
            $sql->scheme->manage('clone:board'),
            array(
                $clone_id,
                '\''.$board_title.'\'에서 복제됨',
                $board_id
            )
        );

        $board_id = $clone_id;

        $sql->query(
            $sql->scheme->manage('create:data'),''
        );

        $sql->query(
            $sql->scheme->manage('create:cmt'),''
        );

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '게시판이 성공적으로 복제 되었습니다.'
            )
        );
        Valid::success();
    }

}
