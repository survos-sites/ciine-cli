<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        if ($this->isRunningFromPhar()) {
//            die("Stopped, running phar is already running");
        }
        if (isset($_SERVER['HOME'])) {
            return $_SERVER['HOME'] . '/.myapp/cache/' . $this->environment;
        }
        return sys_get_temp_dir() . '/myapp_cache/' . $this->environment;
    }

    private function isRunningFromPhar(): bool
    {
        return strpos(__FILE__, 'phar://') === 0;
    }

}
