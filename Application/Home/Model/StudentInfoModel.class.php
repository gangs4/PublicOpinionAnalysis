<?php
namespace Home\Model;
use Think\Model;

class StudentInfoModel extends Model {

	public function ShowGrade($grade)
	{
		if(isset($grade))
			$arr = $this->where("grade = $grade")->select();
		else
			$arr = $this->field('grade,count(*) as number')->group('grade')->select();;	
		return $arr;			

	}
	// public function getWords()
	// {
	// 	$arr_words = $this->field('word,weight,times')->limit(8)->order('weight desc')->select();
	// 	return $arr_words;
	// }
}
