<?php

namespace demetrio77\adds\widgets;

use yii\widgets\DetailView;

class DkDetailView extends DetailView {
    
    public $template = '<tr><th class="col-xs-6 col-sm-4 col-md-3 col-lg-3">{label}</th><td>{value}</td></tr>';
    public $title = '';

    public function run() {
       	JarvisWidget::begin(['title'=>$this->title]);
    	parent::run();
    	JarvisWidget::end();
    }    
}