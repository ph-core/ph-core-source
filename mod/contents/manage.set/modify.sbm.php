<?php
use Corelib\Method;
use Corelib\Valid;
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
        $req = Method::request('POST','mode,idx,title,html,mo_html,use_mo_html');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // modify
    //////////////////////////////
    public function get_modify(){
        global $req;

        $sql = new Pdosql();

        $sql->scheme('Module\\Contents\\Scheme');

        Valid::notnull('title',$req['title'],'');

        if($req['use_mo_html']=='checked'){
            $req['use_mo_html'] = 'Y';
        }else{
            $req['use_mo_html'] = 'N';
        }

        $sql->query(
            $sql->scheme->manage('update:contents'),
            array(
                $req['title'],$req['html'],$req['mo_html'],$req['use_mo_html'],$req['idx']
            )
        );

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

        $sql->scheme('Module\\Contents\\Scheme');

        $sql->query(
            $sql->scheme->manage('select:contents'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Valid::error('','콘텐츠가 존재하지 않습니다.');
        }

        $sql->query(
            $sql->scheme->manage('delete:contents'),
            array(
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 삭제 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?mod='.MOD_CONTENTS.'&href=lists'.$manage->retlink('')
            )
        );
        Valid::success();
    }

}
