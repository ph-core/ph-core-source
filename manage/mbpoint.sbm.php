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
        global $id_qry;

        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id,point,memo');
        $manage->req_hidden_inp('POST');

        Valid::notnull('id',$req['id'],'');
        Valid::isneganum('point',$req['point'],0,10,1,'');
        Valid::notnull('memo',$req['memo'],'');

        $id_ex = explode('|',$req['id']);
        $id_ex = array_unique($id_ex);

        $id_qry = '';
        for($i=0;$i<count($id_ex);$i++){
            if($i==0){
                $id_qry .= 'mb_id=\''.$id_ex[$i].'\'';
            }else{
                $id_qry .= 'OR mb_id=\''.$id_ex[$i].'\'';
            }
        }

        $sql->query(
            $sql->scheme->member('select:mbidxs'),''
        );
        if($sql->getcount()<count($id_ex)){
            Valid::error('id','존재하지 않는 회원 아이디가 포함되어 있습니다.');
        }

        do{
            $mb_idx = $sql->fetch('mb_idx');

            if($req['point']>0){
                $p_type = 'in';
            }else{
                $p_type = 'out';
                $req['point'] = $req['point']/-1;
            }
            Func::set_mbpoint($mb_idx,$p_type,$req['point'],$req['memo']);

        }while($sql->nextRec());

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 반영 되었습니다.'
            )
        );
        Valid::success();
    }

}
