<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;

define('MAINPAGE',true);

class Main extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/main.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _make(){
        $req = Method::request('GET','view_dash_feed,page');

        $sql = new Pdosql();
        $paging = new Paging();

        $sql->scheme('Manage\\Scheme');

        $list_cnt = array();
        $print_arr = array();
        $pagingprint = array();

        //new member
        $sql->query(
            $sql->scheme->main('select:new_member'),''
        );
        $list_cnt['new_mb'] = $sql->getcount();

        $print_arr['new_mb'] = array();
        if($list_cnt['new_mb']>0){
            do{
                $arr = $sql->fetchs();
                $arr['mb_regdate'] = Func::datetime($arr['mb_regdate']);
                $print_arr['new_mb'][] = $arr;
            }while($sql->nextRec());
        }

        //visit member
        $sql->query(
            $sql->scheme->main('select:visit_member'),''
        );
        $list_cnt['visit_mb'] = $sql->getcount();

        $print_arr['visit_mb'] = array();
        if($list_cnt['visit_mb']>0){
            do{
                $arr = $sql->fetchs();
                if(!$arr['mb_id']){
                    $arr['mb_id'] = '비회원';
                    $arr['regdate'] = Func::datetime($arr['regdate']);
                }
                $print_arr['visit_mb'][] = $arr;
            }while($sql->nextRec());
        }

        //session member
        $sql->query(
            $sql->scheme->main('select:sess_member'),''
        );
        $list_cnt['stat_mb'] = $sql->getcount();

        $print_arr['stat_mb'] = array();
        if($list_cnt['stat_mb']>0){
            do{
                $arr = $sql->fetchs();
                if(!$arr['mb_id']){
                    $arr['mb_id'] = '비회원';
                    $arr['regdate'] = Func::datetime($arr['regdate']);
                }
                $print_arr['stat_mb'][] = $arr;
            }while($sql->nextRec());
        }

        //manage feeds
        if(isset($req['view_dash_feed'])){
            if($req['view_dash_feed']=='read_all'){

                $sql->query(
                    $sql->scheme->main('update:feeds_read_all'),''
                );

            }else{

                $sql->query(
                    $sql->scheme->main('update:feeds_read'),array(
                        $req['view_dash_feed']
                    )
                );

            }

        }

        $no_chked = 0;
        $paging->thispage = PH_MANAGE_DIR.'/';
        $paging->setlimit(20);
        $sql->query(
            $paging->paging_query(
                $sql->scheme->main('select:feeds'),''
            ),''
        );
        $list_cnt['mng_feed'] = $sql->getcount();
        $news_newfeeds_count = Func::number($sql->fetch('total'));
        $total_cnt = Func::number($paging->totalCount);
        $print_arr['mng_feed'] = array();

        if($list_cnt['mng_feed']>0){
            do{
                $arr = $sql->fetchs();
                $sql->specialchars = 0;
                $sql->nl2br = 0;
                $arr['memo'] = $sql->fetch('memo');
                $arr['regdate'] = Func::datetime($arr['regdate']);

                if($arr['chked']=='N'){
                    $no_chked++;
                }
                $print_arr['mng_feed'][] = $arr;

            }while($sql->nextRec());
        }

        $pagingprint['mng_feed'] = $paging->pagingprint('');

        $this->set('print_arr',$print_arr);
        $this->set('list_cnt',$list_cnt);
        $this->set('pagingprint',$pagingprint);
        $this->set('news_newfeeds_count',$news_newfeeds_count);
        $this->set('page',$req['page']);
    }

}
