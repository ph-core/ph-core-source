<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['type']){
            case 'add' :
            $this->get_add();
            break;

            case 'modify' :
            $this->get_modify();
            break;
        }
    }

    public function _make(){
        global $req;

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','type,idx,org_caidx,caidx,new_caidx');
    }

    //////////////////////////////
    // add
    //////////////////////////////
    public function get_add(){
        global $req;

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->sitemap('select:sitemap_rct'),''
        );

        $recent_idx = $sql->fetch('idx');
        if($recent_idx){
            $recent_idx++;
        }else{
            $recent_idx = 1;
        }

        switch(strlen($req['new_caidx'])){
            case 4 :
            $new_depth = 1;
            break;
            case 8 :
            $new_len = 8;
            $new_depth = 2;
            break;
            case 12 :
            $new_len = 12;
            $new_depth = 3;
            break;
        }

        if($new_depth>=2){
            $prt_caidx = substr($req['new_caidx'],0,$new_len-4);
            $sql->query(
                $sql->scheme->sitemap('select:children_count'),
                array(
                    $prt_caidx.'%'
                )
            );
            $children_count = $sql->fetch('count');

            $sql->query(
                $sql->scheme->sitemap('update:parent'),
                array(
                    $children_count,
                    $prt_caidx
                )
            );
        }

        $sql->query(
            $sql->scheme->sitemap('insert:sitemap'),
            array(
                $recent_idx,
                $req['new_caidx'],
                '새로운 '.$new_depth.'차 카테고리',
                0
            )
        );

        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'sitemap_list.action(\'list_reload\');'
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // modify
    //////////////////////////////
    public function get_modify(){
        global $req,$where;

        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        $where = '';

        if(count($req['idx'])<1){
            $where = 'idx!=-1';
        }else{
            for($i=0;$i<count($req['idx']);$i++){
                if($i==0){
                    $where .= 'idx!=\''.$req['idx'][$i].'\'';
                }else{
                    $where .= ' AND idx!=\''.$req['idx'][$i].'\'';
                }
            }
        }

        $sql->query(
            $sql->scheme->sitemap('delete:sitemap'),''
        );

        $children_count = array();
        for($i=0;$i<count($req['idx']);$i++){
            $sql->query(
                $sql->scheme->sitemap('select:children_count'),
                array(
                    $req['org_caidx'][$i].'%'
                )
            );
            $children_count[$i] = $sql->fetch('count') - 1;
        }

        for($i=0;$i<count($req['idx']);$i++){
            $sql->query(
                $sql->scheme->sitemap('update:sitemap2'),
                array(
                    $req['caidx'][$i],
                    $children_count[$i],
                    $req['idx'][$i]
                )
            );
        }

        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'sitemap_list.action(\'list_reload\');'
            )
        );
        Valid::success();
    }
}
