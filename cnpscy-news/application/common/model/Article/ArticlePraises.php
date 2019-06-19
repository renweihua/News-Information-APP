<?php

namespace app\common\model\Article;

use app\common\model\Common;

class ArticlePraises extends Common
{
    public $pk = 'praise_id';
    protected $autoWriteTimestamp = true;// 开启自动写入时间戳
}
