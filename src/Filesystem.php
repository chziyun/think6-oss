<?php

namespace chziyun;

use think\Exception;
use think\facade\Config;
use think\facade\Db;

class Filesystem
{
    protected $file; // 上传的文件
    protected $config; // 上传配置
    protected $engine; // 当前存储类

    /**
     * Filesystem constructor.
     * @param $file
     * @throws Exception
     */
    public function __construct($file)
    {
        $this->file = $file;
        if (empty($this->file)) {
            throw new Exception('未找到文件信息');
        }
    }

    /**
     * 设置上传位置
     * @param $disk
     * @return $this
     * @throws Exception
     */
    public function setDisk($disk)
    {
        $this->config = $this->getDiskConfig($disk);
        $this->engine = $this->getEngineClass();
        return $this;
    }

    /**
     * 执行上传
     * @return mixed
     * @throws Exception
     */
    public function upload()
    {
        // 设置默认上传引擎
        if (empty($this->engine)) {
            $disk = Config::get('filesystem.default');
            $this->setDisk($disk);
        }

        $this->engine->upload(); // 执行上传
        return $this->getFileName();
    }

    /**
     * 获取上传后的文件名
     * @return mixed
     */
    public function getFileName()
    {
        return $this->engine->getFileName();
    }

    /**
     * 保存到文件管理数据库
     * @param $fileType
     * @return $this
     */
    public function addFilesystem($fileType)
    {
        $uploadFile = [
            'storage' => $this->config['type'],
            'file_url' => isset($this->config['url']) ? $this->config['url'] : '',
            'file_name' => $this->getFileName(),
            'file_size' => $this->file->getSize(),
            'file_type' => $fileType,
            'extension' => $this->file->extension(),
            'create_time' => time()
        ];

        Db::name('filesystem')->insert($uploadFile);
        return $this;
    }

    /**
     * @param $disk
     * @return mixed
     * @throws Exception
     */
    private function getDiskConfig($disk)
    {
        $config = Config::get('filesystem.disks');
        if (! isset($config[$disk])) {
            throw new Exception('磁盘列表不存在');
        }

        return $config[$disk];
    }

    /**
     * @throws Exception
     */
    private function getEngineClass()
    {
        $engineName = $this->config['type'];
        $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst($engineName);
        if (!class_exists($classSpace)) {
            throw new Exception('未找到存储引擎类: ' . $engineName);
        }

        return new $classSpace($this->file, $this->config);
    }
}
