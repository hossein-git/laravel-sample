<?php

namespace Modules\Base;

use Illuminate\Support\Facades\Facade;

abstract class BaseFacade extends Facade
{
    public static function shouldProxyTo($class): void
    {
        app()->singleton(self::getFacadeAccessor(), $class);
    }

    /**
     * facade accessor set by namespace and class name in child classes.
     */
    protected static function getFacadeAccessor(): string
    {
        return static::class;
    }
}
