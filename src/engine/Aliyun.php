<?php

namespace chziyun\engine;

use OSS\Core\OssException;
use OSS\OssClient;

class Aliyun extends AbstractEngine
{
    protected $config;

    /**
     * Aliyun constructor.
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
     * @return mixed|null
     * @throws OssException
     */
    public function upload()
    {
        // 要上传图片的本地路径
        $realPath = $this->file->getRealPath();
        // 实例化OSS
        $ossClient = new OssClient($this->config['accessId'], $this->config['accessSecret'], $this->config['endpoint']);
        return $ossClient->uploadFile($this->config['bucket'], $this->fileName, $realPath);
    }
}
