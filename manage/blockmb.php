<?php
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Blockmb extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/blockmb.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','blockmbForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/blockmb.sbm.php');
        $form->run();
    }

    public function form2(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','blockmbDelForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/blockmb.sbm.php');
        $form->run();
    }

    public function _func(){
        function block_total($arr){
            return Func::number($arr['total']);
        }
    }

    public function _make(){
        global $PARAM,$sortby,$searchby,$orderby;

        $sql = new Pdosql();
        $paging = new Paging();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        //sortby
        $sortby = '';
        $sort_arr = array();

        $sql->query(
            $sql->scheme->blocked('sort:blockmb'),''
        );
        $sort_arr['total'] = $sql->fetch('total');

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->blocked('select:blockmbs'),''
            ),''
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['regdate'] = Func::datetime($arr['regdate']);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('block_total',block_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);
    }

}
