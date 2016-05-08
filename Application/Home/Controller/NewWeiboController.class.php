<?php
namespace Home\Controller;
use Think\Model\RelationModel;

class NewWeiboController extends BaseController {
    /**
     * 微博首页方法
     *
     * 页面显示内容
     * [...]（根据文档、需求填写）
     *
     * @TemplateCall
     * default
     *
     * @TemplateReturns
     * user: 一个...的用户信息
     * weibo: 带有用户信息的全部微博数据
     */
    public function index(){

        $weiboModel = D("TotalWeibo");
        $arr_total = $weiboModel->total();
 
        $this->assign('hours',json_encode($arr_total[0]));
        $this->assign('weeks',json_encode($arr_total[1]));

        // $wordModel = D("HotWord");

        $this->display();
    }

    public function list_all()
    {
        // echo "list function";
        $model_student = D("StudentInfo");

        $all_student = $model_student->select();
        $grade_info = $model_student->ShowGrade();
        // dump($all_student);
        $this->display();
    }
    public function in_grade()
    {
        $grade = $_GET['grade'];
        $model_student = D("StudentInfo");
        $grade_info = $model_student->ShowGrade($grade);
        // dump($grade_info);
        $this->display('list_all');
    }

    public function detail()
    {
        $id = $_GET['id'];
        $this->display();
    }

//以下是似乎没用了。。。


    public function test()
    {
        $wordModel = D("WeiboInfo");
        $arr = $wordModel->search($_GET['str']);
        dump($arr);
    }



    public function search(){
        if(isset($_GET['type']))
        {
            $type = $_GET['type'];
            $arr_user = null; $arr_cont = null;

            if ($type == 'both' || $type == 'id') {
                $userModel = D('UserInfo');
                $arr_user = $userModel->search($_GET['str']);
            }
            if ($type == 'both' || $type == 'cont') {
                $contentModel = D('TotalWeibo');
                $arr_cont = $contentModel->search($_GET['str']);
            }
            $this->assign('data', $arr_user);
            $this->assign('weibos', $arr_cont);
            // var_dump($arr_user);
            // var_dump($arr_cont);
            $this->display();
        }
        else
        {
            $this->display();
        }

    }

    public function personal(){
        $userModel = D('UserInfo');
        $arr = $userModel->relation(true)->find($_GET['id']);

        $analysisModel = D('TotalWeibo');
        $analysis = $analysisModel->analysisPerson($_GET['id']);
    //var_dump($analysis);
        $this->assign('hours',json_encode($analysis[0]));
        $this->assign('weeks',json_encode($analysis[1]));
        $this->assign('username',json_encode($arr['username']));
        $this->assign('data',array(0=>$arr));
        /*var_dump($arr);*/
        $this->assign('weibos',$arr['weibos']);

        $this->display();
    }

    // public function detail(){
    //     $contentModel = D('TotalWeibo');
    //     if (isset($_GET['id']))
    //     {
    //         $arr = $contentModel->searchDetail($_GET['id']);
    //     }
    //     else
    //     {
    //         $arr = $contentModel->relation(true)->find();
    //     }
    //     $arr = array("1"=>$arr);
    //     $this->assign('weibos',$arr);
    //     $this->display();
    // }

}
