<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 20/4/15 下午6:44
 * Email: 18515831680@163.com
 *
 * Zip操作
 * 需要安装zip扩展
 *
 */

namespace S\Compress;

class Zip {

    private $zip;

    public function __construct() {
        $this->zip = new \ZipArchive();
    }

    /**
     * 压缩文件 (单个、多个)
     * @param string|array $file_path 要压缩的文件路径
     * @param string $zip_path 压缩后的文件路径
     * @param string $dir_name 压缩后的包里的目录名称
     * @param string $new_file_name 压缩后包里的新文件名
     * @return bool
     * @throws \S\Exceptions
     * @throws \Exception
     */
    public function create($file_path, $zip_path, $dir_name = '', $new_file_name = '') {
        try {
            $this->zip->open($zip_path, \ZipArchive::CREATE); //打开压缩包，没有则创建

            if (is_array($file_path)) { //多文件压缩
                if ($new_file_name) {
                    throw new \S\Exceptions('多个文件压缩不支持自定义文件名！');
                }
                if ($dir_name) {
                    $this->zip->addEmptyDir($dir_name);
                }
                foreach ($file_path as $path) {
                    $new_file_name = basename($path);
                    if ($dir_name) {
                        $new_file_name = $dir_name . DIRECTORY_SEPARATOR . $new_file_name;
                    }
                    $this->zip->addFile($path, $new_file_name);
                }
            } else { //单文件压缩
                $new_file_name = $new_file_name ? $new_file_name : basename($file_path);
                if ($dir_name) {
                    $this->zip->addEmptyDir($dir_name);
                    $new_file_name = $dir_name . DIRECTORY_SEPARATOR . $new_file_name;
                }
                $this->zip->addFile($file_path, $new_file_name);
            }
            $this->zip->close();
        } catch (\S\Exceptions $e) {
            throw new \S\Exceptions($e->getMessage(), $e->getCode());
        }

        return true;
    }

}