<?php
namespace Corelib;

use Corelib\Func;

class Method{

    static function parse($var){
        foreach($var as $key => $value){
            global $$key;
            $$key = $value;
        }
    }

    static public function request($type,$name){
        $return_arr = array();
        if($type=='GET'){
            global $_GET;
            $expl = explode(',',$name);
            if(sizeof($expl)>0){
                for($i=0;$i<sizeof($expl);$i++){
                    if(isset($_GET[$expl[$i]])){
                        if(!is_array($_GET[$expl[$i]])){
                            $return_arr[$expl[$i]] = addslashes($_GET[$expl[$i]]);
                        }else{
                            $return_arr[$expl[$i]] = $_GET[$expl[$i]];
                        }
                    }else{
                        $return_arr[$expl[$i]] = NULL;
                    }
                }
            }
            return $return_arr;

        }else if($type=='POST'){
            global $_POST;
            $expl = explode(',',$name);
            if(sizeof($expl)>0){
                for($i=0;$i<sizeof($expl);$i++){
                    if(isset($_POST[$expl[$i]])){
                        if(!is_array($_POST[$expl[$i]])){
                            $return_arr[$expl[$i]] = addslashes($_POST[$expl[$i]]);
                        }else{
                            $return_arr[$expl[$i]] = $_POST[$expl[$i]];
                        }
                    }else{
                        $return_arr[$expl[$i]] = NULL;
                    }
                }
            }
            return $return_arr;

        }else if($type=='FILE'){
            global $_FILES;
            $expl = explode(',',$name);
            if(sizeof($expl)>0){
                for($i=0;$i<sizeof($expl);$i++){
                    if(isset($_FILES[$expl[$i]])){
                        $return_arr[$expl[$i]] = $_FILES[$expl[$i]];
                    }else{
                        $return_arr[$expl[$i]] = NULL;
                    }
                }
            }
            return $return_arr;
        }
    }

    static function security($type){
        global $_SERVER,$REQUEST_METHOD;

        $func = new Func();
        switch($type){
            case 'REFERER' :
            if(!isset($_SERVER['HTTP_REFERER']) || !preg_match(";$_SERVER[HTTP_HOST];",$_SERVER['HTTP_REFERER'])){
                $func->err_print(ERR_MSG_1);
            }
            break;
            case 'REQUEST_GET' :
            if(getenv('REQUEST_METHOD')=='POST'){
                $func->err_print(ERR_MSG_1);
            }
            break;
            case 'REQUEST_POST' :
            if(getenv('REQUEST_METHOD')=='GET'){
                $func->err_print(ERR_MSG_1);
            }
            break;
        }
    }
}
