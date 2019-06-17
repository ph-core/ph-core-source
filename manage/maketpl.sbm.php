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
        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','type,title,html');
        $manage->req_hidden_inp('POST');

        Valid::isidx('type',$req['type'],1,'');
        Valid::notnull('title',$req['title'],'');

        $sql->query(
            $sql->scheme->mailtpl('select:mailtpl2'),
            array(
                $req['type']
            )
        );

        if($sql->getcount()>0){
            Valid::error('type','이미 존재하는 템플릿 type 입니다.');
        }

        $sql->query(
            $sql->scheme->mailtpl('insert:mailtpl'),
            array(
                $req['type'],$req['title'],$req['html']
            )
        );

        $sql->query(
            $sql->scheme->mailtpl('select:mailtpl2'),
            array(
                $req['type']
            )
        );
        $idx = $sql->fetch('idx');

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 추가 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=mailtpl&p=modifytpl&idx='.$idx
            )
        );
        Valid::success();
    }

}
