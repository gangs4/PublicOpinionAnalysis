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

	public function GetKeywords($str)
	{
		$data['name'] =array('like',array("%$str%"));
		$arr = $this->where($data)->field('keywords')->select();
		$keywords = array();
		foreach ($arr as  $value) {
			$words = split('\|',$value['keywords']);
	        foreach ($words as $key => $v) {
	            $data = split('~',$v);
	            $keywords["$data[0]"] += $data[1];
	        }
		}
		arsort($keywords);
		return $keywords;
	}
	public function GetAvatar($str)
	{
		$data['name'] = array('like',array("%$str%"));
		$array = $this->where($data)->limit(0,1)->select();
		return $array[0]['avatar_url'];
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
