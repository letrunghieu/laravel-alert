<?php

namespace HieuLe\Alert\Facades;

use \Illuminate\Support\Facades\Facade;

/**
 * Alert facade
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class Alert extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'alert';
    }

}
