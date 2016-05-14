<?php
namespace Home\Model;
use Think\Model\RelationModel;

class QuestionModel extends RelationModel {
	
	//有点问题。。
    protected $_link = array(
        'Answer' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Answer',
            'foreign_key'   => 'question_id',
            'parent_key'    => 'question_id',
            'mapping_name'  => 'answers',
        ),
    );

	public function getHotquestions($count)
	{
		return $this->order("answer_number DESC")->limit($count)->select();
	}

	public function search($str)
	{
    	$data['name'] =array('like',array("%$str%"));
		$arr = $this->where($data)->order('answer_number desc')->select();
		return $arr;
	}
	public function searchByid($str)
	{
    	$data['question_id'] =$str;
		$arr = $this->where($data)->relation(true)->select();
		// dump($arr);
		return $arr;
	}
}
