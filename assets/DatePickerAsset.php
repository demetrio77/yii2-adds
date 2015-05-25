<?php

namespace demetrio77\adds\assets;

class DatePickerAsset extends BaseAsset
{
	public $css = ['file/datepicker/jquery-ui.min.css'];
	public $js  = ['file/datepicker/jquery-ui.min.js', 'file/datepicker/datepicker.js'];
	public $depends = [];
}