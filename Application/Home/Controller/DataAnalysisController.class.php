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
        // $data = $this->analysis_emotion();
        $data = NULL;
        // var_dump($data);
        if (!isset($text)) {
            $text = $_POST['text'];
        }
        // LDA模块
        $fileDir = "./temp/" ;
        $t = time();
        $fileName = $t."_text.in";

        $myfile = fopen($fileDir.$fileName, "w") or die("Unable to open file in dataAnalysis  LDA!");
        fwrite($myfile, $text);
        fclose($myfile);
        // unlink($fileDir.$fileName);
        
        // dump($fileName);
        $emotion = A("emotion");
        $lda = $emotion->LDA($fileName);
        // $lda = array(array("0.2214170692431562","0.11996779388083736" , "0.061996779388083734" , "0.09098228663446055" , "0.06924315619967794" , "0.047504025764895326" , "0.07648953301127213" , "0.25764895330112725" , "0.05475040257648953" ),array(array('中国',3619816463523),array('国家',2201543183082),array("重要",2181839519095)));

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
    public function test2()
    {
        $str = "
全部
ì热门
更多 c
fg
c
一度燃冰
一度燃冰
4月5日 22:46 来自 魅蓝 note
//@我的前任是极品:转需～！
@买买小天使
｢男生穿好看的衣服，有哪些男装服饰品牌值得推荐？ ｣
长图 长图 长图 长图 长图 长图 长图 长图 长图
5133
 2247
1545
4月5日 20:00 来自 微博 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月5日 22:39 来自 魅蓝 note
有点污啊，谁躺枪了
@安琪蜜月
好魔性的歌曲，12星座到底谁最骚浪贱。有中枪的没？ L秒拍视频

114
 12
73
4月5日 21:53 来自 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月4日 23:17 来自 魅蓝 note
转发微博
@虎扑篮球
#KobeBryant#

239
 75
741
4月4日 12:49 来自 iPhone 6
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月4日 23:13 来自 魅蓝 note
@Boogaloo_Hoo
@土家硒泥坊
喵星人一旦瞪眼后，从此就跟“高冷”这个词无缘了
        
51
 16
194
4月4日 16:10 来自 微博 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月4日 23:06 来自 魅蓝 note
@微博搞笑排行榜
这个片段是看一次笑一次，世上怎么会有这么倒霉的人哈哈哈哈哈哈 L秒拍视频

14975
 6924
25661
4月4日 20:15 来自 微博 weibo.com
û收藏
转发
评论
 1
c
一度燃冰
一度燃冰
4月4日 22:50 来自 魅蓝 note
转发微博
@全球娱乐趣事
卧槽！！这个配音有毒，为什么总能让我想到宋小宝呢L秒拍视频

1554
 223
747
4月4日 22:00 来自 微博 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月4日 22:49 来自 魅蓝 note
转发微博
@糗事笑话控
【神配音】史上最污的愚人节，我歌和wuli仲基被玩坏了... L秒拍视频

38
 7
24
4月4日 22:15 来自 微博 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月4日 22:44 来自 魅蓝 note
@央视新闻
#清明节#【为红军 抗战老兵守陵45年】贵州遵义，93岁抗战老兵刘福昌为无名烈士守陵已45年。当年，他见红军烈士陵园荒草丛生，便留下护陵，每日清扫巡视、栽花种树…45年过去，如今陵园里2000株树木枝繁叶茂。老人说，红军用血肉打倒了敌人，我要好好照管他们的陵墓。致敬！L秒拍视频 (央...展开全文c
        
2592
 478
7063
4月4日 22:19 来自 微博 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月4日 08:59 来自 魅蓝 note
转发微博
@新闻晨报
【#清明节#】今天16时28分，清明，“万物生长此时，皆清洁而明净，故谓之清明。”清明起初只是节气名称，后逐渐与农历三月的寒食节、上巳节交汇融合，成为“时年八节”中的清明节。这一天，既是祭祖扫墓、慎终追远的感伤之日，也是扑蝶插柳、踏青赏春佳节。今天，在追忆中拭去心伤，心怀感恩继续前行。
        
234
 13
182
4月4日 08:30 来自 iPhone 6 Plus
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月2日 17:57 来自 魅蓝 note
回学校马上去一次
@哈尔滨身边事
#哈尔滨身边事# 【哈尔滨·松花江铁路大桥】@光影文化_御宇 ：是爱的枯冢，还是爱的祭奠。 @淡定滴苏苏 ：一起溜达的情侣为什么会随身带小刀
        
117
 121
208
4月2日 15:31 来自 微博 weibo.com
û收藏
转发
评论
赞
c
一度燃冰
一度燃冰
4月2日 17:41 来自 魅蓝 note
@Boogaloo_Hoo @黄郅博 //@最神奇的视频:
@我Hold不住了
你在上铺，下铺的人探上头来时的情景。真的好像！
";

    $arr = $this->analysis_emotion($str);
    dump($arr);
    }

    //传进来全部文本，进行正则提取时间，再处理
    public function analysis_emotion($text)
    {
        if (!isset($text)) {
            $text = $_POST['text'];
        }
        // var_dump($text);

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

