<?php
/**
 * Created by PhpStorm.
 * User: liu
 * Date: 16/2/23
 * Time: 上午12:01
 */

namespace Home\Controller;
use Think\Controller;

/**
 * Class BaseController
 * @package Home\Controller
 * 控制器基类
 */
class BaseController extends Controller {

    public function _empty($name){

        $this->display('public:header');
        echo '该方法为空';
    }

    public function fun1()
    {
    	return;
    }

}
