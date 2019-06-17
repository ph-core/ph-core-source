<?php
namespace Make\Database;

use Corelib\Func;

class Pdosql{

    static private $DB_HOST = DB_HOST;
    static private $DB_NAME = DB_NAME;
    static private $DB_USER = DB_USER;
    static private $DB_PWD = DB_PWD;
    static private $DB_PREFIX = DB_PREFIX;
    static private $CONN;
    public $scheme;
    private $ROW;
    private $REC_COUNT;
    private $pdo;
    private $stmt;

    //pdo 연결 초기화
    public function __construct(){
        try{
            switch(DB_ENGINE){
                default :
                    $this->pdo = new \PDO(
                        'mysql:host='.self::$DB_HOST.';dbname='.self::$DB_NAME,self::$DB_USER,self::$DB_PWD,
                        array(
                            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                        )
                    );
                    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
        }
        catch(Exception $e){
            Func::err_print($e->getMessage());
        }

        $this->specialchars = DB_SPECIALCHARS;
        $this->nl2br = DB_NL2BR;
    }

    //pdo 연결 종료
    public function close(){
        $this->pdo = null;
    }

    //Scheme
    public function scheme($className){
        $this->scheme = new $className();
    }

    //테이블 명칭 조합 후 반환
    public function table($tblName){
        $expl = explode(':',$tblName);
        $tbl = '';
        //모듈 Table인 경우
        if(count($expl)>1){
            if($expl[1]){
                $tbl = self::$DB_PREFIX.$expl[0].'_'.$expl[1];
            }else{
                $tbl = self::$DB_PREFIX.$expl[0];
            }
            //그 외 기본 Table
        }else{
            $tbl = self::$DB_PREFIX.$tblName;
        }
        return $tbl;
    }

    //Query
    public function query($query,$param){
        $this->stmt = $this->pdo->prepare($query);
        for($i=1;$i<=count($param);$i++){
            $this->stmt->bindParam(':col'.$i,$param[$i-1]);
        }
        $this->stmt->execute();
        $this->REC_COUNT = $this->stmt->rowCount();
        if(
        strpos(strtolower($query),'select')!==false &&
        (
            strpos(strtolower($query),'insert')===false &&
            strpos(strtolower($query),'update')===false
            )
        ){
            $this->ROW = $this->stmt->fetch(\PDO::FETCH_ASSOC);
        }
        $this->ROW_NUM = 0;
    }

    //레코드의 갯수를 구함
    public function getcount(){
        return $this->REC_COUNT;
    }

    //첫번째 레코드에 위치 시킴
    public function firstRec(){
        $this->ROW = $this->stmt->fetch(\PDO::FETCH_ASSOC,\PDO::FETCH_ORI_ABS,0);
    }

    //마지막 레코드에 위치 시킴
    public function lastRec(){
        $this->ROW = $this->stmt->fetch(\PDO::FETCH_ASSOC,\PDO::FETCH_ORI_LAST);
    }

    //다음 레코드에 위치 시킴
    public function nextRec(){
        $this->ROW_NUM = $this->ROW_NUM + 1;

        if($this->ROW_NUM < $this->REC_COUNT){
            $this->ROW = $this->stmt->fetch(\PDO::FETCH_ASSOC,\PDO::FETCH_ORI_REL,$this->ROW_NUM);
            return TRUE;
        }else{
            return FALSE;
        }
    }

    //이전 레코드에 위치 시킴
    public function prevRec(){
        $this->ROW_NUM = $this->ROW_NUM - 1;
        if($this->ROW_NUM>=0){
            $this->ROW = $this->stmt->fetch(\PDO::FETCH_ASSOC,\PDO::FETCH_ORI_REL,$this->ROW_NUM);
            return TRUE;
        }else{
            return FALSE;
        }
    }

    //레코드의 특정 필드 값을 가져옴
    public function fetch($fieldName){
        if(isset($this->ROW[$fieldName])){
            $this->ROW_RE = stripslashes($this->ROW[$fieldName]);
            if($this->specialchars==1){
                $this->ROW_RE = htmlspecialchars($this->ROW_RE);
            }
            if($this->nl2br==1){
                $this->ROW_RE = nl2br($this->ROW_RE);
            }
            return $this->ROW_RE;
        }else{
            return '';
        }
    }

    //레코드의 모든 필드 값을 배열로 가져옴
    public function fetchs(){
        foreach($this->ROW as $key => $value){
            $this->array[$key] = stripslashes($this->fetch($key));
        }
        return $this->array;
    }

    //여분필드 설명 처리
    public function etcfd_exp($exp){
        $ex = explode('|',$exp);
        for($i=0;$i<10;$i++){
            if(!isset($ex[$i])){
                $ex[$i] = '';
            }
        }
        return implode('|',$ex);
    }
}
