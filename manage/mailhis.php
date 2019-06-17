<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Mailhis extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/mailhis.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function sent_total($arr){
            return Func::number($arr['total']);
        }

        function to_mb_total($arr){
            return Func::number($arr['to_mb_total']);
        }

        function level_from_total($arr){
            return Func::number($arr['level_from_total']);
        }

        function print_level($arr){
            global $MB;
            if($arr['to_mb']){
                return '특정 회원 지정';
            }else{
                return $arr['level_from'].' ('.$MB['type'][$arr['level_from']].') ~ '.$arr['level_to'].' ('.$MB['type'][$arr['level_to']].')';
            }
        }

        function print_to_mb($arr){
            global $MB;
            if(!$arr['to_mb']){
                return '수신 범위 지정';
            }else{
                return $arr['to_mb'];
            }
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
            $sql->scheme->sendmail('sort:mailhis'),''
        );
        $sort_arr['total'] = $sql->fetch('total');
        $sort_arr['to_mb_total'] = $sql->fetch('to_mb_total');
        $sort_arr['level_from_total'] = $sql->fetch('level_from_total');

        switch($PARAM['sort']){
            case 'to_mb' :
            $sortby = 'AND to_mb IS NOT NULL AND to_mb!=\'\'';
            break;
            case 'level_from' :
            $sortby = 'AND to_mb IS NULL OR to_mb=\'\'';
            break;
        }

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query($paging->paging_query($sql->scheme->sendmail('select:sendmails'),''),'');
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['regdate'] = Func::datetime($arr['regdate']);
                $arr[0]['print_level'] = print_level($arr);
                $arr[0]['print_to_mb'] = print_to_mb($arr);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('sent_total',sent_total($sort_arr));
        $this->set('to_mb_total',to_mb_total($sort_arr));
        $this->set('level_from_total',level_from_total($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);
    }

}
