<?php

namespace SystemError;

require_once('package/AbstractSystemError.php');

class SystemError extends AbstractSystemError
{

    const SYS_ERROR_PATH = '/data/log/php-error/';

    /**
     * 获取文件内容
     * @return array
     */
    public function getFileContentDesc()
    {
        $fileArr = $this->getFileName(self::SYS_ERROR_PATH.date('Y-m'), []);
        $list = [];
        if(empty($fileArr) === false ){
            foreach($fileArr as $k=>$v) {
                $handle = @fopen($k, 'r');
                if($handle) {
                    while (($buffer = fgets($handle, 4096)) !== false) {
                        array_unshift($list, $buffer);
                    }
                }
            }
        }

        return $list;
    }

    /**
     * 计算出最新的文件名
     */
    protected function getFileName($dir, $fileArr=[])
    {
        $handle = opendir($dir);
        while(false !== ($file = readdir($handle))) {
            if($file !== '.' && $file !== '..') {
                $fileName = $dir.'/'.$file;
                if( is_dir($fileName) ) {
                    $secondArr = $this->getFileName($fileName ,$fileArr);
                    $fileArr = array_merge($secondArr, $fileArr);
                }
                if(is_file($fileName)) {
                    $fileArr[$fileName] = filectime($fileName);
                }
            }
        }
        if(!empty($fileArr)) {
            asort($fileArr);
        }

        return $fileArr;
    }


}