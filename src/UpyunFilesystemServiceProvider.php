<?php

namespace Nidesky\Upyun;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Storage;

class UpyunFilesystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('upyun', function($app, $config)
        {
            $client = new Upyun($config['bucket'], $config['username'], $config['password']);

            return new Filesystem(new UpyunAdapter($client));
        });
    }

    public function register()
    {

    }
}