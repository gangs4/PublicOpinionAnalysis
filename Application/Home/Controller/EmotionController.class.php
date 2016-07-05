<?php
namespace Home\Controller;
use Think\Controller;
header("content-Type: text/html; charset=utf-8");

class EmotionController extends Controller {
    public function index(){
        $this->display();
    }

    public function div()
    {
        echo "<br /> ====================================== <br / >";
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
";

    $arr = $this->analysis($str);
    dump($arr);

    $arr = $this->analysis($str);
    dump($arr);

    $arr = $this->analysis($str);
    dump($arr);
    
    $arr = $this->analysis($str);
    dump($arr);
    }

    //这是一个可重用的模块,只要更改输入就好,里面的content是要分析的文本,返回data数组
    public function analysis($content=null)
    {

        if(isset($_GET["content"]))
        {
            $content = $_GET["content"];
        }
        //情感分析
        $EmotionDir = "./Public/emotion/";
        $fileDir = "./temp/" ;
        $t = time();
        $fileName = $t."_".$i;
        //header里面制定了编码,但是在Linux下的运行保持怀疑
        $myfile = fopen($fileDir.$fileName.".origin", "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
        $myfile2 = fopen($fileDir.$fileName.".seg", "w") or die("Unable to open file!");
        $segContent = $this->seg($content);
        fwrite($myfile2,$segContent);
        fclose($myfile2);

        // var_dump($content);
        // $this->div();
        // var_dump($segContent);
        // $this->div();

        // 调用情感分析模块,速度大约在120s内跑50多条,有待优化
        $cmd = "/usr/lib/jvm/jdk1.8.0/bin/java -jar ".$EmotionDir."emotion.jar ".$fileDir.$fileName;
        // var_dump($cmd);
        // $this->div();
        unset($ret);
        $last_line = exec($cmd,$ret,$ans);
        // var_dump($ret);
        // $this->div();
        unset($data);
        $data["prob"] = $ret[0];
        $data["emotion"] = $ret[1];
        if (count($ret)<2)
        {
            //  情感分析的问题,稍后调试
            $data["prob"] = 1;
            $data["emotion"] = "NONE";
        }
        // var_dump($data);
        // $this->div();

        //关闭并删除缓存
        $toBeDelated = $fileDir.$fileName;
        $result = unlink($toBeDelated.".origin");
        $result = unlink($toBeDelated.".seg");
        // $result = unlink($toBeDelated.".out");
        // if ($result == true)
        // {
        //          echo $i." chenggongshanchu </br>";
        // }
        // else
        // {
        //  echo $i." shanchushibai </br>";
        // }
        return $data;
    }

    //java跑完存在本地然后load
    public function loadFromFile()
    {
        $fileDir = "D:/zhihu/";
        $m = D("answer");
        for ($i=0; $i < 3338; $i++) {
            # code...
            $f = fopen("".$fileDir.$i.".out", "r");
            if ($f)
            {
                unset($condition);
                unset($data);
                $condition["id"] = $i+1;
                $data["prob"] = fgets($f, 4096);
                $data["emotion"] = fgets($f, 4096);
                echo $condition["id"]."</br>";
                var_dump($data);
                echo "=====================</br>";
                $m->where($condition)->save($data);
                fclose($f);
            }
        }
    }

    //seg function
    public function seg($content)
    {
        $fileName = time();
        $fileDir = "./temp/";
        $myfile = fopen($fileDir.$fileName, "w") or die("Unable to open file in seg");
        fwrite($myfile, $content);
        fclose($myfile);
        $seg = "python ./Public/seg.py " . $fileDir.$fileName;
        $last_line = exec($seg,$ret,$ans);
        // unlink($fileDir.$fileName);
        // $ans = join($last_line);
        // var_dump($ans);
        return $last_line;
    }

    public function LDA($name)
    {
        //跑起来的条件python3，java，linux
        if (isset($_GET["name"]))
        {
            $name = $_GET["name"];    
        }        
        $fileName = $name;
        $fileDir = "./temp/";
        $LDADir = "./Public/LDA/";

        //open and read file
        $content = "";
        // var_dump($fileDir . $fileName);
        $fin = fopen($fileDir . $fileName,"r") or die("unable to open file in emotion LDA");
        while (!feof($fin))
        {
            $content .= fgets($fin);
        }
        fclose($fin);
        // var_dump($content);

        /*ltp seg*/
        // $uri     = "http://api.ltp-cloud.com/analysis/?";
        // $apikey  = "T5B8m263WV5FeewQwxWd5wIn1uhTsulcGPjgkf7D";
        // $text    = $content;
        // $pattern = "ws";
        // $format  = "plain";
        // $url = ($uri
        //         . "api_key=" . $apikey          . "&"
        //         . "text="    . urlencode($text) . "&"
        //         . "pattern=" . $pattern         . "&"
        //         . "format="  . $format);
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // // grab URL and pass it to the browser
        // $response = curl_exec($ch);
        // // echo "<br />";
        // curl_close($ch);
        // $response = str_replace("\n", " ", $response);

        /*local ltp seg*/
        $response = $this->seg($content);
        var_dump($response);

        $myfile2 = fopen($fileDir.$fileName.".out.txt", "w") or die("Unable to open file!");
        fwrite($myfile2, $response);
        fclose($myfile2);
        chmod("./temp/".$fileName, 0777);

        $env = "$LANG=en_US.UTF-8";
        $echo = "echo $LANG";
        $cut = "python3 ./Public/LDA/cut.py ".$fileDir.$fileName;
        $dataFile = $fileName.".out.txt";
        $gzip = "gzip -kf ".$fileDir.$dataFile;
        $java =" /usr/lib/jvm/jdk1.8.0/bin/java -jar ./Public/LDA/lda.jar  -inf -niters 50 -twords 100  -dir ".$fileDir." -dfile ".$fileName.".out.txt.gz";
        // echo $java."<br />";
        $gunzip = "gunzip -fk ".$fileDir.$dataFile."*.gz";
        $op = array($gzip,$java,$gunzip );
        // print_r($op);
        for ($i=0; $i <count($op) ; $i++) {
            $last_line = exec($op[$i],$ret,$ans) ;
            // print_r($ret);
            unset($ret);
            // echo "<br />=======================================<br />";
        }
        $last_line = exec($cmd,$ret,$ans);
        // print_r($ret);
        // $cmd = "rm ".$fileDir.$fileName."*";
        // $last_line = exec($cmd,$ret,$ans);
        // $result = unlink("".$fileDir.$fileName);
        // echo $result;

        //read file and return value
        $themeFile = $fileName.".out.txt..theta";
        $fin = fopen($fileDir . $themeFile, "r") or die("Unable to open file in read LDA theta");
        $oneline = fgets($fin);
        fclose($fin);
        $tempArr = split(" ", $oneline);
        array_pop($tempArr);
        $data = array();
        $maxNum = 0;
        $max = -1;
        foreach ($tempArr as $key => $value) {
            $tempOne = split(":", $value);
            $data[$key] = $tempOne[1];
            if (floatval($tempOne[1])>$max)
            {
                $max = floatval($tempOne[1]);
                $maxNum = $key;
            }
        }
        // var_dump($data);
        $twordFile = $fileName . ".out.txt..twords";
        $fin = fopen($fileDir . $twordFile, "r") or die("unable to open file in LDA twords");
        $words = array();
        $flag = "Topic ".$maxNum.": ";
        // var_dump($flag);
        $nowType = "";
        $numOfWords = 0;
        // echo "<br />";
        while (! feof($fin))
        {
            $oneline = fgets($fin);
            // var_dump($oneline);
            // echo "     ";
            // var_dump($flag);
            // echo "      ";
            // var_dump(strcmp($oneline, $flag));
            // var_dump(strncmp($oneline, $flag, 7));
            // echo "<br />";
            if (strncmp($oneline, $flag, 7) == 0)
            {
                for ($i=0; $i < 60; $i++) {
                    # code...｀
                    $oneline = fgets($fin);
                    array_push($words, $oneline);
                }
                break;
            }
        }
        // echo "<br />";
        foreach ($words as $key => $value) {
            // var_dump($words[$key]);
            // echo "<br />";
            $words[$key] = split("\t", $value) ;
            array_shift($words[$key]);
            $words[$key][1] = floatval($words[$key][1]);
            // var_dump($words[$key]);
            // echo "<br />";
        }
        // var_dump($words);
        $result = array($data,$words);
        // var_dump($result);

        //delete the cache
        $cmd = "rm 1* -rf";
        exec($cmd,$ret);
        // var_dump($ret);
        unlink($fileDir.$fileName);

        return $result;
    }
    public function test()
    {
        // $content = "睡前旧了一把，《SMAP×SMAP.111128 超豪华动漫名曲歌谣祭》  http://t.cn/zWpWSF5  TOUCH真是听它千遍也不厌倦[可怜] 明天再继续第二弹...";
        // $name = "./temp/1.in";
        // $cmd = "python -h";
        // $seg = "python ./Public/seg.py " . $content;
        // var_dump($seg);
        // echo "<br />=======================================<br />";
        // $last_line = exec($seg,$ret,$ans);
        // var_dump($ret);
        // echo "<br />=======================================<br />";
        // var_dump($ans);
        // chmod("./temp/*",0777);
        $content = $_GET["content"];
        var_dump($this->seg($content));
    }


}
