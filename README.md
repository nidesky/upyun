# UpyunFilesystem for Laravel 5.1

---

> 本文参考又拍云官方SDK [upyun/php-sdk](https://github.com/upyun/php-sdk)

## 1. 安装
我们可以添加 `nidesky\upyun` 到 `composer.json` 中：

```php
{
    ...
    "require": {
        ...
        "nidesky/upyun": "dev-master"
    },
```
也可以在项目目录中运行 `composer require nidesky/upyun`。

## 2. 添加配置
首先，我们需要 `ServiceProvider` 到 `config/app.php` ：

```php
[
	...
	'providers' => [
		...
       Nidesky\Upyun\UpyunFilesystemServiceProvider::class,	
		...

    ],
    ...
]
```

然后我们要在 `config/filesystems.php` 中配置又拍云的信息：

```php
[
	...
	'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        ... 

        'upyun'     => [
            'driver'    => 'upyun',
            'bucket'    => env('UPYUN_BUCKET'),
            'username'  => env('UPYUN_USERNAME'),
            'password'  => env('UPYUN_PASSWORD')
        ]

    ],
]

```

OK , 配置完成！

## 3. 使用

### 上传文件
文件类空间可以上传任意形式的二进制文件

#### 1. 直接读取整个文件内容:

```php
$upyun = Storage::disk('upyun');

$upyun->put('/storage/uploads/file.txt', 'file contents');

```

#### 2. 文件流的方式上传，可降低内存占用:

```php

$file_handler = fopen('demo.png', 'r');

$upyun->putStream('/storage/uploads/file.txt', $file_handler);

```

### 下载文件

#### 1.直接读取文件内容:

```php
$data = $upyun->read('/temp/upload_demo.png');

```
#### 2.使用文件流模式下载:

```php
$fh = fopen('/tmp/demo.png', 'w');
$upyun->readStream('/temp/upload_demo.png', $fh);
fclose($fh);

```

直接获取文件时，返回文件内容，使用数据流形式获取时，成功返回true。 如果获取文件失败，则抛出异常。

### 创建目录

```php
$upyun->createDir('/demo/');

```
目录路径必须以斜杠 / 结尾，创建成功返回 true，否则抛出异常。


### 删除目录或者文件

```php
$upyun->delete('/demo/'); // 删除目录
$upyun->delete('/demo/demo.png'); // 删除文件

```