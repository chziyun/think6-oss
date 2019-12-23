<?php

namespace chziyun\engine;

use think\facade\Filesystem;

class Local extends AbstractEngine
{
    protected $config;

    /**
     * Local constructor.
     * @param $file
     * @param $config
     */
    public function __construct($file, $config)
    {
        parent::__construct($file);
        $this->config = $config;
    }

    /**
     * 执行上传
     * @return mixed
     */
    public function upload()
    {
        return Filesystem::putFile('', $this->file);
    }
}
