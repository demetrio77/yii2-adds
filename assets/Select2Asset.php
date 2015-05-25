<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace demetrio77\adds\assets;

class Select2Asset extends BaseAsset
{
    public $sourcePath = '@demetrio77/adds/assets/select2';
    public $css = ['select2.css'];
    public $js = ['select2.min.js','select2_locale_ru.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
