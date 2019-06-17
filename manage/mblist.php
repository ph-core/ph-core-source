<?php
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Mblist extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/mblist.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function mb_total($arr){
            return Func::number($arr['mb_total']);
        }

        function emchk_total($arr){
            return Func::number($arr['emchk_total']);
        }

        function namechk_total($arr){
            return Func::number($arr['mb_total'] - $arr['emchk_total']);
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
            $sql->scheme->member('sort:mblist'),''
        );
        $sort_arr['mb_total'] = $sql->fetch('mb_total');
        $sort_arr['emchk_total'] = $sql->fetch('emchk_total');

        switch($PARAM['sort']){
            case 'emchk' :
            $sortby = 'AND mb_email_chk=\'Y\'';
            break;
            case 'noemchk' :
            $sortby = 'AND mb_email_chk=\'N\'';
            break;
        }

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'mb_regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->member('select:mblist'),''
            ),''
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['mb_point'] = Func::number($arr['mb_point']);
                $arr['mb_regdate'] = Func::datetime($arr['mb_regdate']);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('mb_total',mb_total($sort_arr));
        $this->set('emchk_total',emchk_total($sort_arr));
        $this->set('namechk_total',namechk_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);

    }

}
