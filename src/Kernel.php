<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        if (isset($_SERVER['HOME'])) {
            return $_SERVER['HOME'] . '/.myapp/cache/' . $this->environment;
        }
        return sys_get_temp_dir() . '/myapp_cache/' . $this->environment;
    }
}
