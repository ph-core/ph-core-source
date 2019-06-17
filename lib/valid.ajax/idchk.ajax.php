<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id');

        Valid::isid('id',$req['id'],1,'올바르게 입력하세요.');

        $sql->query(
            $sql->scheme->member('select:id_inspt'),
            array(
                $req['id']
            )
        );

        if($sql->getcount()>0){
            Valid::error('id','이미 존재하는 아이디입니다.');
        }

        //return
        Valid::set(
            array(
                'return' => 'ajax-validt',
                'msg' => '사용할 수 있는 아이디입니다.'
            )
        );
        Valid::success();
    }

}
