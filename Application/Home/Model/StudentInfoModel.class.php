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

	public function OneToAll($id,$other)
	{
		$data['lda_pro'] = array('NEQ',"");
		$data['real_name'] = array('NEQ',"NULL");
		$data['id'] = array('NEQ',$id);
		$arr = $this->field('id,real_name,avatar_hd,lda_pro')->where($data)->order('id ASC')->select();
		$CDArray = array();
		if (!isset($other)) {
			$self = $this->where(array('id'=>$id))->select();
			$Mself = $this->pickitup($self[0]['lda_pro']);
		}
		else//在线传的
		{
			$Mself = $other;
		}
		foreach ($arr as $key => $value) {
			$CDArray[] = array($value,$this->CosDistance($Mself,$this->pickitup($value['lda_pro'])));
		}
		//根据二维数据的某个值排序
		foreach ($CDArray as $key => $value) {
            $temp[$key] = $value[1];
        }
        array_multisort($temp, SORT_DESC,$CDArray);
        // dump($CDArray);
		return $CDArray;
	}
	public function pickitup($lda_pro)
	{
		$arr = explode('|', $lda_pro);
		array_pop($arr);
		return $arr;
	}
	private function CosDistance($m1,$m2)
	{
		$sum = 0;
		$chu1 = 0;
		$chu2 = 0;
		for ($i=0; $i < 9; $i++) { 
			$sum += $m1[$i]*$m2[$i];
			$chu1 += pow($m1[$i], 2);
			$chu2 += pow($m2[$i], 2);
		}
		$cd = $sum/( sqrt($chu1)*sqrt($chu2) );
		return $cd;
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
