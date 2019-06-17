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
        $this->load_tpl(MOD_CONTENTS_PATH.'/manage.set/html/lists.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function contents_total($arr){
            return Func::number($arr['contents_total']);
        }
    }

    public function _make(){
        global $PARAM,$sortby,$searchby,$orderby;

        $sql = new Pdosql();
        $paging = new Paging();
        $manage = new Manage();

        $sql->scheme('Module\\Contents\\Scheme');

        //sortby
        $sortby = '';
        $sort_arr = array();

        $sql->query(
            $sql->scheme->manage('sort:contents'),''
        );
        $sort_arr['contents_total'] = $sql->fetch('contents_total');

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query($paging->paging_query($sql->scheme->manage('select:contentslist'),''),'');
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

        $pagingprint = $paging->pagingprint($manage->pag_def_param());

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('contents_total',contents_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);
    }

}
