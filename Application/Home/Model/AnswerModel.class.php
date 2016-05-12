<?php
namespace Home\Model;
use Think\Model;

class AnswerModel extends Model {

	public function getHotusers($count=10)
	{
        $arr = $this->query("SELECT count(*) as frequent,name 
            FROM `answer` 
            WHERE name != '匿名用户' 
            group by name
            ORDER BY frequent DESC LIMIT $count");
        return $arr;
	}

	public function searchuser($str)
	{
    	$data['name'] =array('like',array("%$str%"));
		$arr = $this->field('name , count(*) as frequent')->where($data)->group('name')->order('frequent desc')->select();
		return $arr;
	}
	//接收一个数组中含有 分数据库中 ~ | 格式保存的关键词
	private function pickitup($arr,$limit)
	{
		foreach ($arr as  $value) {
			$words = split('\|',$value['keywords']);
	        foreach ($words as $key => $v) {
	            $data = split('~',$v);
	            $keywords["$data[0]"] += $data[1];
	        }
		}
		arsort($keywords);
		if (isset($limit)) {
			$keywords = array_slice($keywords,0,$limit);
		}

		return $keywords;
	}

	public function GetKeywords($str)
	{
		$data['name'] =array('like',array("%$str%"));
		$arr = $this->where($data)->field('keywords')->select();
		$keywords = $this->pickitup($arr);
		return $keywords;
	}
	public function GetAvatar($str)
	{
		$data['name'] = array('like',array("%$str%"));
		$array = $this->where($data)->limit(0,1)->select();
		return $array[0]['avatar_url'];
	}

	public function analysis($ans)
	{
		$lenth = count($ans);
		if($lenth>20)
		{
			$arr = $this->divier($ans,5);
		}
		elseif ($lenth>8) {
			$arr = $this->divier($ans,3);
		}
		else
		{
			$arr = $this->divier($ans,2);
		}
		return $arr;
	}
	//answer 划分时间
	private function divier($ans,$parts)
	{
		$lenth = count($ans);
		$size = ceil($lenth/$parts);
		$arr = array();
		for ($i=0; $i < $parts; $i++) { 
			$sum_time = 0 ;
			$temp = array();
			for($j=$i*$size; $j < ($i+1)*$size;$j++)
			{
				if(isset($ans[$j]))
				{
					$temp[] = $ans[$j];
					$sum_time +=  strtotime($ans[$j]['answer_time']);					
				}
				else break;
			}
			$arr[$i]['time'] = $sum_time/count($temp);
			$arr[$i]['keywords'] = $this->pickitup($temp,10);
		}
		return $arr;
	}

	public function search($str)
	{
		$sql = "SELECT count(*) as frequent,an2.`name`
				FROM `answer` as an1 ,`answer` as an2 
				WHERE an1.`question_id` = an2.`question_id` 
					and an1.`name` = '$str'
					and an2.`name` != '$str'
					and an2.`name` != '匿名用户' 
				group by an2.`name`
				ORDER BY `frequent` DESC";
		$arr = $this->query($sql);
		return $arr;
	}
}
