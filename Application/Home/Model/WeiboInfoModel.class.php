<?php
namespace Home\Model;
use Think\Model\RelationModel;

class WeiboInfoModel extends RelationModel {

    protected $_link = array(
        'WeiboDetail' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'TotalWeibo',
            'foreign_key'   => 'weibo_id',
            'mapping_name'  => 'weibo',
        ),
    );
    public function search($str)
    {
    	$data['keyword'] =array('like',array("%$str%"));
    	$arr = $this->relation(true)->where($data)->select();

        $hotvalue = array();

        $quan = array(0.8,0.6,0.4);

        foreach ($arr as $key => $value) {
            $keywords = split(',',$value['keyword']);
            // dump($keywords);
            for ($i=0; $i < 3; $i++) { 
                // dump($keywords[$i]);
                $data = split('#', $keywords[$i]);
                if($data[0]==$str)
                {
                    $time = date('Y-m-d',strtotime($value['weibo']['time']));

                    $hotvalue[$time] += $data[1]*$quan[$i];
                }
                
            }
        }
        $total = array($arr,$hotvalue);
    	return $total;
    }
}
