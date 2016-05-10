<?php
namespace Home\Controller;
use Think\Controller;
class ZhihuController extends BaseController {

    //各显示6个热门
    public function index(){

        $m_question = D('Question');
        $arr_topic = $m_question->getHotquestions(6);

        $m_user = D('Answer');
        //参数为首页展示人数
        $arr_user = $m_user->getHotusers(12);

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
    
    //添加关键词的脚本
    public function shell()
    {
        $m = D('Answer');
        $dir = "./temp/result.txt";

        $text = file_get_contents($dir);
        $arr = split("\n\n",$text);

        foreach ($arr as $key => $value) {
            $data = split("\n",$value);
            $info = "";
            foreach ($data as $k => $v) {
                if($k!=0)
                {
                    $temp = split(" ",$v);
                    $info.= $temp[0]."~".$temp[1]."|";
                }
                
            }
            // dump($info);
            $condition['id'] = $key+1;
            $data['keywords'] = $info;
            $m->where($condition)->save($data);
        }

    }
    public function add_info()
    {
        $m = D('Answer');
        $m2 = M('Answer2');
        $arr = $m2->select();
        foreach ($arr as $key => $value) {
            $condition['id'] = $value['id'];
            $data['answer_time'] =  $value['answer_time'];
            $data["avatar_url"] = $value['avatar_url'];
            $m->where($condition)->save($data);
            // break;
        }
        // dump();
    }
}
