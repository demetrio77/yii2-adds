<?php

namespace yii\helpers;

use Yii;

class Html extends BaseHtml
{
	public static function radio($name, $checked = false, $options = [])
	{
		$options['checked'] = (bool) $checked;
		$value = array_key_exists('value', $options) ? $options['value'] : '1';
		if (isset($options['uncheck'])) {
			// add a hidden field so that if the radio button is not selected, it still submits a value
			$hidden = static::hiddenInput($name, $options['uncheck']);
			unset($options['uncheck']);
		} else {
			$hidden = '';
		}
		if (isset($options['label'])) {
			$label = $options['label'];
			$labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
			unset($options['label'], $options['labelOptions']);
			/*
			 * добавлено
			 */if (isset($options['class']) && $options['class']=='styled') {
				$options['id'] = 'radio-'.$name.'-'.$value;
				$content = static::input('radio', $name, $value, $options).' '.static::label($label, $options['id'], $labelOptions);
			}
			else {/* до сюда */
				$content = static::label(static::input('radio', $name, $value, $options) . ' ' . $label, null, $labelOptions);
			}
			return $hidden . $content;
		} else {
			return $hidden . static::input('radio', $name, $value, $options);
		}
	}
	
	public static function checkbox($name, $checked = false, $options = [])
	{
		$options['checked'] = (bool) $checked;
		$value = array_key_exists('value', $options) ? $options['value'] : '1';
		if (isset($options['uncheck'])) {
			// add a hidden field so that if the checkbox is not selected, it still submits a value
			$hidden = static::hiddenInput($name, $options['uncheck']);
			unset($options['uncheck']);
		} else {
			$hidden = '';
		}
		if (isset($options['label'])) {
			$label = $options['label'];
			$labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
			unset($options['label'], $options['labelOptions']);
			/*
			 * добавлено
			*/if (isset($options['class']) && $options['class']=='styled') {
				$options['id'] = 'check-'.$name.'-'.$value;
				$content = static::input('checkbox', $name, $value, $options).' '.static::label($label, $options['id'], $labelOptions);
			}
			else {/* до сюда */
				$content = static::label(static::input('checkbox', $name, $value, $options) . ' ' . $label, null, $labelOptions);
			}
			return $hidden . $content;
		} else {
			return $hidden . static::input('checkbox', $name, $value, $options);
		}
	}
}