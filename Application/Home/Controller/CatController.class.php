<?php
namespace Home\Controller;
use Think\Controller;

class CatController extends CommonController {
    public function index(){
      $id = intval($_GET['id']);
      if (!$id) {
        return $this->error('ID不存在');
      }
      $nav = D("Menu")->find($id);
      if (!$nav || $nav['status'] != 1) {
        return $this->error('栏目ID不存在或状态不正常');
      }
      //获取文章排行
      $rankNews = $this->getRank();
      //获取右侧广告位
      $advNews = D("PositionContent")->select(array('status' => 1,'position_id' => 5),2);

      $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
      $pageSize = 20;
      $conds = array(
        'status' => 1,
        'thumb' => array('neq',''),
        'catid' => $id,
      );
      $news = D("News")->getNews($conds,$page,$pageSize);
      $count = D("News")->getNewsCount($conds);
      $res = new \Think\Page($count,$pageSize);
      $pageRes = $res->show();

      $this->assign('result',array(
        'advNews' => $advNews,
        'rankNews' => $rankNews,
        'catId' => $id,
        'listNews' => $news,
        'pageRes' => $pageRes,
      ));
      $this->display();
    }
}
