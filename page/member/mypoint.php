<?php
use Corelib\Func;
use Make\Library\Paging;
use Make\Database\Pdosql;

class Mypoint extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/mypoint.tpl.php');
        $this->layout()->foot();
    }

    public function _make(){
        global $MB;

        $sql = new Pdosql();
        $paging = new Paging();

        $sql->scheme('Core\\Scheme');

        Func::getlogin(SET_NOAUTH_MSG);

        $paging->setlimit(SET_LIST_LIMIT);
        $sql->query(
            $paging->paging_query(
                $sql->scheme->member('select:mbpointlist'),
                array(MB_IDX)
            ),
            array(MB_IDX)
        );
        $print_arr = array();

        if($sql->getcount()>0){
            do{
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['regdate'] = Func::datetime($arr['regdate']);
                $arr['p_in'] = Func::number($arr['p_in']);
                $arr['p_out'] = Func::number($arr['p_out']);

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('print_arr',$print_arr);
        $this->set('pagingprint',$paging->pagingprint(''));
        $this->set('total_point',Func::number($MB['point']));
    }

}
