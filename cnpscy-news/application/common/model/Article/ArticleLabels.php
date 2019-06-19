<?php

namespace app\common\model\Article;

use app\common\model\Common;

class ArticleLabels extends Common
{
    public $pk = 'label_id';
    protected $autoWriteTimestamp = true;// 开启自动写入时间戳
}
