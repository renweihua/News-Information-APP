<?php

namespace app\common\interfaces;

interface Event
{
    public static function init(array $params = [], $other);
}