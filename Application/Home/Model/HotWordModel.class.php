<?php
namespace Home\Model;
use Think\Model;

class HotWordModel extends Model {


	public function getWords()
	{
		$arr_words = $this->field('word,weight,times')->limit(8)->order('weight desc')->select();
		return $arr_words;
	}
}
