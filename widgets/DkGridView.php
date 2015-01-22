<?php

namespace demetrio77\adds\widgets;

use yii\grid\GridView;

class DkGridView extends GridView 
{
    public $layout = '<div class="alert alert-info no-margin fade in">{pager}</div>{items}';
    public $title;

    public function run() {
       	JarvisWidget::begin(['title'=>$this->renderSummary(), 'nopadding'=>true]);
    	parent::run();
    	JarvisWidget::end();
    }
}