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
        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id,title,link,link_target,width,height,pos_top,pos_left,level_from,level_to,show_from,show_to,html,mo_html');
        $manage->req_hidden_inp('POST');

        Valid::isidx('id',$req['id'],1,'');
        Valid::notnull('title',$req['title'],'');
        Valid::isnum('width',$req['width'],0,10,1,'');
        Valid::isnum('height',$req['height'],0,10,1,'');
        Valid::isnum('pos_top',$req['pos_top'],0,10,1,'');
        Valid::isnum('pos_left',$req['pos_left'],0,10,1,'');
        Valid::notnull('show_from',$req['show_from'],'');
        Valid::notnull('show_to',$req['show_to'],'');

        if($req['level_from'] > $req['level_to']){
            Valid::error('level_to','노출 종료 level 보다 시작 level이 클 수 없습니다.');
        }

        if($req['show_from'] > $req['show_to']){
            Valid::error('show_to','노출 일자가 올바르지 않습니다.');
        }

        $sql->query(
            $sql->scheme->popup('select:popup2'),
            array(
                $req['id']
            )
        );

        if($sql->getcount()>0){
            Valid::error('id','이미 존재하는 팝업 id 입니다.');
        }

        $req['show_to'] = $req['show_to'].' 23:59:59';

        $sql->query(
            $sql->scheme->popup('insert:popup'),
            array(
                $req['id'],$req['title'],$req['link'],$req['link_target'],$req['width'],$req['height'],$req['pos_left'],$req['pos_top'],$req['level_from'],$req['level_to'],$req['show_from'],$req['show_to'],$req['html'],$req['mo_html']
            )
        );

        $sql->query(
            $sql->scheme->popup('select:popup2'),
            array(
                $req['id']
            )
        );
        $idx = $sql->fetch('idx');

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 추가 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=poplist&p=modifypop&idx='.$idx
            )
        );
        Valid::success();
    }

}
