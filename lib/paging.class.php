<?php
namespace Make\Library;

use Corelib\Func;
use Corelib\Method;

class Paging extends \Make\Database\Pdosql{

    public $page = 1;
    public $total = 0;
    public $listPerPage = SET_LIST_LIMIT;
    public $totalPage;
    public $totalCount;
    public $printPerList = 5;
    public $addParam;
    public $listno = 0;
    public $thispage;
    private $firstPage;
    private $lastPage;
    private $prePage;
    private $nextPage;

    public function request(){
        $method = new Method();
        $req = $method->request('GET','page');
        if(!$req['page']){
            $page = 1;
        }else{
            $page = $req['page'];
        }
        $this->page = $req['page'];
        if(!$this->page){
            $this->page = 1;
        }
    }
    public function gettotal($total){
        $this->total = $total;
    }
    public function paging_query($sql,$param){
        $this->listno = 0;
        $this->request();
        $this->query($sql,$param);
        $this->totalCount = $this->getcount();
        $this->gettotal($this->getcount()); //Data의 전체 갯수
        return $sql.$this->setpaging();
    }
    public function setpaging(){
        $limit = ($this->page-1)*$this->listPerPage.",".$this->listPerPage;
        return " limit $limit";
    }
    public function setlimit($listPerPage){
        $this->listPerPage = $listPerPage;
    }
    public function getnumStart(){
        return ($this->total-($this->listPerPage*($this->page-1)));
    }
    public function getnum(){
        $no = $this->getnumStart()-$this->listno;
        $this->listno++;
        return $no;
    }
    public function setparam($addParam){
        if(is_string($addParam)){
            $this->addParam = $addParam;
        }else{
            $this->addParam = basename($_SERVER['REQUEST_URI']);
        }
    }
    public function setmax($printPerList){
        $this->printPerList = $printPerList;
    }
    //페이지 범위 계산
    private function setnav(){
        $this->totalPage = ceil($this->total/$this->listPerPage);
        $this->startPage = (floor(($this->page-1)/$this->printPerList)*$this->printPerList)+1;
        $this->endPage = $this->startPage+$this->printPerList-1;
        if($this->endPage>$this->totalPage){
            $this->endPage = $this->totalPage;
        }
        $this->prePage = $this->startPage-1;
        $this->nextPage = $this->endPage+1;
    }

    //페이징 출력
    public function pagingprint($addParam){
        if(isset($addParam)){
            $this->setparam($addParam);
        }
        if($this->thispage){
            $thispage = $this->thispage;
        }else{
            $thispage = Func::thisuri();
        }
        if($this->total>0){
            $this->setnav();
            $prn = array();
            $prn[] = "<ul class=\"paging\">";
            if($this->startPage!=1){
                $prn[] = "<li class=\"first\"><a href=\"{$thispage}?page=1{$this->addParam}\"><i class=\"fa fa-angle-double-left\"></i></a></li>";
            }
            if($this->startPage!=1){
                $prn[] = "<li class=\"prev\"><a href=\"{$thispage}?page={$this->prePage}{$this->addParam}\"><i class=\"fa fa-angle-left\"></i></a></li>";
            }
            for($i=$this->startPage;$i<=$this->endPage;$i++){
                if($i==$this->page){
                    $prn[] = "<li class=\"active\"><a>{$i}</a></li>";
                }else{
                    $prn[] = "<li><a href=\"{$thispage}?page={$i}{$this->addParam}\">{$i}</a></li>";
                }
            }
            if($this->endPage<$this->totalPage){
                $prn[] = "<li class=\"next\"><a href=\"{$thispage}?page={$this->nextPage}{$this->addParam}\"><i class=\"fa fa-angle-right\"></i></a></li>";
            }
            if($this->endPage<$this->totalPage){
                $prn[] = "<li class=\"last\"><a href=\"{$thispage}?page={$this->totalPage}{$this->addParam}\"><i class=\"fa fa-angle-double-right\"></i></a></li>";
            }
            $prn[] = "</ul>";
            return join(" ",$prn);
        }else{
            return false;
        }
    }

}
