<?php

namespace SystemError;

abstract class AbstractSystemError
{
    abstract protected function getFileName($dir, $fileArr=[]);
}
