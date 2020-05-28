<?php
namespace Corelib;

use Make\Database\Pdosql;

class Func{

    static public function add_stylesheet($file){
        global $ob_src_css;
        if(!strstr($ob_src_css,$file)){
            $ob_src_css .= '<link rel="stylesheet" href="'.$file.'"/>'.PHP_EOL;
        }
    }

    static public function add_javascript($file){
        global $ob_src_js;
        if(!strstr($ob_src_js,$file)){
            $ob_src_js .= '<script src="'.$file.'"></script>'.PHP_EOL;
        }
    }

    static public function define_javascript($name,$val){
        global $ob_define_js;
        $ob_define_js .= PHP_EOL.'var '.$name.' = "'.$val.'";';
    }

    static public function print_javascript($source){
        global $ob_src_js;
        $ob_src_js .= '<script type="text/javascript">'.PHP_EOL;
        $ob_src_js .= $source.PHP_EOL;
        $ob_src_js .= '</script>'.PHP_EOL;
    }

    static public function add_title($title){
        global $CONF,$ob_title,$ob_ogtitle;
        $ob_title .= '<title>'.$CONF['title'].' - '.$title.'</title>'.PHP_EOL;
        $ob_ogtitle .= '<meta property="og:title" content="'.$CONF['og_title'].' - '.$title.'" />'.PHP_EOL;
    }

    //page key 셋팅
    static public function set_category_key($key){
        define('SET_CATEGORY_KEY',$key);
    }

    //Date Format (날짜만)
    static public function date($str){
        if($str!=''){
            return date(SET_DATE,strtotime($str));
        }else{
            return '';
        }
    }

    //Date Format (날짜와 시간)
    static public function datetime($str){
        if($str!=''){
            return date(SET_DATETIME,strtotime($str));
        }else{
            return '';
        }
    }

    //Number로 치환
    static public function number($str){
        return number_format((int)$str);
    }

    //파일 사이즈 단위 계산
    static public function getbyte($size,$byte){
        if($byte=='K'){
            $size = number_format((int)$size/1024,0);
        }else if($byte=='M'){
            $size = number_format((int)$size/1024/1024,1);
        }else if($byte=='G'){
            $size = number_format((int)$size/1024/1024/1024,1);
        }
        return $size;
    }

    //file_get_contents 대체함수 (curl)
    static public function url_get_contents($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //파일 사이즈 표시
    static public function filesize($file,$byte){
        if($byte=='K'){
            $size = number_format(filesize($file)/1024,0);
        }else if($byte=='M'){
            $size = number_format(filesize($file)/1024/1024,1);
        }else if($byte=='G'){
            $size = number_format(filesize($file)/1024/1024/1024,1);
        }
        return $size;
    }

    //로그인이 되어있지 않다면 로그인 화면으로 이동
    static public function getlogin($msg){
        if(!IS_MEMBER){
            if($msg){
                self::alert($msg);
            }
            $url = $_SERVER['REQUEST_URI'];
            self::location_parent(PH_DOMAIN.'/member/signin?redirect='.urlencode($url));
        }
    }

    //회원 level 체크
    static public function chklevel($level){
        global $MB;
        if($MB['level']>$level){
            self::err_back(ERR_MSG_10);
        }
    }

    //Device 체크
    static public function chkdevice(){
        $mobile = explode(',',SET_MOBILE_DEVICE);
        $chk_count = 0;
        for($i=0;$i<sizeof($mobile);$i++){
            if(preg_match("/$mobile[$i]/",strtolower($_SERVER['HTTP_USER_AGENT']))){
                $chk_count++;
                break;
            }
        }
        if($chk_count>0){
            return 'mobile';
        }else{
            return 'pc';
        }
    }

    //문자열 유효성 검사
    static public function chkintd($type,$val,$intd){
        $intd = explode(',',$intd);
        $chk = true;
        for($i=0;$i<=sizeof($intd)-1;$i++){
            if(strpos($val,$intd[$i])!==false){
                $chk = false;
            }
        }
        if($type=='notmatch'){
            if($chk===false){
                return false;
            }else{
                return true;
            }
        }else if($type=='match'){
            if($chk===false){
                return true;
            }else{
                return false;
            }
        }
    }

    //문자열 자르기
    static public function strcut($str,$start,$end){
        $cutstr = mb_substr($str,$start,$end,'UTF-8');
        if(strlen($cutstr)<strlen($str)){
            return $cutstr.'···';
        }else{
            return $cutstr;
        }
    }

    //회원 포인트 적립 or 차감 처리
    static public function set_mbpoint($mb_idx,$mode,$point,$memo){
        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        if(!$mb_idx || $mb_idx<1 || $point<1){
            return;
        }
        $sql->query(
            $sql->scheme->member('select:mbpoint'),
            array(
                $mb_idx
            )
        );
        $mb_point = $sql->fetch('mb_point');

        if($mode=='in'){
            $set_point = $mb_point + $point;
            $sql->query(
                $sql->scheme->member('insert:pointin'),
                array(
                    $mb_idx,
                    $point,
                    $memo
                )
            );
        }
        if($mode=='out'){
            if($mb_point<$point){
                $set_point = 0;
                $memo .= ' (차감할 포인트 부족으로 0 처리)';
            }else{
                $set_point = $mb_point - $point;
            }
            $sql->query(
                $sql->scheme->member('insert:pointout'),
                array(
                    $mb_idx,
                    $point,
                    $memo
                )
            );
        }
        $sql->query(
            $sql->scheme->member('update:mbpoint'),
            array(
                $set_point,
                $mb_idx
            )
        );
    }

    //관리자 최근 피드에 등록
    static public function add_mng_feed($arr){
        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        $sql->query(
            $sql->scheme->manage('insert:addfeeds'),
            array(
                $arr[0],
                $arr[1],
                $arr[2]
            )
        );
    }

    //Captcha 출력 및 검증
    static public function get_captcha($id,$type){
        global $CONF,$PLUGIN_CAPTCHA_CONF;
        if($id==''){
            $id = 'captcha';
        }
        $PLUGIN_CAPTCHA_CONF['id'] = $id;

        if($CONF['use_recaptcha']=='Y'){
            self::add_javascript('https://www.google.com/recaptcha/api.js?onload=g_recaptcha_'.$id.'&render=explicit');
            self::print_javascript('var g_recaptcha_'.$id.'_act = 0;var g_recaptcha_'.$id.'_aload = '.$type.';var g_recaptcha_'.$id.'_rend;var g_recaptcha_'.$id.' = function(aload){if((g_recaptcha_'.$id.'_aload==1 || aload==1) && document.getElementById(\'g-recaptcha-'.$id.'\')){g_recaptcha_'.$id.'_rend = grecaptcha.render(\'g-recaptcha-'.$id.'\',{\'sitekey\' : \''.$CONF['recaptcha_key1'].'\'});}g_recaptcha_'.$id.'_act++;}');
            $html = '<div class="g-recaptcha" id="g-recaptcha-'.$id.'" data-name="'.$id.'"></div>';
            $html .= '<textarea name="'.$id.'" id="'.$id.'" style="display: none;"></textarea>';
        }else{
            ob_start();
            include PH_PLUGIN_PATH."/".PH_PLUGIN_CAPTCHA."/getimg.php";
            $html = ob_get_contents();
            ob_end_clean();
        }
        return $html;
    }

    static public function chk_captcha($val){
        global $CONF;
        if($CONF['use_recaptcha']=='Y'){
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$CONF['recaptcha_key2'].'&response='.$val.'&remoteip='.$_SERVER['REMOTE_ADDR'];
            $req = json_decode(self::url_get_contents($url),true);
            if($req['success']){
                return true;
            }else{
                return false;
            }
        }else{
            include_once PH_PLUGIN_PATH.'/captcha/zmSpamFree.php';
            if(zsfCheck($val,'')){
                return true;
            }else{
                return false;
            }
        }
    }

    //파일 확장명 추출
    static public function get_filetype($file){
        $fn = explode('.',$file);
        $fn = array_pop($fn);
        return strtolower($fn);
    }

    //현재 PHP 파일명 반환
    static public function thispage(){
        return basename($_SERVER['PHP_SELF']);
    }

    //현재 PHP 경로(Directory) 반환
    static public function thisdir(){
        return str_replace('/'.basename(self::thisuri()),'',self::thisuri());
    }

    //현재 PHP uri 제외한 전체 경로 반환
    static public function thisurl(){
        return self::thisdir().'/'.self::thispage();
    }

    //현재 URI 반환
    static public function thisuri(){
        $uri = $_SERVER['REQUEST_URI'];
        $qry = substr($_SERVER['QUERY_STRING'],strpos($_SERVER['QUERY_STRING'],'&')+1);
        $uri = str_replace('?'.$qry,'',$uri);
        return $uri;
    }

    //현재 URI 반환 (쿼리 포함)
    static public function thisuriqry(){
        $uri = $_SERVER['REQUEST_URI'];
        return $uri;
    }

    //htmlspecialchars_decode+br2nl 리턴 함수
    //(mysql에서 Array된 변수값은 htmlspecialchars+nl2br이 기본 적용됨)
    static public function htmldecode($val){
        return self::deHtmlspecialchars(self::br2nl($val));
    }

    //deHtmlspecialchars 함수
    static public function deHtmlspecialchars($val){
        return htmlspecialchars_decode($val);
    }

    //br2nl 함수
    static public function br2nl($val){
        return preg_replace("/\<br(\s*)?\/?\>/i",'\n',$val);
    }

    //error : alert 띄운 뒤 오류메시지 화면에 뿌림
    static public function err_print($msg){
        echo $msg;
        exit;
    }

    //error : alert만 띄움
    static public function err($msg){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert(\''.$msg.'\');</script>';
        exit;
    }

    //error : alert 띄운 뒤 뒤로 이동
    static public function err_back($msg){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert(\''.$msg.'\');history.back();</script>';
        exit;
    }

    //error : alert 띄운 뒤 설정한 페이지로 이동
    static public function err_location($msg,$url){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert(\''.$msg.'\');location.href=\''.$url.'\';</script>';
        exit;
    }

    //error : alert 띄운 뒤 윈도우 창 닫음
    static public function err_close($msg){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert(\''.$msg.'\');self.close();</script>';
        exit;
    }

    //exit 없는 alert 띄움
    static public function alert($msg){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert(\''.$msg.'\');</script>';
    }

    //페이지 이동
    static public function location($url){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">location.href=\''.$url.'\';</script>';
        exit;
    }

    //페이지 이동(_parent)
    static public function location_parent($url){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">parent.location.href=\''.$url.'\';</script>';
        exit;
    }

}
