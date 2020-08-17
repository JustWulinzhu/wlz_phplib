<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/8/17 下午3:34
 * Email: 18515831680@163.com
 */

namespace App\Dao\Db;

class District
{

    public $table = 'id_district';
    private $db = null;

    /**
     * Province constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->db = new \S\Db($this->table);
    }

    /**
     * @param $data
     * @return array|bool
     * @throws \Exception
     */
    public function add($data)
    {
        return $this->db->insert($data);
    }

    /**
     * @param $id
     * @return array|mixed
     * @throws \Exception
     */
    public function getInfoById($id) {
        return $this->db->queryone(['did' => $id]);
    }

}