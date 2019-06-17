<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Paging;
use Manage\Func as Manage;

class Mbvisit extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/mbvisit.tpl.php');
        $this->layout()->mng_foot();
    }

    public function _func(){
        function mbtype($arr){
            if($arr['mb_id']){
                return '회원';
            }else{
                return '비회원';
            }
        }

        function visit_total($arr){
            return Func::number($arr['visit_total']);
        }

        function device_per($arr){
            if($arr['device_total']>0){
                $pc_per = @round(100 / ($arr['device_total'] / $arr['device_pc']),1);
                $mo_per = 100 - $pc_per;
                return 'pc <strong>'.$pc_per.'%</strong> / mobile <strong>'.$mo_per.'%</strong>';
            }else{
                return '-';
            }
        }

        function member_per($arr){
            if($arr['device_total']>0){
                $mb_per = @round(100 / ($arr['device_total'] / $arr['member_total']),1);
                $gu_per = 100 - $mb_per;
                return '회원 <strong>'.$mb_per.'%</strong> / 비회원 <strong>'.$gu_per.'%</strong>';
            }else{
                return '-';
            }
        }

        function user_agent($type,$arr){
            $brw = $arr['browser'];
            $agt = '';
            $os = '';

            if($type=='os'){
                if(stristr($brw,'android')){
                    $os = 'Android';
                }else if(stristr($brw,'iphone')){
                    $os = 'iPhone';
                }else if(stristr($brw,'ipad')){
                    $os = 'iPad';
                }else if(stristr($brw,'ipod')){
                    $os = 'iPod';
                }else if(stristr($brw,'macintosh')){
                    $os = 'Macintosh';
                }else if(stristr($brw,'symbianos')){
                    $os = 'SymbianOS';
                }else if(stristr($brw,'blackberry')){
                    $os = 'BlackBerry';
                }else if(stristr($brw,'bb10')){
                    $os = 'BB10';
                }else if(stristr($brw,'nokia')){
                    $os = 'Nokia';
                }else if(stristr($brw,'sonyericsson')){
                    $os = 'SonyEricsson';
                }else if(stristr($brw,'webos')){
                    $os = 'webOS';
                }else if(stristr($brw,'palmos')){
                    $os = 'PalmOS';
                }else if(stristr($brw,'linux')){
                    $os = 'LINUX';
                }else if(stristr($brw,'windows')){
                    $os = 'Windows';
                }else if(stristr($brw,'googlebot')){
                    $os = '* Googlebot';
                }else if(stristr($brw,'bingbot')){
                    $os = '* Bingbot';
                }else if(stristr($brw,'yahoobot')){
                    $os = '* Yahoobot';
                }else if(stristr($brw,'naverbot')){
                    $os = '* Naverbot';
                }else if(stristr($brw,'baiduspider')){
                    $os = '* Baiduspider';
                }else{
                    $os = '기타 OS';
                }
                return $os;

            }else if($type=='browser'){
                if(stristr($brw,'Edge')){
                    $agt = 'Edge';
                }else if(stristr($brw,'rv:11.0')){
                    $agt = 'IE 11';
                }else if(stristr($brw,'msie 10')){
                    $agt = 'IE 10';
                }else if(stristr($brw,'msie 9')){
                    $agt = 'IE 9';
                }else if(stristr($brw,'msie 8')){
                    $agt = 'IE 8';
                }else if(stristr($brw,'msie 7')){
                    $agt = 'IE 7';
                }else if(stristr($brw,'msie 6')){
                    $agt = 'IE 6';
                }else if(stristr($brw,'opera')){
                    $agt = 'Opera';
                }else if(stristr($brw,'firefox')){
                    $agt = 'Firefox';
                }else if(stristr($brw,'chrome')){
                    $agt = 'Chrome';
                }else if(stristr($brw,'safari')){
                    $agt = 'Safari';
                }else{
                    $agt = '기타 Browser';
                }
                return $agt;
            }
        }
    }

    public function _make(){
        global $PARAM,$sortby,$searchby,$orderby;

        $sql = new Pdosql();
        $paging = new Paging();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        $req = Method::request('GET','fdate,tdate');

        //date sortby
        if(!$req['fdate']){
            $req['fdate'] = date('Y-m-d');
        }
        if(!$req['tdate']){
            $req['tdate'] = date('Y-m-d');
        }

        //sortby
        $sortby = '';
        $sort_arr = array();

        $sql->query(
            $sql->scheme->member('sort:mbvisit'),
            array(
                $req['fdate'],
                $req['tdate']
            )
        );
        $sort_arr['visit_total'] = $sql->fetch('visit_total');
        $sort_arr['device_total'] = $sql->fetch('device_total');
        $sort_arr['device_pc'] = $sql->fetch('device_pc');
        $sort_arr['member_total'] = $sql->fetch('member_total');

        //orderby
        if(!$PARAM['ordtg']) $PARAM['ordtg'] = 'regdate';
        if(!$PARAM['ordsc']) $PARAM['ordsc'] = 'desc';
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $paging->thispage = PH_MANAGE_DIR.'/';
        $sql_arr = array($req['fdate'],$req['tdate']);
        $sql->query(
            $paging->paging_query(
                $sql->scheme->member('select:mbvisit'),$sql_arr
            ),$sql_arr
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['regdate'] = Func::datetime($arr['regdate']);
                $arr[0]['user_agent']['os'] = user_agent('os',$arr);
                $arr[0]['user_agent']['browser'] = user_agent('browser',$arr);

                if(!$arr['mb_level']){
                    $arr['mb_level'] = 10;
                }

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('manage',$manage);
        $this->set('keyword',$PARAM['keyword']);
        $this->set('fdate',$req['fdate']);
        $this->set('tdate',$req['tdate']);
        $this->set('visit_total',visit_total($sort_arr));
        $this->set('device_per',device_per($sort_arr));
        $this->set('member_per',member_per($sort_arr));
        $this->set('pagingprint',$paging->pagingprint($manage->pag_def_param().'&fdate='.$req['fdate'].'&tdate='.$req['tdate']));
        $this->set('print_arr',$print_arr);
    }

}
