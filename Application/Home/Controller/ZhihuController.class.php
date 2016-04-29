<?php
namespace Home\Controller;
use Think\Controller;
class ZhihuController extends Controller {

    //各显示6个热门
    public function index(){

        $m_question = D('Question');
        $arr_topic = $m_question->getHotquestions(6);

        $m_user = D('Answer');
        $arr_user = $m_user->getHotusers(6);

        $this->assign('topics',$arr_topic);
        $this->assign('users',$arr_user);

		$this->display();
    }
    //ajaxreturn
    public function search()
    {
        if(isset($_GET['type']))
        {
            if($_GET['type']=='1')
            {
                $m = D('Question');
                $data = $m->search($_GET['str']);
                $this->ajaxReturn($data);
            }
            else
            {
                $m = D('Answer');
                $data = $m->searchuser($_GET['str']);
                $this->ajaxReturn($data);
            }
        }
    }
    
    public function show_topic()
    {
        $m = D('Question');
        $data = $m->searchByid($_GET['id']);
        $this->assign('data',$data);
        $this->display('question');
    }

    public function relation()
    {
        $m = D('Answer');
        $name = $_GET['name'];
        $fre = $_GET['fre'];
        $arr = $m->search($name);
        array_unshift($arr, array("frequent"=>"$fre",'name'=>"$name"));
        $this->assign('relation', json_encode($arr));
        $this->display();
    }

}
