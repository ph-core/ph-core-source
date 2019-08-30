<?php
namespace Module\Board;

use Corelib\Method;
use Make\Database\Pdosql;
use Module\Board\Library as Board_Library;

class Ctrpop extends \Controller\Make_Controller{

    public function _init(){
        global $boardconf;

        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_BOARD_THEME_PATH.'/board/'.$boardconf['theme'].'/ctrpop.tpl.php');
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','board_ctrpopForm');
        $form->set('type','html');
        $form->set('action',MOD_BOARD_DIR.'/controller/ctrpop.sbm.php');
        $form->run();
    }

    public function _func(){
        //게시판 목록
        function board_opt_list(){
            $sql = new Pdosql();

            $sql->scheme('Module\\Board\\Scheme');

            $sql->query(
                $sql->scheme->lists('select:boards'),''
            );

            $opt = '';
            do{
                $arr = $sql->fetchs();
                $opt .= '<option value="'.$arr['id'].'">'.$arr['title'].'('.$arr['id'].')</option>';
            }while($sql->nextRec());

            return $opt;
        }
    }

    public function _make(){
        global $boardconf;

        $boardlib = new Board_Library();

        $req = Method::request('POST','cnum,board_id');
        $boardconf = $boardlib->load_conf($req['board_id']);


        for($i=0;$i<sizeof($req['cnum']);$i++){
            if(!isset($cnum_arr)){
                $cnum_arr = $req['cnum'][$i];
            }else{
                $cnum_arr .= ','.$req['cnum'][$i];
            }
        }

        $this->set('slt_count',sizeof($req['cnum']));
        $this->set('board_opt_list',board_opt_list());
        $this->set('cnum_arr',$cnum_arr);
        $this->set('board_id',$req['board_id']);
    }

}
