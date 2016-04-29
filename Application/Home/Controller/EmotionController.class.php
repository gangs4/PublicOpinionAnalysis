<?php
namespace Home\Controller;
use Think\Controller;
header("content-Type: text/html; charset=utf-8");

class EmotionController extends Controller {
    public function index(){
  		$this->display();
    }

    //这是一个可重用的模块,只要更改输入就好,里面的content是要分析的文本,返回data数组
    public function analysis($content=null)
    {
//		$m = D("answer");
//    	$query = $m->select();
//    	//n=3 是为了跑得快
//    	$n = 1;
//    	for ($i=0; $i < $n; $i++)
//    	{
    		//制定内容
		if(isset($_GET["content"]))
		{
			$content = $_GET["content"];
		}
//		dump($content);
		//情感分析
		$EmotionDir = "./Public/emotion/";
		$fileDir = "./temp/" ;
		$t = time();
		$fileName = $t."_".$i;
		//header里面制定了编码,但是在Linux下的运行保持怀疑
		$myfile = fopen($fileDir.$fileName.".in", "w") or die("Unable to open file!");
		fwrite($myfile, $content);

		// 调用情感分析模块,速度大约在120s内跑50多条,有待优化
		$cmd = "java -jar ".$EmotionDir."emotionFromFile.jar ".$fileDir.$fileName;
		unset($ret);
		$last_line = exec($cmd,$ret,$ans);
		unset($data);
		$data["prob"] = $ret[1];
		$data["emotion"] = $ret[2];
		if (count($ret)<2)
		{
			//	情感分析的问题,稍后调试
			$data["prob"] = 1;
			$data["emotion"] = "NONE";
		}

		//打印并更新数据库
//	    	$condition["id"] = $query[$i]["id"];
//	    	print_r($condition);
//		print_r($data);
//		var_dump($ret);
//	    	echo "</br>";
//	    	$m->where($condition)->save($data);

		fclose($myfile);

		//关闭并删除缓存
		$toBeDelated = $fileDir.$fileName;
		$result = unlink($toBeDelated.".in");
		$result = unlink($toBeDelated.".out");
		if ($result == true)
		{
//			echo $i." chenggongshanchu </br>";
		}
		else
		{
			echo $i." shanchushibai </br>";
		}
		return $data;

//		echo "==================================================</br>";
//		echo "<a href=\"/PublicOpinionAnalysis/index.php/Home/Emotion/index\"><button>return</button></a>";

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
    public function LDA()
    {
        //跑起来的条件python3，java，lunix
        $content = "/@Copper_PKU://@eyounx_俞扬:帮贴链接：OAAAI 主席 2016年会致辞：通往稳健人工智能... 确实，这还是在封闭环境下，都会突然出现远低于预期的性能，环境再开放一点，性能还会急剧下降//@刘知远THU: 的确如此。今年AAAI特邀报告就有一个是关于人工智能鲁棒性的" ;
        $fileDir = "./temp/";
        $LDADir = "./Public/LDA/";
        $t = time();
        $fileName = "".$t;
        $myfile = fopen($fileDir.$fileName."", "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
        chmod("./temp/".$fileName, 0777);
        $cut = "python3 ./Public/LDA/cut.py ".$fileDir.$fileName;
        $dataFile = $fileName."out.txt";
        $gzip = "gzip -kf ".$fileDir.$dataFile;
        $java =" java -Xms512m -Xmx512m -jar ./Public/LDA/lda.jar  -inf -niters 50 -twords 20  -dir ".$fileDir."-dfile ".$fileName.".out.txt.gz";
        $gunzip = "gunzip -fk ".$fileDir.$dataFile."*.gz";
        $op = array($cut,$gzip,$java,$gunzip );
        // print_r($op);
        for ($i=0; $i <4 ; $i++) {
            $last_line = exec($op[$i],$ret,$ans) ;
            print_r($ret);
            unset($ret);
            echo "<br />=======================================<br />";
            # code...
        }
        $last_line = exec($cmd,$ret,$ans);
        print_r($ret);
        // $cmd = "rm ".$fileDir.$fileName."*";
        // $last_line = exec($cmd,$ret,$ans);
        // $result = unlink("".$fileDir.$fileName);
        // echo $result;
    }
    public function test()
    {
        $cut = "sudo python3 ./Public/LDA/cut.py ./temp/1460468361";
        $pwd = "pwd";
        $last_line = exec($cut,$ret,$ans);
        var_dump($ret);
        echo "<br />=======================================<br />";
        var_dump($ans);
        fwrite($myfile,"1233333212321");
        fclose($myfile);
        chmod("./temp/*",0777);
        print_r($result);
        if ($result)
            echo "hahahahah";
        else
            echo "aaaaaaaaaa";
    }
}
