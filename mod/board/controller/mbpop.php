<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Mbpop extends \Controller\Make_Controller{

    public function _init(){
        global $boardconf;

        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/mbpop.tpl.php');
    }

    public function _func(){
        //성별
        function gender($mbinfo){
            if($mbinfo['mb_gender']=='M'){
                return '남자';
            }else{
                return '여자';
            }
        }

        //작성글 보기 링크
        function get_link($mbinfo){
            return '?where=mb_id&keyword='.$mbinfo['mb_id'];
        }
    }

    public function _make(){
        global $boardconf;

        $sql = new Pdosql();
        $boardlib = new Board_Library();

        $sql->scheme('Module\\Board\\Scheme');

        $req = Method::request('GET','board_id,mb_idx');

        //load config
        $boardconf = $boardlib->load_conf($req['board_id']);

        //회원 정보 가져옴
        $sql->query(
            $sql->scheme->lists('select:member'),
            array(
                $req['mb_idx']
            )
        );
        $mbinfo = $sql->fetchs();

        //chkck
        if(!isset($req['mb_idx']) || $sql->getcount()<1){
            Func::err_location(ERR_MSG_1,PH_DOMAIN);
        }

        $mbinfo['mb_regdate'] = Func::datetime($mbinfo['mb_regdate']);
        $mbinfo['mb_lately'] = Func::datetime($mbinfo['mb_lately']);

        if(IS_MEMBER){
            $is_mbinfo_show = true;
        }else{
            $is_mbinfo_show = false;
        }

        $this->set('mbinfo',$mbinfo);
        $this->set('is_mbinfo_show',$is_mbinfo_show);
        $this->set('gender',gender($mbinfo));
        $this->set('get_link',get_link($mbinfo));
    }

}
