<?php

namespace demetrio\adds\components;

use yii\web\AssetBundle;

class BaseAsset extends AssetBundle
{
	public static function getPublishedUrl() {
		$obj = new static();
		return \Yii::$app->assetManager->getPublishedUrl($obj->sourcePath).DIRECTORY_SEPARATOR;
	}
	
	public static function getUrl($assetDir){
		return static::getPublishedUrl().$assetDir.DIRECTORY_SEPARATOR;
	}
}