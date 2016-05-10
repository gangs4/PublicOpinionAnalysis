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
    
    public function od()
    {
        $m = D("StudentInfo");
        $dir =  "./ans/";
        // $dir = "./the";
        $fso = opendir($dir);
        // echo $base_dir."<hr/>"   ;
        while($flist=readdir($fso)){
            // echo iconv("GBK", "UTF-8", $flist);
            echo $flist."<br/>" ;
            // echo type($file);
            if(preg_match_all("/txt.seg.lda/", $flist,$match)){
                $text = $this->loading($flist);
                // dump($text);
                $str = split(".txt.seg.lda",iconv("GBK", "UTF-8", $flist))[0];

                // dump($str);
                $strd = preg_replace("/\d*$/", "", $str);
                // dump($strd);
                $m->insert_info($strd,$text);
                $m->insert_info2($strd,$text);

            }
            if(preg_match_all("/.txt.seg.theme/", $flist,$match)){
                $text = $this->loadlda($flist);
                $str = split(".txt.seg.theme",iconv("GBK", "UTF-8", $flist))[0];
                $strd = preg_replace("/\d*$/", "", $str);
                dump($strd);

                $m->insert_info($strd,$text);
                $m->insert_info2($strd,$text);
                // break;
            }
        }
        closedir($fso);
    }
    private function loadlda($filename)
    {
        $Dir = "./the/";
        $file = fopen($Dir.$filename,'r') or die("Unable to open file");
        $line = fgets($file);
        $arr = split(" ",$line);
        $text = "";
        foreach ($arr as $key => $value) {
            if($key == 9) break;
            $text.= split(':',$value)[1]."|";
        }
        // dump($text);
        return $text;
    }
    private function loading($filename)
    {
        $Dir = "./ans/";
        // dump($Dir."____今夜阳光灿烂2227.txt.seg.lda");
        // dump(file_exists($Dir."8Adam8260.txt.seg.lda"));

        $file = fopen($Dir.$filename,'r') or die("Unable to open file");
        $text = "";
        while (! feof($file))
        {
            $oneline = fgets($file);
            // dump($oneline);
            $arr = split(' ',trim($oneline));
            // dump($arr);
            if($arr[0]=="") break;
            $text.=$arr[0]."~".$arr[1]."|";
        }
        // dump($text);
        return($text);
        fclose($file);

    }
}
