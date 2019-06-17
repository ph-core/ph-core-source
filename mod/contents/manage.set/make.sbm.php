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
        global $board_id;

        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Module\\Contents\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','data_key,title,html,mo_html,use_mo_html');
        $manage->req_hidden_inp('POST');

        Valid::isidx('data_key',$req['data_key'],1,'');
        Valid::notnull('title',$req['title'],'');

        $sql->query(
            $sql->scheme->manage('select:chk_datakey'),
            array(
                $req['data_key']
            )
        );

        if($sql->getcount()>0){
            Valid::error('key','이미 존재하는 콘텐츠 key 입니다.');
        }

        if($req['use_mo_html']=='checked'){
            $req['use_mo_html'] = 'Y';
        }else{
            $req['use_mo_html'] = 'N';
        }

        $sql->query(
            $sql->scheme->manage('insert:contents'),
            array(
                $req['data_key'],$req['title'],$req['html'],$req['mo_html'],$req['use_mo_html']
            )
        );

        $sql->query(
            $sql->scheme->manage('select:chk_datakey'),
            array(
                $req['data_key']
            )
        );
        $idx = $sql->fetch('idx');

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 추가 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?mod='.MOD_CONTENTS.'&href=lists&p=modify&idx='.$idx
            )
        );
        Valid::success();
    }

}
