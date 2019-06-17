<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Mbpoint extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/mbpoint.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','mbpointForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/mbpoint.sbm.php');
        $form->run();
    }

    public function _func(){
        function act_total($arr){
            return Func::number($arr['act_total']);
        }

        function in_total($arr){
            return Func::number($arr['in_total']);
        }

        function out_total($arr){
            return Func::number($arr['out_total']);
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
            $sql->scheme->member('sort:mbpoint'),''
        );
        $sort_arr['act_total'] = $sql->fetch('act_total');
        $sort_arr['in_total'] = $sql->fetch('in_total');
        $sort_arr['out_total'] = $sql->fetch('out_total');

        if($PARAM['sort']){
            $sortby = 'AND '.$PARAM['sort'].'>0';
        }

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->member('select:mbpoint'),''
            ),''
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['p_in'] = Func::number($arr['p_in']);
                $arr['p_out'] = Func::number($arr['p_out']);
                $arr['regdate'] = Func::datetime($arr['regdate']);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('act_total',act_total($sort_arr));
        $this->set('in_total',in_total($sort_arr));
        $this->set('out_total',out_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);

    }

}
