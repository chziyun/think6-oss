<?php

namespace chziyun\engine;

abstract class AbstractEngine
{
    protected $file; // 上传的文件
    protected $fileName; // 保存文件名
    protected $fileInfo; // 文件详情

    /**
     * AbstractEngine constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->fileName = $this->buildSaveName(); // 生成文件名
        $this->fileInfo = $this->file->getFileInfo(); // 文件详细信息
    }

    /**
     * 执行上传
     * @return mixed
     */
    abstract public function upload();

    /**
     * 返回上传后的文件名
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * 返回文件信息
     * @return mixed
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * 生成文件名
     * @return string
     */
    public function buildSaveName()
    {
        return date('YmdHis') . substr(md5($this->file->getRealPath()), 0, 5)
            . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . '.' . $this->file->extension();
    }
}
