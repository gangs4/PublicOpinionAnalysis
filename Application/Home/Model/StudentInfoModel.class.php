<?php
namespace Home\Model;
use Think\Model;

class StudentInfoModel extends Model {

	public function ShowGrade($grade,$page=0,$limit=30)
	{
		if(isset($grade))
			if($grade == 'all')
				$arr = $this->limit($page*30,$limit)->select();
			else
				$arr = $this->where("grade = $grade")->limit($page*30,$limit)->select();
		else
			$arr = $this->field('grade,count(*) as number')->group('grade')->select();;	
		return $arr;			

	}
	public function SearchByName($str)
	{
		$data['real_name'] =array('like',array("%$str%"));
		$arr = $this->where($data)->select();
		return($arr);
	}



	public function insert_info($name,$text)
	{
		$condition['screen_name'] = array('like',array("%$name%"));
		$data['keywords'] = $text;
		// $data['lda_pro'] = $text;
		$this->where($condition)->save($data);
	}
	public function insert_info2($name,$text)
	{
		$condition['real_name'] = array('like',array("%$name%"));
		$data['keywords'] = $text;
		// $data['lda_pro'] = $text;
		$this->where($condition)->save($data);		
	}
	// public function getWords()
	// {
	// 	$arr_words = $this->field('word,weight,times')->limit(8)->order('weight desc')->select();
	// 	return $arr_words;
	// }
}
