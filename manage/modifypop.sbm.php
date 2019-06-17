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
        $req = Method::request('POST','mode,idx,title,link,link_target,width,height,pos_top,pos_left,level_from,level_to,show_from,show_to,html,mo_html');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // modify
    //////////////////////////////
    public function get_modify(){
        global $req;

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Valid::notnull('title',$req['title'],'');
        Valid::isnum('width',$req['width'],0,10,1,'');
        Valid::isnum('height',$req['height'],0,10,1,'');
        Valid::isnum('pos_top',$req['pos_top'],0,10,1,'');
        Valid::isnum('pos_left',$req['pos_left'],0,10,1,'');
        Valid::notnull('show_from',$req['show_from'],'');
        Valid::notnull('show_to',$req['show_to'],'');

        if($req['level_from'] > $req['level_to']){
            Valid::error('level_to','노출 종료 level 보다 시작 level 클 수 없습니다.');
        }

        if($req['show_from'] > $req['show_to']){
            Valid::error('show_to','노출 일자가 올바르지 않습니다.');
        }

        $req['show_to'] = $req['show_to'].' 23:59:59';

        $sql->query(
            $sql->scheme->popup('update:popup'),
            array(
                $req['title'],$req['link'],$req['link_target'],$req['width'],$req['height'],$req['pos_top'],$req['pos_left'],$req['level_from'],$req['level_to'],$req['show_from'],$req['show_to'],$req['html'],$req['mo_html'],$req['idx']
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

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->popup('select:popup'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Valid::error('','팝업이 존재하지 않습니다.');
        }

        $sql->query(
            $sql->scheme->popup('delete:popup'),
            array(
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 삭제 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=poplist'.$manage->retlink('')
            )
        );
        Valid::success();
    }

}
