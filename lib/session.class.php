<?php
namespace Corelib;

class Session{

    static function set_sess($name,$val){
        if($name=='MB_IDX'){
            SessionHandler::$dbinfo['mb_idx'] = $val;
        }
        $_SESSION[$name] = $val;
    }

    static function empty_sess($name){
        global $_SESSION;

        if($name=='MB_IDX'){
            SessionHandler::$dbinfo['mb_idx'] = 0;
        }
        unset($_SESSION[$name]);
    }

    static function drop_sess(){
        session_destroy();
    }

    static function sess($name){
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }else{
            return NULL;
        }
    }

    static function is_sess($name){
        if(isset($_SESSION[$name])){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}

class SessionHandler extends \Make\Database\Pdosql{

    private $value;
    private $sess_life = SET_SESS_LIFE;
    private $expiry;
    static public $dbinfo = array();

    public function open(){
        return true;
    }

    public function close(){
        return true;
    }

    public function get_scheme(){
        $this->scheme('Core\\Scheme');
    }

    public function read($key){
        $this->get_scheme();
        $this->query(
            $this->scheme->core('select:session'),
            array(
                $key
            )
        );
        $this->specialchars = 0;
        $this->nl2br = 0;

        if($this->getcount()>0){
            return $this->fetch('value');
        }else{
            $this->expiry = time()+$this->sess_life;
            $this->query(
                $this->scheme->core('insert:session'),
                array(
                    $key,
                    $this->expiry,
                    $_SERVER['REMOTE_ADDR']
                )
            );
            return $this->fetch('value');
        }
        return true;
    }

    public function write($key,$val){
        $this->get_scheme();

        $this->value = $val;
        $this->expiry = time()+$this->sess_life;

        if(isset(self::$dbinfo['mb_idx'])){
            $this->query(
                $this->scheme->core('update:session2'),
                array(
                    $this->expiry,
                    $this->value,
                    self::$dbinfo['mb_idx'],
                    $key
                )
            );
        }else{
            $this->query(
                $this->scheme->core('update:session'),
                array(
                    $this->expiry,
                    $this->value,
                    $key
                )
            );
        }
        return true;
    }

    public function destroy($key){
        $this->get_scheme();
        $this->query(
            $this->scheme->core('delete:session'),
            array(
                $key
            )
        );
        return true;
    }

    public function gc(){
        $this->get_scheme();
        $this->query(
            $this->scheme->core('gc:session'),
            ''
        );
        return true;
    }

}

$sess_init = new SessionHandler();
session_set_save_handler(
    array($sess_init,'open'),
    array($sess_init,'close'),
    array($sess_init,'read'),
    array($sess_init,'write'),
    array($sess_init,'destroy'),
    array($sess_init,'gc')
);
if(ini_get('session.auto_start')!=1){
    session_start();
}
