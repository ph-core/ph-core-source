<?php
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Lists extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_BOARD_PATH.'/manage.set/html/list.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','makeBoardForm');
        $form->set('type','html');
        $form->set('action',MOD_BOARD_DIR.'/manage.set/list.clone.sbm.php');
        $form->run();
    }

    public function _func(){
        function board_total($arr){
            return Func::number($arr['board_total']);
        }
        function data_total($arr){
            global $board_id;

            $sql = new Pdosql();

            $sql->scheme('Module\\Board\\Scheme');

            $board_id = $arr['id'];

            $sql->query(
                $sql->scheme->manage('select:data_total'),
                array(
                    $board_id
                )
            );
            return Func::number($sql->getcount());
        }
    }

    public function _make(){
        global $PARAM,$sortby,$searchby,$orderby;

        $sql = new Pdosql();
        $paging = new Paging();
        $manage = new Manage();

        $sql->scheme('Module\\Board\\Scheme');

        $sql->query(
            $sql->scheme->manage('sort:config'),''
        );
        $sort_arr['board_total'] = $sql->fetch('board_total');

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->manage('select:configs'),''
            ),''
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr[0]['data_total'] = data_total($arr);
                $arr['regdate'] = Func::datetime($arr['regdate']);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('board_total',board_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);

    }

}
