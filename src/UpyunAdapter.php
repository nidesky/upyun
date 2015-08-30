<?php

namespace Nidesky\Upyun;


use Exception;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use Nidesky\Upyun\Upyun;

class UpyunAdapter extends AbstractAdapter
{

    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * 判断文件或目录是否存在
     *
     * @param string $path 文件或目录的路径
     * @return bool
     */
    public function has($path)
    {
        try {
            $fileinfo = $this->client->has('/'.$path);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * 向指定路径写入内容
     *
     * @param string $path      文件路径地址
     * @param string $contents  文件内容
     * @param Config $config
     * @return mixed
     */
    public function write($path, $contents, Config $config)
    {
        return $this->client->write('/'.$path, $contents, true);
    }


    public function writeStream($path, $resource, Config $config)
    {
        // TODO: Implement writeStream() method.
    }

    /**
     * 更新文件
     *
     * @param string $path      文件路径
     * @param string $contents  文件内容
     * @param Config $config
     * @return mixed
     */
    public function update($path, $contents, Config $config)
    {
        try {
            $this->client->delete('/'.$path);
        }
        catch (Exception $e) {

        }

        return $this->write($path, $contents, $config);
    }


    public function updateStream($path, $resource, Config $config)
    {
        // TODO: Implement updateStream() method.
    }


    /**
     * 重命名文件
     *
     * @param string $path      文件原路径
     * @param string $newpath   文件新路径
     * @return bool
     */
    public function rename($path, $newpath)
    {
        try {
            $file = $this->client->read('/'.$path);

            $this->client->write('/'.$newpath, $file, true);

            $this->client->delete('/'.$path);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return false;
        }

        return true;

    }

    /**
     * 复制文件
     *
     * @param string $path      文件路径
     * @param string $newpath   目标路径
     * @return bool
     */
    public function copy($path, $newpath)
    {
        try {
            $file = $this->client->read('/'.$path);

            $this->client->write('/'.$newpath, $file, true);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return false;
        }

        return true;
    }


    /**
     * 删除文件或者路径
     *
     * @param string $path      路径地址
     * @return bool
     */
    public function delete($path)
    {
        try {
            $this->client->delete('/'.$path);
        } catch (Exception $e) {

        }

        return true;;
    }

    /**
     * 删除目录
     *
     * @param string $dirname   目录路径
     * @return bool
     */
    public function deleteDir($dirname)
    {
        return $this->delete($dirname);
    }

    /**
     * 创建目录
     *
     * @param string $dirname   目录路径
     * @param Config $config
     * @return bool
     */
    public function createDir($dirname, Config $config)
    {
        try {
            $this->client->createDir('/'.$dirname);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return false;
        }

        return true;

    }

    /**
     * 读取文件内容
     *
     * @param string $path      文件路径
     * @return bool
     */
    public function read($path)
    {
        try {
            $fileinfo = $this->client->read('/'.$path);
        } catch (Exception $e) {
            return false;
        }

        return $fileinfo;
    }


    public function readStream($path)
    {
        $stream = fopen('php://temp', 'w+');

        if (! $this->client->has('/'.$path, $stream)) {
            fclose($stream);
            return false;
        }
        rewind($stream);

        return compact('stream');
    }

    /**
     * 获取文件大小
     *
     * @param string $path      文件路径
     * @return bool
     */
    public function getSize($path)
    {
        try {
            $fileinfo = $this->client->has('/'.$path);
        } catch (Exception $e) {
            return false;
        }

        return $fileinfo['x-upyun-file-size'];
    }

    /**
     * 获取文件最后修改时间
     *
     * @param string $path      文件路径
     * @return bool
     */
    public function getTimestamp($path)
    {
        try {
            $fileinfo = $this->client->has('/'.$path);
        } catch (Exception $e) {
            return false;
        }

        return $fileinfo['x-upyun-file-date'];
    }

    /**
     * 获取文件列表
     *
     * @param string $directory         目录路径
     * @param bool|false $recursive     是否递归读取
     * @return bool
     */
    public function listContents($directory = '', $recursive = false)
    {
        try {
            $lists = $this->client->listContents('/'.$directory);
        } catch (Exception $e) {
            return false;
        }

        return $lists;
    }



    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
        return false;
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
        return false;
    }

    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
        return false;
    }


    public function setVisibility($path, $visibility)
    {
        return false;
    }


}