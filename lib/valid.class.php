<?php
namespace Corelib;

class Valid{

    static public $return;
    static public $msg;
    static public $location;
    static public $function;
    static public $document;
    static public $element;
    static public $input;
    static public $err_code;

    static private function trim_val($val){
        return trim($val);
    }

    static public function set($arr){
        foreach($arr as $key => $value){
            self::$$key = $value;
        }
    }

    static public function success(){
        switch(self::$return){
            case 'alert->location' :
                echo '
                    [
                        {
                            "success" : "alert->location",
                            "opt" : [
                                {
                                    "msg" : "'.self::$msg.'",
                                    "location" : "'.self::$location.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'alert->reload' :
                echo '
                    [
                        {
                            "success" : "alert->reload",
                            "opt" : [
                                {
                                    "msg" : "'.self::$msg.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'callback' :
                echo '
                    [
                        {
                            "success" : "callback",
                            "opt" : [
                                {
                                    "function" : "'.self::$function.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'callback-txt' :
                echo '
                    [
                        {
                            "success" : "callback-txt",
                            "opt" : [
                                {
                                    "msg" : "'.self::$msg.'",
                                    "element" : "'.self::$element.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'alert->close->opener-reload' :
                echo '
                    [
                        {
                            "success" : "alert->close->opener-reload",
                            "opt" : [
                                {
                                    "msg" : "'.self::$msg.'",
                                    "location" : "'.self::$location.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'ajax-load' :
                echo '
                    [
                        {
                            "success" : "ajax-load",
                            "opt" : [
                                {
                                    "document" : "'.self::$document.'",
                                    "element" : "'.self::$element.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'ajax-validt' :
                echo '
                    [
                        {
                            "success" : "ajax-validt",
                            "opt" : [
                                {
                                    "msg" : "'.self::$msg.'"
                                }
                            ]
                        }
                    ]
                ';
                break;

            case 'none' :
                echo '
                    [
                        {
                            "success" : "none",
                            "opt" : ""
                        }
                    ]
                ';
                break;

            case 'error' :
                echo '
                    [
                        {
                            "success" : "error",
                            "opt" : [
                                {
                                    "input" : "'.self::$input.'",
                                    "err_code" : "'.self::$err_code.'",
                                    "msg" : "'.self::$msg.'"
                                }
                            ]
                        }
                    ]
                ';
                break;
        }
        exit;
    }

    //무조건 error 출력
    static public function error($inp,$msg){
        self::set(
            array(
                'return' => 'error',
                'input' => $inp,
                'msg' => $msg
            )
        );
        self::success();
    }

    //정규식으로 검사 후 에러 출력
    static private function match($inp,$val,$match,$msg){
        if(self::trim_val($val)!='' && !preg_match($match,$val)){
            self::error($inp,$msg);
        }
    }

    //글자수만 검사 후 에러 출력
    static public function strlen($inp,$val,$minLen,$maxLen,$null,$msg){
        ob_start();
        mb_internal_encoding('UTF-8');
        if($minLen==''){
            $minLen = 0;
        }
        if(self::trim_val($val)!=''){
            if(mb_strlen($val)<$minLen || $maxLen!='' && mb_strlen($val)>$maxLen){
                self::error($inp,$msg);
            }

        }
        if($null==1){
            self::notnull($inp,$val,'');
        }
    }

    //공백인지 검사
    static public function notnull($inp,$val,$msg){
        if(trim($val)==''){
            if(trim($msg)==''){
                self::$err_code = 'ERR_NULL';
            }else{
                self::$err_code = '';
            }
            self::error($inp,$msg);
        }
    }

    //체크박스 체크 유무 검사
    static public function checked($inp,$val,$msg){
        if($val!='checked'){
            self::error($inp,$msg);
        }
    }

    //셀렉트박스 선택 유무 검사
    static public function selected($inp,$val,$msg){
        global $val;
        if($val=='none' || $val==''){
            self::error($inp,$msg);
        }
    }

    //이메일인지 검사
    static public function isemail($inp,$val,$null,$msg){
        $match = REGEXP_EMAIL;
        self::match($inp,$val,$match,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //한글인지 검사
    static public function iskor($inp,$val,$minLen,$maxLen,$null,$msg){
        $match = REGEXP_KOR;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,$minLen,$maxLen,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //이름인지 검사
    static public function isnick($inp,$val,$null,$msg){
        $match = REGEXP_NICK;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,2,12,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //패스워드인지 검사
    static public function ispwd($inp,$val,$null,$msg){
        self::strlen($inp,$val,5,50,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //연락처인지 검사
    static public function isphone($inp,$val,$null,$msg){
        $match = REGEXP_PHONE;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,8,15,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //숫자인지 검사
    static public function isnum($inp,$val,$minLen,$maxLen,$null,$msg){
        $match = REGEXP_NUM;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,$minLen,$maxLen,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //숫자인지 검사 (음수 포함)
    static public function isneganum($inp,$val,$minLen,$maxLen,$null,$msg){
        $match = REGEXP_NEGANUM;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,$minLen,$maxLen,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //idx인지 검사
    static public function isidx($inp,$val,$null,$msg){
        $match = REGEXP_IDX;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,3,15,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //ID인지 검사
    static public function isid($inp,$val,$null,$msg){
        $match = REGEXP_ID;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,5,30,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //영어인지 검사
    static public function iseng($inp,$val,$minLen,$maxLen,$null,$msg){
        $match = REGEXP_ENG;
        self::match($inp,$val,$match,$msg);
        self::strlen($inp,$val,$minLen,$maxLen,$null,$msg);
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }

    //사용금지 태그 사용했는지 검사
    static public function chktag($inp,$val,$null,$msg){
        $not_tags = SET_INTDICT_TAGS;
        $not_tags_ex = explode(',',$not_tags);
        for($i=0;$i<count($not_tags_ex);$i++){
            if(stristr($val,'<'.$not_tags_ex[$i]) || stristr($val,'</'.$not_tags_ex[$i])){
                if($msg==''){
                    $msg = ERR_MSG_2;
                }
                self::error($inp,$msg);
                return;
            }
        }
        if($null==1){
            self::notnull($inp,$val,$msg);
        }
    }
}
