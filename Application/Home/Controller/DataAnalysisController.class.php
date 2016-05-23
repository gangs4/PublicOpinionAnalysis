<?php
namespace Home\Controller;
use Think\Controller;
class DataAnalysisController extends BaseController {
    public function index(){
        $this->assign('data','粘贴微博文本进来~');

        $model_student = D("StudentInfo");
        $student_info = $model_student->ShowGrade('all',0,8);
        // dump($arr);
        $this->assign('students',$student_info);
        $this->display();

    }

    public function start(){
        $this->display('start');
    }

    public function analysis()
    {
        $data = $this->analysis_emotion();

        // LDA模块
        $fileDir = "./temp/" ;
        $t = time();
        $fileName = $t."_text";

        $myfile = fopen($fileDir.$fileName.".in", "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
        
        // dump($fileName);
        // $lda = $emotion->LDA($fileName);
        $lda = array(array("0.2214170692431562","0.11996779388083736" , "0.061996779388083734" , "0.09098228663446055" , "0.06924315619967794" , "0.047504025764895326" , "0.07648953301127213" , "0.25764895330112725" , "0.05475040257648953" ),
            array(array('中国',3619816463523),array('国家',2201543183082),array("重要",2181839519095)));

        // dump($lda);
        $Recommand = D('StudentInfo');
        $friends = $Recommand->OneToAll(0,$lda[0]);
        // dump($friends);
        $this->assign('emotion',json_encode($data));
        $this->assign('word',json_encode($lda[1]));
        $this->assign('model',json_encode($lda[0]));
        $this->display();
        //var_dump($data);
    }
    //以下都是调用的函数
    public function DelSomeWord($str)
    {
        $delword = array('微博','weibo.com','赞','评论','收藏','转发','展开全文','来自',"\n");
        $str = str_replace($delword, '', $str);
        return $str;
    }
    //传进来全部文本，进行正则提取时间，再处理
    public function analysis_emotion($text)
    {
        if (!isset($text)) {
            $text = $_POST['text'];
        }

        $time_arr = array();
        preg_match_all('/(\d{1,2}分钟前)|(\d{1,2}月\d{1,2}日 \d\d:\d\d)|(今天 \d{2}:\d{2})|(\d{4}-\d{2}-\d{2} \d{2}:\d{2})/', $text , $time_arr);
        $data_arr = preg_split('/(\d{1,2}分钟前)|(\d{1,2}月\d{1,2}日 \d\d:\d\d)|(今天 \d{2}:\d{2})|(\d{4}-\d{2}-\d{2} \d{2}:\d{2})/', $text);

        $all_data = $this->combine($time_arr[0],$data_arr);

        //数据都在这了，然后是要调用那个方法。。似乎要改一下。。
//      dump($all_data);

        // 实例化控制器
        $emotion = A('Emotion');        

        foreach ($all_data as $key => $value)
        {

            $content=$value['data'];
//          dump($content);
            $eval = $emotion->analysis($content);
            $all_data[$key]['emotion'] = $eval['emotion'];
            $all_data[$key]['prob'] = $eval['prob'];
//          dump ($all_data[$key]);
        }
        // dump($all_data);
        foreach ($all_data as $key => $value) {
            $time[$key] = $value['time'];
        }
        array_multisort($time,$all_data);
        $data = $this->parzen($all_data);

        //data里为展示的数据
        // dump($data);
        return $data;
    }

    public function parzen($data)
    {

        // dump($data);
        // data按照时间排序
        $arr = array();
        $n = count($data);
        $first_time = strtotime($data[0]['time']);
        $last_time = strtotime($data[$n-1]['time']);

        // dump($first_time);

        // dump($last_time);

        //sigma参数设置。。。

        
        $sum = 0;

        foreach($data as $value)
        {
            $str_time = strtotime($value['time']);
            $arr[$value['emotion']][] = $str_time;
            $sum += $str_time;
        }
        $mean = $sum/$n;
        // echo "mean = ".$mean;
        // var_dump($arr);
        $ss = 0;
        foreach ($arr as $key => $value) {
            foreach ($value as $k => $v) {
                // var_dump("$v-$mean");
                $ss += pow($v - $mean,2);
            }
        }
        // $sigma = $ss/($n-1);
        // 效果更好一点
        $sigma = ($last_time-$first_time)/2;
        // echo $sigma;

        $step = ($last_time-$first_time)/9;
        $time = range($first_time, $last_time,$step);

        $final_data = array();
        $final_data['begin'] = $first_time;
        $final_data['end'] = $last_time;

        for ($i=0; $i < 10; $i++) { 

            foreach ($arr as $key => $value) {
                $sum = 0;
                foreach ($value as $t) {
                    // dump($t);
                    $sum += exp(- (pow($time[$i] - $t, 2.0) / (2.0*pow($sigma,2) ) ));
                }
                // echo $sum;
                $final_data[$key][$i] = 1/(sqrt(2*3.1415)*$sigma*$n)*$sum;
            }
        }
        // dump($final_data);

        return $final_data;

    }

    private function combine($time_arr,$data_arr)
    {
        $arr = array();
        foreach ($time_arr as $key => $time) {
            if(preg_match_all("/\d{1,2}分钟前/", $time,$match))
            {
                $offset = $time[0];
                if(is_numeric($time[1]))
                {
                    $offset = $offset*10+$time[1];
                }
                $format_time = date("Y-m-d G:i",strtotime("-$offset minute"));
            }
            elseif (preg_match_all("/\d{1,2}月\d{1,2}日 \d\d:\d\d/", $time,$match))
            {
                $offset = 0;
                $month = $time[0];
                if(is_numeric($time[1]))
                {
                    $month = $month*10+$time[1];
                    $offset += 1;
                }
                $day = $time[4+$offset];
                if (is_numeric($time[5+$offset])) {
                    $day = $day*10+$time[5+$offset];
                    $offset+=1;
                }
                $year = date("Y");
                $md = date("m-d",strtotime($month."/".$day));
                $format_time = $year."-".$md.substr($time, 8+$offset);
            }
            elseif (preg_match_all("/今天 \d{2}:\d{2}/", $time,$match))
            {
                $today = date("Y-m-d");
                $format_time = $today.substr($time, 6);
            }
            else
            {
                $format_time = $time;
            }

            $arr[$key] = array('time'=>$format_time,'data'=>$this->DelSomeWord($data_arr[$key+1]));
            // $arr[$key] = array('time'=>$format_time,'data'=>($data_arr[$key+1]));
        }
        // dump($arr);
        return $arr;
    }

}

