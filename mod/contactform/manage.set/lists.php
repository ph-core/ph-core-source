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
        $this->load_tpl(MOD_CONTACTFORM_PATH.'/manage.set/html/lists.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function contactform_total($arr){
            return Func::number($arr['contactform_total']);
        }

        function print_name($arr){
            if($arr['mb_idx']!=0){
                return '<a href="./?href=mblist&p=modifymb&idx='.$arr['mb_idx'].'">'.$arr['name'].'</a>';
            }else{
                return $arr['name'];
            }
        }

        function print_reply($arr){
            if($arr['rep_idx']!=0){
                return '<strong>완료</strong>';
            }else{
                return '대기';
            }
        }
    }

    public function _make(){
        global $PARAM,$sortby,$searchby,$orderby;

        $sql = new Pdosql();
        $paging = new Paging();
        $manage = new Manage();

        $sql->scheme('Module\\Contactform\\Scheme');

        //sortby
        $sortby = '';
        $sort_arr = array();

        $sql->query(
            $sql->scheme->manage('sort:contact'),''
        );
        $sort_arr['contactform_total'] = $sql->fetch('contactform_total');

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->manage('select:contacts'),''
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
                $arr['article'] = Func::strcut(strip_tags($arr['article']),0,30);
                $arr[0]['print_name'] = print_name($arr);
                $arr[0]['print_reply'] = print_reply($arr);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $pagingprint = $paging->pagingprint($manage->pag_def_param());

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('contactform_total',contactform_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);
    }

}
