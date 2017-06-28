<?php
/**
 * Created by PhpStorm.
 * User: lacorey
 * Date: 16/9/20
 * Time: 下午6:34
 */

namespace EchoBool\AlipayLaravel\Facades;


use Illuminate\Support\Facades\Facade;

class Alipay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Alipay';
    }
}