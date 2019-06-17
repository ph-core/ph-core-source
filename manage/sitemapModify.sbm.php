<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','idx,title,href,visible');

        Valid::notnull('title',$req['title'],'');
        Valid::notnull('href',$req['href'],'');

        $sql->query(
            $sql->scheme->sitemap('update:sitemap'),
            array(
                $req['title'],
                $req['href'],
                $req['visible'],
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'sitemap_list.action(\'secc_modify\');'
            )
        );
        Valid::success();
    }

}
