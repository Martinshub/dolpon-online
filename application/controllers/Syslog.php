<?php

require_once('../application/library/SystemError/SystemError.php');

Class SysLogController extends Yaf_Controller_Abstract
{
    public function init(){
        //使用layout页面布局
        /*$this->_layout = new LayoutPlugin('admin/admin.html');
        $this->dispatcher = Yaf_Registry::get("dispatcher");
        $this->dispatcher->registerPlugin($this->_layout);*/

        $this->_user = new AdminModel();
    }

    public function indexAction()
    {
        $this->getView()->assign("action",strtolower(
            $this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName()));

        //判断是否登陆
        if(Yaf_Session::getInstance()->get("admin_username")){
            $this->getView()->assign("isLogin",true);
        } else{
            $this->getView()->assign("isLogin",false);
        }

        $this->getView()->assign("name",'yantze');
        $this->getView()->assign("content",'game,');

        $systemError = new SystemError();
        $sysErrorList = $systemError->getFileContentDesc();
        $this->getView()->assign("sysErrorList", $sysErrorList );
    }


}