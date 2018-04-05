<?php

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
        $fileArr = $this->getFileName();
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
    protected function getFileName()
    {
        $handle = opendir(self::SYS_ERROR_PATH.date('Y-m').'/');
        $fileArr = [];
        while(false !== ($file = readdir($handle))) {
            if($file !== '.' && $file !== '..') {
                if( is_dir(self::SYS_ERROR_PATH.date('Y-m').'/'.$file) ) {
                    $handle = opendir(self::SYS_ERROR_PATH.date('Y-m').'/'.$file);
                    while(false !== ($file1 = readdir($handle))) {
                        if ($file1 !== '.' && $file1 !== '..') {
                            $fileName = self::SYS_ERROR_PATH.date('Y-m').'/'.$file.'/'.$file1;
                            if(is_file($fileName)) {
                                $fileArr[$fileName] = filectime($fileName);
                            }
                        }

                    }
                }

            }
        }
        asort($fileArr);

        return $fileArr;
    }


}