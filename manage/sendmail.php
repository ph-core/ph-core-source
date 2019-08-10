<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Sendmail extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/sendmail.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','sendmailForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/sendmail.sbm.php');
        $form->run();
    }

    public function _func(){
        function tpl_opts(){
            $sql = new Pdosql();

            $sql->scheme('Manage\\Scheme');

            $sql->query(
                $sql->scheme->mailtpl('select:mailtpl3'),''
            );

            $opts = '';

            if($sql->getcount()>0){
                do{
                    $arr = $sql->fetchs();
                    $opts .= '<option value="'.$arr['type'].'">'.$arr['type'].' ('.$arr['title'].')</option>';
                }while($sql->nextRec());
            }
            return $opts;
        }
    }

    public function _make(){
        $manage = new Manage();

        $req = Method::request('GET','mailto');

        $this->set('manage',$manage);
        $this->set('mailto',$req['mailto']);
        $this->set('tpl_opts',tpl_opts());
    }

}
