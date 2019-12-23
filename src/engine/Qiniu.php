<?php

namespace chziyun\engine;

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
use think\Exception;

class Qiniu extends AbstractEngine
{
    protected $config;

    /**
     * Qiniu constructor.
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
     * @throws Exception
     */
    public function upload()
    {
        // 要上传图片的本地路径
        $realPath = $this->file->getRealPath();

        $upManager = new UploadManager();
        $auth = new Auth($this->config['accessKey'], $this->config['secretKey']);
        $token = $auth->uploadToken($this->config['bucket']);
        list($ret, $error) = $upManager->put($token, $this->fileName, $realPath);

        if ($error !== null) {
            throw new Exception($error->message());
        }

        return $ret;
    }
}
