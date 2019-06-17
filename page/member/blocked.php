<?php
use Corelib\Func;
use Corelib\Blocked as CoreBlocked;
use Make\Database\Pdosql;

class Blocked extends \Controller\Make_Controller{

    public function _init(){
        $this->common()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/blocked.tpl.php');
        $this->common()->foot();
    }

    public function _make(){
        global $MB,$ip_qry;

        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        CoreBlocked::get_qry();

        $sql->query(
            $sql->scheme->core('select:blocked'),
            array(
                $ip_qry[0],
                $ip_qry[1],
                $ip_qry[2],
                $ip_qry[3],
                $MB['idx'],
                $MB['id']
            )
        );

        if($sql->getcount()<1){
            Func::err_location('차단 내역이 없습니다.',PH_DOMAIN);
        }

        $msg = $sql->fetch('memo');

        $this->set('msg',$msg);
    }

}
