# think6-oss
thinkphp6文件上传oss整合

## 支持
1. 本地存储
2. 七牛云
3. 阿里云

## 包含
1. php >= 7.1
2. thinkphp >=6.0.0
3. aliyuncs/oss-sdk-php >= 2.3
4. qiniu/php-sdk >= 7.2

## 安装
第一步：
```shell
$ composer require chziyun/think6-oss
```
第二步：
在config/filesystem.php中disks数组下添加
```
'aliyun' => [
    'type'         => 'aliyun',
    'accessId'     => '******',
    'accessSecret' => '******',
    'bucket'       => 'bucket',
    'endpoint'     => 'oss-cn-hongkong.aliyuncs.com',
    'url'          => 'http://oss-cn-hongkong.aliyuncs.com',
],
'qiniu'  => [
    'type'      => 'qiniu',
    'accessKey' => '******',
    'secretKey' => '******',
    'bucket'    => 'bucket',
    'url'       => 'http://oss.s3-cn-east-1.qiniucs.com',
]
```
## 使用
```
use chziyun\Filesystem;

$file = $this->request->file('file');
$filesystem = new Filesystem($file);
// 上传到默认磁盘
$fileName = $filesystem->upload();
// 指定上传位置并保存到数据库
$fileName = $filesystem->setDisk('qiniu')->addFilesystem('image')->upload();
echo $fileName;
```
调用addFilesystem()可以保存文件信息到数据库
表结构如下
```
CREATE TABLE `filesystem` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `storage` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '存储位置',
  `file_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '访问地址',
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `file_size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '大小',
  `file_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件后缀',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `path_idx` (`file_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## 感谢
1. thinkphp
2. aliyuncs/oss-sdk-php
3. qiniu/php-sdk