<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {

    public function index(){
      $news = D('News')->maxcount();
      $newscount = D('News')->getNewsCount(array('status'=>1));
      $positioncount = D('Position')->getCount(array('status'=>1));
      $admincount = D('Admin')->getLastLoginUsers();
      $this->assign('news',$news);
      $this->assign('newscount',$newscount);
      $this->assign('positioncount',$positioncount);
      $this->assign('admincount',$admincount);
    	$this->display();
    }

    public function main() {
    	$this->display();
    }
}
