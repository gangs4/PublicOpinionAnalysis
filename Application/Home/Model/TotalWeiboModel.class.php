<?php
namespace Home\Model;
use Think\Model\RelationModel;

class TotalWeiboModel extends RelationModel {

    protected $_link = array(
        'User'  =>  array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name' => 'UserInfo',
            'foreign_key'   => 'userid',
            'parent_key'    => 'userid',
            'mapping_name' => 'user',
        ),
    );

    public function searchDetail($str)
    {
        $data['weiboid'] = $str;
        $arr = $this->relation(true)->where($data)->find();
        return $arr;
    }

    public function search($str)
    {
    	$data['content'] =array('like',array("%$str%"));
    	$arr = $this->relation(true)->where($data)->select();
    	return $arr;
    }




public function analysisPerson()//获取到具体人的发的所有信息（回复，发帖（根据idx），有theme
{
	$userid = $_GET["id"];
	$data["userid"] = $userid;
	$data["_logic"] = "AND";

	$week = array('Mon' => 0, "Tue" => 1, "Wed" => 2, "Thu" => 3, "Fri" => 4, "Sat" => 5, "Sun" => 6);
        $weekNumber = array();

        foreach ($week as $key => $value) {
            $data["time"] = array("like",array("%$key%"));
            $arr = $this->where($data)->select();
            $weekNumber[$value] = count($arr);
        }        

        $dayNumber = array();        
        for ($i=0; $i < 10; $i++) { 
            $data["time"] = array("like",array("%0$i:__:__ %"));
            $arr = $this->where($data)->select();
            $dayNumber[$i] = count($arr);
        }
        for ($i=10; $i < 24; $i++) { 
            $data["time"] = array("like",array("%$i:__:__ %"));
            $arr = $this->where($data)->select();
            $dayNumber[$i] = count($arr);
        }

        $arr = array($dayNumber,$weekNumber);
	return $arr;
}

public function total()
{
    //额，也不是很清楚SQL怎么写，然后tp就用的拙计了。。
    $arr_hour = $this->field('count(*) as cnt')->group('HOUR(TIME(time))')->select();
    $arr_day = $this->field('count(*) as cnt')->group('dayofweek(time)')->select();

    foreach ($arr_hour as $key => $value) {
        $arr_hour[$key] = (int)$value['cnt'];
    }
    foreach ($arr_day as $key => $value) {
        $arr_day[$key] = (int)$value['cnt'];
    }

    $arr = array($arr_hour,$arr_day);
    return $arr;
}
}