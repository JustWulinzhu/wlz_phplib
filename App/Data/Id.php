<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/17 下午3:50
 * Email: 18515831680@163.com
 */

namespace App\Data;

class Id {

    /**
     * 通过身份证号获取地址（省市区县）
     * @param $cert_no
     * @return string
     * @throws \Exception
     */
    public function getAddrById($cert_no) {
        $province_id = substr($cert_no, 0, 2);
        $city_id = substr($cert_no, 0, 4);
        $district_id = substr($cert_no, 0, 6);

        $province   = (new \App\Dao\Db\Province())->getInfoById($province_id);
        $city       = (new \App\Dao\Db\City())->getInfoById($city_id);
        $district   = (new \App\Dao\Db\District())->getInfoById($district_id);

        return $province['name'] . $city['name'] . $district['name'];
    }

}