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
        //lda模块名称
        $ldamodel = D("Ldamodel");
        $lda = $ldamodel->select();
        $this->assign('lda',json_encode($lda));

        //个人信息
        $model_student = D("StudentInfo");
        $info = $model_student->where(array('id'=>$id))->select();
        $this->assign('info',$info[0]);

        //词
        $words = split('\|',$info[0]['keywords']);
        $lda_word = array();
        foreach ($words as $key => $value) {
            $data = split('~',$value);
            $lda_word[] = $data;
        }

        // lda概率
        $lda_pros = $model_student->pickitup($info[0]['lda_pro']);
        $this->assign('word',json_encode($lda_word));
        $this->assign('model',json_encode($lda_pros));

        // 余弦相似度
        $cosdis = $model_student->OneToAll($id);
        $this->assign('friends',json_encode($cosdis));
        
        // 情感变化数据
        $emotion_data = json_decode($info[0]['emotion_data']);
        // dump($emotion_data);
        $this->assign('emotion',$emotion_data);

        $this->display();
        // dump($cosdis);
        dump($cosdis);
    }

    //以下为添加数据的脚本 php5.6
    public function od()
    {
        $m = D("StudentInfo");
        // $dir =  "./ans/";
        $dir = "./wb/";
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
                $str = split(".txt.seg.lda",iconv("GBK", "UTF-8", $flist));
                $str = $str[0];

                // dump($str);
                $strd = preg_replace("/\d*$/", "", $str);
                // dump($strd);
                $m->insert_info($strd,$text);
                // $m->insert_info2($strd,$text);

            }
            if(preg_match_all("/.txt.seg.theme/", $flist,$match)){
                $text = $this->loadlda($flist);
                $str = split(".txt.seg.theme",iconv("GBK", "UTF-8", $flist));
                $str = $str[0];
                $strd = preg_replace("/\d*$/", "", $str);
                dump($strd);

                $m->insert_info($strd,$text);
                // $m->insert_info2($strd,$text);
                // break;
            }
            if(preg_match_all("/.txt/", $flist,$match)){
                // $text = $this->loadlda($flist);
                $data = $this->loadEmotionData($flist);
                // dump(json_encode($data));

                $str = split(".txt",iconv("GBK", "UTF-8", $flist));
                $str = $str[0];
                $strd = preg_replace("/\d*$/", "", $str);
                // dump($strd);

                $m->insert_info($strd,json_encode($data));
                // $m->insert_info2($strd,$text);
                break;
            }
        }
        closedir($fso);
    }
    public function loadEmotionData($filename)
    {
        $Dir = "./wb/";
        $file = fopen($Dir.$filename,'r') or die("Unable to open file");
        // $text = fgets($file);
        $text = file_get_contents($Dir.$filename);
        $C = A('DataAnalysis');
        $data = $C->analysis_emotion($text);
        // dump($text);
        return $data;
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
