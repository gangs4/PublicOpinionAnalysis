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
        // dump($grade_info);
        $this->display();
    }
    public function in_grade()
    {
        $grade = $_GET['grade'];
        $model_student = D("StudentInfo");
        $student_info = $model_student->ShowGrade($grade);
        $grade_info = $model_student->ShowGrade();
        // dump($grade_info);
        $this->display('list_all');
    }

    public function detail()
    {
        $id = $_GET['id'];
        $ldamodel = D("Ldamodel");
        $lda = $ldamodel->select();
        // dump($lda);
        $this->display();
    }

}
