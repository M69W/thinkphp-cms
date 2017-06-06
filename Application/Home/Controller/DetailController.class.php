<?php
namespace Home\Controller;
use Think\Controller;

class DetailController extends CommonController {
    public function index(){
      $id = intval($_GET['id']);
      if (!$id || $id<0) {
        return $this->error('ID不合法');
      }
      $news = D("News")->find($id);

      if (!news || $news['status'] != 1) {
        return $this->error("ID不存在或者资讯被关闭");
      }

      $count = intval($news['count']) + 1;
      D('News')->updateCount($id,$count);

      $content = D("NewsContent")->find($id);
      $news['content'] = htmlspecialchars_decode($content['content']);

      //获取文章排行
      $rankNews = $this->getRank();
      //获取右侧广告位
      $advNews = D("PositionContent")->select(array('status' => 1,'position_id' => 5),2);

      $this->assign('result',array(
        'advNews' => $advNews,
        'rankNews' => $rankNews,
        'catId' => $news['catId'],
        'news' => $news,
      ));
      $this->display("Detail/index");
    }
    public function view(){
      if (!getLoginUsername) {
        $this->error("您没有权限访问该页面");
      }
      $this->index();
    }
}
