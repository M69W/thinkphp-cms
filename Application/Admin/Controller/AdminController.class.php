<?php
/**
 * 用户管理相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class AdminController extends CommonController {


    public function index() {
        $admins = D('Admin')->getAdmins();
        $this->assign('admins', $admins);
        $this->display();
    }

    public function add() {

        // 保存数据
        if(IS_POST) {

            if(!isset($_POST['username']) || !$_POST['username']) {
                return show(0, '用户名不能为空');
            }
            if(!isset($_POST['password']) || !$_POST['password']) {
                return show(0, '密码不能为空');
            }
            $_POST['password'] = getMd5Password($_POST['password']);
            // 判定用户名是否存在
            $admin = D("Admin")->getAdminByUsername($_POST['username']);
            if($admin && $admin['status']!=-1) {
                return show(0,'该用户存在');
            }

            // 新增
            $id = D("Admin")->insert($_POST);
            if(!$id) {
                return show(0, '新增失败');
            }
            return show(1, '新增成功');
        }
        $this->display();
    }

    public function setStatus(){
      try {
        if ($_POST) {
          $id = $_POST['id'];
          $status  = $_POST['status'];
          if (!$id) {
            return show(0,'ID不存在');
          }
          $res = D("Admin")->updataStatusById($id,$status);
          if ($res) {
            return show(1,'操作成功');
          }else{
            return show(0,'操作失败');
          }
        }
        return show(0,"没有提交内容");
      } catch (Exception $e) {
        return show(0,$e->getMessage());
      }
    }

    public function personal() {
        $res = $this->getLoginUser();
        $user = D("Admin")->getAdminByAdminId($res['admin_id']);
        $this->assign('vo',$user);
        $this->display();
    }

    public function editPassword() {
        $res = $this->getLoginUser();
        $user = D("Admin")->getAdminByAdminId($res['admin_id']);
        $this->assign('vo',$user);
        $this->display("edit-password");
    }

    public function newPassword() {
      $admin_id = $_POST['admin_id'];
      $oldpassword = $_POST['oldpassword'];
      $newpassword = $_POST['newpassword'];
      if (!$oldpassword || !$newpassword) {
        return show(0,'请输入正确信息');
      }
      $ret = D('Admin')->getAdminByAdminId($admin_id);
      if ($ret['password'] != getMd5Password($oldpassword)) {
        return show(0,"旧密码错误");
      }
      $data['password'] = getMd5Password($newpassword);
      try {
          $id = D("Admin")->updateByAdminId($admin_id, $data);
          if($id === false) {
              return show(0, '修改失败');
          }
          session('adminUser',null);
          return show(1, '修改成功');
      }catch(Exception $e) {
          return show(0, $e->getMessage());
      }
    }

    public function save() {
        $user = $this->getLoginUser();
        if(!$user) {
            return show(0,'用户不存在');
        }

        $data['realname'] = $_POST['realname'];
        $data['email'] = $_POST['email'];

        try {
            $id = D("Admin")->updateByAdminId($user['admin_id'], $data);
            if($id === false) {
                return show(0, '配置失败');
            }
            return show(1, '配置成功');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

}
