<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/17 下午2:31
 * Email: 18515831680@163.com
 *
 * 导入省市区街道信息
 *
 */

namespace Job\Jobs\Tmp;

class Import implements \Job\Base
{

    /**
     * @param null $argv
     * @return mixed|void
     * @throws \OSS\Core\OssException
     * @throws \S\Exceptions
     */
    public function exec($argv = null) {
        throw new \S\Exceptions("has done");
        //省份
        $province = (new \S\Office\Excel())->read(APP_ROOT_PATH . DIRECTORY_SEPARATOR . "province_ids.xlsx", 0);
        array_shift($province);

        foreach ($province as $p) {
            $data_province = [
                'name' => $p[0],
                'pid' => $p[1],
            ];
            (new \App\Dao\Db\Province())->add($data_province);
        }

        //城市
        $city = (new \S\Office\Excel())->read(APP_ROOT_PATH . DIRECTORY_SEPARATOR . "province_ids.xlsx", 1);
        array_shift($city);

        foreach ($city as $c) {
            $data_city = [
                'name' => $c[0],
                'cid' => $c[1],
            ];
            (new \App\Dao\Db\City())->add($data_city);
        }

        //区
        $district = (new \S\Office\Excel())->read(APP_ROOT_PATH . DIRECTORY_SEPARATOR . "province_ids.xlsx", 2);
        array_shift($district);

        foreach ($district as $d) {
            $data_district = [
                'name' => $d[0],
                'did' => $d[1],
            ];
            (new \App\Dao\Db\District())->add($data_district);
        }

        //街道
        $street = (new \S\Office\Excel())->read(APP_ROOT_PATH . DIRECTORY_SEPARATOR . "province_ids.xlsx", 3);
        array_shift($street);

        foreach ($street as $s) {
            $data_street = [
                'name' => $s[0],
                'sid' => $s[1],
            ];
            (new \App\Dao\Db\Street())->add($data_street);
        }
    }

}