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
        $req = Method::request('POST','mode,idx,title,html');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // modify
    //////////////////////////////
    public function get_modify(){
        global $req;

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->mailtpl('select:mailtpl'),
            array(
                $req['idx']
            )
        );

        Valid::notnull('title',$req['title'],'');

        if($sql->fetch('system')=='Y'){

            $sql->query(
                $sql->scheme->mailtpl('update:mailtpl'),
                array(
                    $req['html'],$req['idx']
                )
            );

        }else{

            $sql->query(
                $sql->scheme->mailtpl('update:mailtpl2'),
                array(
                    $req['title'],$req['html'],$req['idx']
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
            $sql->scheme->mailtpl('select:mailtpl'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Valid::error('','메일 템플릿이 존재하지 않습니다.');
        }
        if($sql->fetch('system')=='Y'){
            Valid::error('','시스템 발송 메일 템플릿은 삭제 불가합니다.');
        }

        $sql->query(
            $sql->scheme->mailtpl('delete:mailtpl'),
            array(
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 삭제 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=mailtpl'.$manage->retlink('')
            )
        );
        Valid::success();
    }

}
