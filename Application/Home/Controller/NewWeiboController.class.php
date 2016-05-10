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

    public function ShowList()
    {
        $model_student = D("StudentInfo");
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            $all_student = $model_student->SearchByName($name);
        }
        else
        {
            $now_page = $_GET['page'];
            $grade = $_GET['grade'];
            $now_page = $_GET['page'];
            $grade = $_GET['grade'];
            $all_student = $model_student->ShowGrade($grade,$now_page);
            //这里似乎可以~~~
            $this->assign('font',$now_page-1);
            $this->assign('next',$now_page+1);
        }

        $grade_info = $model_student->ShowGrade();
        $this->assign('students',$all_student);
        $this->assign('grades',$grade_info);
        $this->display();
    }

    public function detail()
    {
        $id = $_GET['id'];
        $ldamodel = D("Ldamodel");
        $lda = $ldamodel->select();
        $m = D("StudentInfo");
        $info = $m->where(array('id'=>$id))->select();
        $this->assign('info',$info[0]);
        // dump($lda);
        $this->display();
        dump($info);
    }

}
