<?php

namespace app\common\event;

use app\common\interfaces\Event;

/**
 * 抽奖
 * Class LuckdrawHandleEvent
 * @package app\common\event
 */
class LuckdrawHandleEvent implements Event
{
    public static function init($params = [])
    {
        $prize_arr = array(
            '0' => array('id' => 1, 'title' => 'iphone5s', 'v' => 0.2, 'num' => 10),
            '1' => array('id' => 2, 'title' => '联系笔记本', 'v' => 0.2, 'num' => 10),
            '2' => array('id' => 3, 'title' => '音箱设备', 'v' => 0.5, 'num' => 10),
            '3' => array('id' => 4, 'title' => '30GU盘', 'v' => 4, 'num' => 10),
            '4' => array('id' => 5, 'title' => '话费50元', 'v' => 5, 'num' => 10),
            '5' => array('id' => 6, 'title' => 'iphone6s', 'v' => 0.1, 'num' => 10),
            '6' => array('id' => 7, 'title' => '谢谢，继续加油哦！~', 'v' => 90, 'num' => 10),
        );

        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }

        var_dump($arr);

        $prize_id = get_rand($arr); //根据概率获取奖品id
        $data['msg'] = ($prize_id >= 7) ? 0 : 1; //如果为0则没中
        $data['prize_title'] = $prize_arr[$prize_id - 1]['title']; //中奖奖品

        var_dump($data);
        exit;
        echo json_encode($data);
        exit; //以json数组返回给前端

        function get_rand($proArr)
        { //计算中奖概率
            $rs = ''; //z中奖结果
            $proSum = array_sum($proArr); //概率数组的总概率精度
            //概率数组循环
            foreach ($proArr as $key => $proCur) {
                $randNum = mt_rand(1, $proSum);
                if ($randNum <= $proCur) {
                    $rs = $key;
                    break;
                } else $proSum -= $proCur;
            }
            unset($proArr);
            return $rs;
        }
    }
}