<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();
        date_default_timezone_set("Europe/Paris");
        setlocale(LC_TIME, array('fr_FR.UTF-8', 'fr_FR@euro', 'fr_FR', 'french'));
    }
}
