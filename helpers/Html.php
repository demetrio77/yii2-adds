<?php

namespace yii\helpers;

use Yii;
use demetrio77\adds\widgets\JarvisWidget;

class Html extends BaseHtml
{
	public static $formNoWidget=false;
	
	public static function beginForm($action = '', $method = 'post', $options = [])
	{
		if (isset($options['nowidget'])) {
			self::$formNoWidget = true;
			return parent::beginForm($action, $method, $options);
		}
		JarvisWidget::begin(['title'=>isset($options['title'])?$options['title']:'']);
		return parent::beginForm($action, $method, $options);
	}
	
	public static function endForm() 
	{
		if (self::$formNoWidget) {
			self::$formNoWidget = false;
			return parent::endForm();
		}
		echo parent::endForm();
		JarvisWidget::end();
	}
	
	public static function activeDropDownMultiple($model, $attribute, $items = [], $options = [])
	{
		$view = Yii::$app->getView();
		\backend\assets\Select2Asset::register( $view );
		$id = self::getInputId($model, $attribute);
		$view->registerJs("$('#".$id."').select2();");
		$options['multiple'] = true;
		$options['style'] = 'padding:0; border:0;';
		return static::activeListInput('dropDownList', $model, $attribute, $items, $options);
	}
	
	public static function activeDateDropDown($model, $attribute, $options = [])
	{
		$opts = [];
		if (isset($options['minYear'])) {
			$opts[] = "minYear: ".$options['minYear'];
			unset($options['minYear']);
		}
		if (isset($options['maxYear'])) {
			$opts[] = "maxYear: ".$options['maxYear'];
			unset($options['maxYear']);
		}
		if (isset($options['defaultDate'])) {
			$opts[] = "defaultDate: ".$options['defaultDate'];
			unset($options['defaultDate']);
		}
		$id = Html::getInputId($model, $attribute).'Div';
		$view = Yii::$app->getView();
		\backend\assets\DateDropDownAsset::register($view);
		$view->registerJs("$('#".$id."').dateDropDown(".($opts?"{".implode(',', $opts)."}":'').");");
		return '<div id="'.$id.'" class="row">'.static::activeHiddenInput($model, $attribute, $options).'</div>';
	}
}