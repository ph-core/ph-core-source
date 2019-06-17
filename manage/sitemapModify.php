<?php
use Corelib\Method;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class SitemapModify extends \Controller\Make_Controller{

    public function _init(){
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/sitemapModify.tpl.php');
    }

    public function _func(){
        function set_checked($arr,$val){
            $setarr = array(
                'Y' => '',
                'N' => ''
            );
            foreach($setarr as $key => $value){
                if($key==$arr[$val]){
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }
    }

    public function _make(){
        $req = Method::request('GET','idx');

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->sitemap('select:sitemap'),
            array(
                $req['idx']
            )
        );
        $arr = $sql->fetchs();

        $write = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $write[$key] = $value;
            }
        }else{
            $write = null;
        }

        $this->set('visible',set_checked($arr,'visible'));
        $this->set('write',$write);
    }
}
