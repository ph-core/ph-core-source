<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $MB;

        $sql = new Pdosql();
        $boardlib = new Board_Library();

        $sql->scheme('Module\\Board\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','board_id,read,mode');

        //load config
        $boardconf = $boardlib->load_conf($req['board_id']);

        //chkck
        if($boardconf['use_likes']=='N'){
            Valid::error('','추천 기능이 비활성화 되어 있습니다.');
        }
        if(!IS_MEMBER){
            Valid::error('','추천 권한이 없습니다. 추천 기능은 회원만 이용 가능합니다.');
        }

        //이미 참여 하였는지 검사
        $sql->query(
            $sql->scheme->view('select:likes'),
            array(
                $req['board_id'],$req['read'],$MB['idx']
            )
        );
        if($sql->getcount()>0){
            Valid::error('','이미 참여 하였습니다.');
        }

        //insert
        if($req['mode']=='likes'){

            $sql->query(
                $sql->scheme->view('insert:likes'),
                array(
                    $req['board_id'],$req['read'],$MB['idx']
                )
            );

            $sql->query(
                $sql->scheme->view('select:ret_likes'),
                array(
                    $req['board_id'],$req['read']
                )
            );
            $return_ele = '#board-likes-cnt';

        }else{

            $sql->query(
                $sql->scheme->view('insert:unlikes'),
                array(
                    $req['board_id'],$req['read'],$MB['idx']
                )
            );

            $sql->query(
                $sql->scheme->view('select:ret_unlikes'),
                array(
                    $req['board_id'],$req['read']
                )
            );
            $return_ele = '#board-unlikes-cnt';

        }

        Valid::set(
            array(
                'return' => 'callback-txt',
                'element' => $return_ele,
                'msg' => $sql->fetch('total_cnt')
            )
        );
        Valid::success();
    }
}
