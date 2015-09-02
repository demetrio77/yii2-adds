<?php

namespace demetrio77\adds\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use site\assets\StarRatingAsset;
//use backend\components\behaviors\ImageUploadBehavior;

class ActiveField extends \yii\widgets\ActiveField
{
	/**
     * Селект с данными из связанной модели
     * @param string $lookupModelName
     * @param string $lookupModelField
     * @param array $params
     * @param array $options
     * @return Ambigous <\yii\widgets\static, \backend\components\widgets\ActiveField>
     */
    public function lookupDropDownList( $lookupModelName, $lookupModelField, $params=[], $options = [] )
    {
        /**
        то, что может быть передано в params
         */
        $lookupModelPk = 'id';
        $allowEmpty    = true;
        $defaultEmptyPk = '0';
        $defaultEmptyValue = '';
        $where = [];
        $autoComplete = false;
        
    	$style = 'padding:0; border:0;';
    	if (!isset($options['style']))
    		$options['style'] = $style;
    	else
    		$options['style'] .= $style;
    	
    	$options = array_merge($this->inputOptions, $options);

    	foreach ($params as $property => $value) {
    		if (isset($$property)) $$property = $value;
    	}
    
    	$query = new \yii\db\Query();
    
    	$query->select([$lookupModelPk, $lookupModelField])
    	       ->from($lookupModelName)
    	       ->orderBy($lookupModelField);
    
    	if ($where) {
    		foreach ($where as $w)
    			$query->andWhere( $w );
    	}
    
    	$items = ArrayHelper::map(
    			$query->all(),
    			$lookupModelPk,
    			$lookupModelField
    	);
    	
    	if ($autoComplete) {
    		$view = Yii::$app->getView();
    		\backend\assets\Select2Asset::register( $view );
    		$id = Html::getInputId($this->model, $this->attribute);    		 
    		$js = "$(document).ready(function(){ $('#".$id."').select2({}); });";
    		$view->registerJs($js);
    	}
    
    	if ($allowEmpty) {
    		$items = \yii\helpers\ArrayHelper::merge([$defaultEmptyPk => $defaultEmptyValue], $items);
    	}
    
    	return $this->dropDownList( $items, $options );
    }
    
    public function colorInput( $options = [] )
    {
        $view = Yii::$app->getView();
        \backend\assets\ColorPickerAsset::register( $view );
        $id = Html::getInputId($this->model, $this->attribute);
        $view->registerJs("
            $('#".$id."').colorpicker().on('changeColor', function(ev) {
                $('#".$id."').css('background-color', ev.color.toHex() )
            });"
        );
        $options[ 'style'] = 'background-color: '.$this->model->{ $this->attribute};
        return $this->textInput( $options );
    }
    
    public function dateInput( $options = [])
    {
    	$view = Yii::$app->getView();
        \demetrio77\adds\assets\DatePickerAsset::register( $view );
        
        $this->inputOptions['class'] .= ' datepicker';
    	$options['data-dateformat'] = "yy-mm-dd";

    	$options = array_merge($this->inputOptions, $options);
    	$this->adjustLabelFor($options);
    	
    	$this->parts['{input}'] = '<div class="input-group">'.Html::activeTextInput($this->model, $this->attribute, $options).'<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>';
    	
    	return $this;
    }
    
    public function dateDropDown( $options = [])
    {
    	$this->parts['{input}'] = Html::activeDateDropDown($this->model, $this->attribute, $options);
    	return $this;
    }
    
    public function ckEditor ( $options = [])
    {
    	$view = Yii::$app->getView();
    	
    	\backend\assets\CkEditorAsset::register( $view );
    	$id = Html::getInputId($this->model, $this->attribute);
    	
    	$view->registerJs("$('#".$id."').ckeditor();");
    	return $this->textarea( $options );
    }
    
    public function dropDownListAutoComplete($items, $options = [])
    {
    	$showHints = false;
    	$hints = [];
    	
    	if (isset($options['hints'])) {
    		$h = $options['hints'];
    		foreach ($h as $key => $val) {
    			$hints[$key] = ['data-hint' => $val ];
    		}
    		unset( $options['hints']);
    		$showHints = true;
    	}
    	
    	if (isset($options['style'])) {
    		$options['style'].= '; padding:0;';
    	}
    	else {
    		$options['style'] = 'padding:0;';
    	}
    	
    	$view = Yii::$app->getView();
    	\demetrio77\adds\assets\Select2Asset::register( $view );
    	$id = Html::getInputId($this->model, $this->attribute);

    	$js = "$('#".$id."').select2({".($showHints ? "
		    formatResult: format,
			formatSelection: format":'')."
		});
		".($showHints ? "
		function format(state) {
			var originalOption = state.element;
			return state.text+\"<br /><span style=\'font-size:80%\'>\"+$(originalOption).data(\"hint\")+\"</span>\";			
		}" : "");
    	$view->registerJs($js);
    	
    	return $this->dropDownList($items, ArrayHelper::merge($options, [ 'options' => $hints ]));
    }
    
    public function dropDownMultiple( $items = [], $options = [], $tags = false)
    {
    	$style = 'padding:0; border:0;';
    	if (!isset($options['style'])) 
    		$options['style'] = $style;
   		else 
   			$options['style'] .= $style;
    	$options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        if ($tags) {
        	$view = Yii::$app->getView();
        	\backend\assets\Select2Asset::register( $view );
        	$id = Html::getInputId($this->model, $this->attribute);
        	$js = "
    	       $('#".$id."').select2({
    	       		tags:['".implode("','", $items)."'],
                    tokenSeparators: [',']
        		});
    	    ";
        	$this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);
        	$view->registerJs($js);
        } else {
        	$this->parts['{input}'] = Html::activeDropDownMultiple($this->model, $this->attribute, $items, $options);
        }
        return $this;
    }
    
    public function markInput($options=[])
    {
    	$view = Yii::$app->getView();
    	StarRatingAsset::register( $view );
    	
    	$this->inputOptions['class'] .= ' rating';
    	if (!isset($options['min'])) {
    		$options['min'] = 0;
    	}
    	if (!isset($options['max'])) {
    		$options['max'] = 5;
    	}
    	if (!isset($options['step'])) {
    		$options['step'] = 1;
    	}
    	if (!isset($options['disabled'])) {
    		$options['disabled'] = false;
    	}
    	
    	$options['data']['size'] = isset($options['size'])?$options['size']:'lg';
    	
    	$options['type'] = 'number';
    	$options['data']['show-clear']=false;
    	$options['data']['show-caption']=false;
    	
    	$options = array_merge($this->inputOptions, $options);
    	$this->adjustLabelFor($options);
    	 
    	$this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);
    	return $this;
    }
    
   /* public function imageInput( $options = [] )
    {
    	$view = Yii::$app->getView();
    	\backend\assets\ImageUploaderAsset::register( $view );
    	
    	$imageUploaderOptions = [];
    	if (isset($options['tmpl'])) {
    		$imageUploaderOptions['tmpl'] = $options['tmpl'];
    		unset($options['tmpl']);
    	} 
    	if (isset($options['callback'])) {
    		$imageUploaderOptions['callback'] = $options['callback'];
    		unset($options['callback']);
    	}
    	
    	
    	$options = array_merge($this->inputOptions, $options);
    	$this->adjustLabelFor($options);
    	$name = Html::getInputName($this->model, $this->attribute.$this->model->uploadFieldPostfix);
    	$id = Html::getInputId($this->model, $this->attribute);
    	
    	$js = "$(document).ready(function(){ 
    	       $('#".$id."').imageUploader({
                   name      : '".$name."',
                   value	 : '".$this->model->{$this->attribute}."',
                   uploadUrl : '".Url::toRoute(['upload'])."',
                   progressUrl: '".Url::toRoute(['progress'])."',
                   urlUrl: '".Url::toRoute(['url'])."',
                   csrfToken : '".Yii::$app->request->getCsrfToken()."',
                   ".(isset($imageUploaderOptions['tmpl'])?"tmpl:'".$imageUploaderOptions['tmpl']."',":'')."
                   ".(isset($imageUploaderOptions['callback'])?"callback:".$imageUploaderOptions['callback'].',':'')."
               });
    	   });";
    	$view->registerJs($js);
    	$this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute);    	
    	return $this;
    }*/
}