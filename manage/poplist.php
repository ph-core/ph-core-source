<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Poplist extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/poplist.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function pop_total($arr){
            return Func::number($arr['pop_total']);
        }

        function use_pop($arr){
            return Func::number($arr['use_pop']);
        }

        function notuse_pop($arr){
            return Func::number($arr['notuse_pop']);
        }

        function thumbnail($arr){
            preg_match(REGEXP_IMG,Func::htmldecode($arr['html']),$match);

            if(isset($match[0])){
                $src = str_replace(PH_DATA_DIR.PH_PLUGIN_CKEDITOR.'/',PH_DATA_DIR.PH_PLUGIN_CKEDITOR.'/thumb/',$match[1]);
                $tmb = $src;
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
            $sql->scheme->popup('sort:poplist'),''
        );
        $sort_arr['pop_total'] = $sql->fetch('pop_total');
        $sort_arr['use_pop'] = $sql->fetch('use_pop');
        $sort_arr['notuse_pop'] = $sql->fetch('notuse_pop');

        switch($PARAM['sort']){
            case 'usepop' :
            $sortby = 'AND show_from<now() AND show_to>now()';
            break;
            case 'nousepop' :
            $sortby = 'AND (show_from>now() OR show_to<now())';
            break;
        }

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql->query(
            $paging->paging_query(
                $sql->scheme->popup('select:poplist'),''
            ),''
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['show'] = Func::date($arr['show_from']).' ~ '.Func::date($arr['show_to']);
                $arr['level'] = $arr['level_from'].' ~ '.$arr['level_to'];
                $arr['no'] = $paging->getnum();
                $arr['regdate'] = Func::datetime($arr['regdate']);
                $arr[0]['thumbnail'] = thumbnail($arr);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('pop_total',pop_total($sort_arr));
        $this->set('use_pop',use_pop($sort_arr));
        $this->set('notuse_pop',notuse_pop($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr',$print_arr);

    }

}
