<?php
namespace Manage;

use Corelib\Method;

class Func{

    public function gosite(){
        return PH_DOMAIN;
    }

    public function signout_link(){
        return PH_DIR.'/member/signout';
    }

    public function adminfo_link(){
        return PH_MANAGE_DIR.'/?href=adminfo';
    }

    public function module_total(){
        global $MODULE;
        return count($MODULE);
    }

    public function req_hidden_inp($type){
        global $PARAM;
        $PARAM = Method::request($type,'page,mode,href,p,sort,ordtg,ordsc,where,keyword');
    }

    public function retlink($param){
        global $PARAM;
        return '&page='.$PARAM['page'].'&sort='.$PARAM['sort'].'&ordtg='.$PARAM['ordtg'].'&ordsc='.$PARAM['ordsc'].'&where='.$PARAM['where'].'&keyword='.$PARAM['keyword'].$param;
    }

    public function href_type(){
        global $PARAM;

        if(isset($PARAM['mod']) && $PARAM['mod']!=''){
            return 'mod';
        }else if(isset($PARAM['href']) && $PARAM['href']!=''){
            return 'def';
        }
    }

    public function sch_where($wh){
        global $PARAM;
        if($PARAM['where']==$wh){
            return 'selected';
        }
    }

    public function sortlink($param){
        global $PARAM;

        if(isset($PARAM['mod']) && $PARAM['mod']!=''){
            return PH_MANAGE_DIR.'/?mod='.$PARAM['mod'].'&href='.$PARAM['href'].'&p='.$PARAM['p'].$param;
        }else if(isset($PARAM['href']) && $PARAM['href']!=''){
            return PH_MANAGE_DIR.'/?href='.$PARAM['href'].'&p='.$PARAM['p'].$param;
        }
    }

    public function orderlink($tg){
        global $PARAM;
        if($PARAM['ordtg']==$tg && $PARAM['ordsc']=='asc'){
            $sc = 'desc';
        }else{
            $sc = 'asc';
        }
        if($PARAM['keyword']){
            $sch = '&where='.$PARAM['where'].'&keyword='.urlencode($PARAM['keyword']);
        }else{
            $sch = '';
        }
        $etc_var = '';
        if(isset($PARAM[0])){
            foreach($PARAM[0] as $key=>$value){
                $etc_var .= '&'.$key.'='.$value;
            }
        }
        return $this->sortlink('&sort='.$PARAM['sort'].'&ordtg='.$tg.'&ordsc='.$sc.$sch.$etc_var);
    }

    public function pag_def_param(){
        global $PARAM;
        return '&mod='.$PARAM['mod'].'&href='.$PARAM['href'].'&p='.$PARAM['p'].'&sort='.$PARAM['sort'].'&ordtg='.$PARAM['ordtg'].'&ordsc='.$PARAM['ordsc'].'&where='.$PARAM['where'].'&keyword='.urlencode($PARAM['keyword']);
    }

    public function lnk_def_param(){
        global $PARAM;
        return '&page='.$PARAM['page'].'&sort='.$PARAM['sort'].'&ordtg='.$PARAM['ordtg'].'&ordsc='.$PARAM['ordsc'].'&where='.$PARAM['where'].'&keyword='.urlencode($PARAM['keyword']);
    }

    public function print_hidden_inp(){
        global $PARAM;
        echo '
            <input type="hidden" name="page" value="'.$PARAM['page'].'" />
            <input type="hidden" name="mod" value="'.$PARAM['mod'].'" />
            <input type="hidden" name="href" value="'.$PARAM['href'].'" />
            <input type="hidden" name="p" value="'.$PARAM['p'].'" />
            <input type="hidden" name="sort" value="'.$PARAM['sort'].'" />
            <input type="hidden" name="ordtg" value="'.$PARAM['ordtg'].'" />
            <input type="hidden" name="ordsc" value="'.$PARAM['ordsc'].'" />
        ';
    }

    public function make_target($tab){
        global $target_tabs;
        $target_tabs = explode('|',$tab);
    }

    public function print_target(){
        global $target_tabs;
        $tab_arr = array();
        for($i=0;$i<count($target_tabs);$i++){
            $html = '<ul id="target'.$i.'" class="tab1">';
            for($j=0;$j<count($target_tabs);$j++){
                $chked = '';
                if($j==$i){
                    $chked = 'on';
                }
                $html .= '<li class="'.$chked.'"><a href="#target'.$j.'">'.$target_tabs[$j].'</a></li>';
            }
            $html .= '</ul>';
            $tab_arr[$i] = $html;
        }
        return $tab_arr;
    }

}
