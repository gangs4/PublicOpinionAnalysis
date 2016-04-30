<?php
namespace Home\Controller;
use Think\Controller;
class DataAnalysisController extends Controller {
    public function index(){
    	$this->assign('data','something');
		$this->display();

    }

    public function analysis()
    {
    	$text = $_POST['text'];

    	$time_arr = array();
    	preg_match_all('/(\d{1,2}分钟前)|(\d{1,2}月\d{1,2}日 \d\d:\d\d)|(今天 \d{2}:\d{2})|(\d{4}-\d{2}-\d{2} \d{2}:\d{2})/', $text , $time_arr);
    	$data_arr = preg_split('/(\d{1,2}分钟前)|(\d{1,2}月\d{1,2}日 \d\d:\d\d)|(今天 \d{2}:\d{2})|(\d{4}-\d{2}-\d{2} \d{2}:\d{2})/', $text);

    	$all_data = $this->combine($time_arr[0],$data_arr);

    	//数据都在这了，然后是要调用那个方法。。似乎要改一下。。
//    	dump($all_data);
        // 实例化控制器
		$emotion = A('Emotion');

		foreach ($all_data as $key => $value)
		{
			$content=$value['data'];
//			dump($content);
			$eval = $emotion->analysis($content);
			$all_data[$key]['emotion'] = $eval['emotion'];
			$all_data[$key]['prob'] = $eval['prob'];
//			dump ($all_data[$key]);
		}

        $fileDir = "./temp/" ;
        $t = time();
        $fileName = $t."_text";

        $myfile = fopen($fileDir.$fileName.".in", "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
        
        // dump($fileName);
        // $lda_arr = $emotion->LDA($fileName);

		// dump($all_data);
        // dump($lda_data);
        $lda = array(array("0.2214170692431562","0.11996779388083736" , "0.061996779388083734" , "0.09098228663446055" , "0.06924315619967794" , "0.047504025764895326" , "0.07648953301127213" , "0.25764895330112725" , "0.05475040257648953" ),
            array(array('中国',0.0023619816463523),array('国家',0.0012201543183082),array("重要",0.0012181839519095)));

        // dump($lda);
        $this->assign('emotion',json_encode($all_data));
        $this->assign('word',json_encode($lda[1]));
        $this->assign('model',json_encode($lda[0]));
        $this->display();
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
    		$arr[$key] = array('time'=>$format_time,'data'=>$data_arr[$key+1]);
    	}
    	// dump($arr);
    	return $arr;
    }

}

