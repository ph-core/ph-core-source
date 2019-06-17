<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Bnlist extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/bnlist.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function bn_total($arr){
            return Func::number($arr['bn_total']);
        }

        function thumbnail($arr){
            if($arr['pc_img']){
                $tmb = PH_DATA_DIR.'/manage/'.$arr['pc_img'];
            }else{
                $tmb = '';
            }
            return $tmb;
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
            $sql->scheme->banner('sort:bnlist'),''
        );
        $sort_arr['bn_total'] = $sql->fetch('bn_total');

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->banner('select:bnlist'),''),''
                )
            ;
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['hit'] = Func::number($arr['hit']);
                $arr['regdate'] = Func::datetime($arr['regdate']);
                $arr[0]['thumbnail'] = thumbnail($arr);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('bn_total',bn_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);

    }

}
