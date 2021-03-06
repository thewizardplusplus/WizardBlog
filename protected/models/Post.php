<?php

class Post extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public static function formatTime($time) {
		$parts = explode(' ', $time);
		return '<time>' . implode('.', array_reverse(explode('-', $parts[0]))) .
			' ' . $parts[1] . '</time>';
	}

	public static function processText($view, $text) {
		switch ($view) {
			case 'list':
				$text = self::cut($text);
				break;
			case 'view':
				$text = preg_replace(Constants::CUT_TAG_PATTERN, '', $text);
				break;
		}

		$translator = new CMarkdown;
		$text = $translator->transform($text);

		$text = preg_replace(
			'/<img\s/',
			'<img class = "img-responsive" ',
			$text
		);
		$text = preg_replace(';\s+</pre>;', '</pre>', $text);
		$text = str_replace(
			'<table>',
			'<div class = "table-responsive">'
				. '<table class = "table table-bordered table-striped">',
			$text
		);
		$text = str_replace('</table>', '</table></div>', $text);

		return $text;
	}

	public static function processDescription($text) {
		$text = self::cut($text);
		$text = preg_replace('/!\[[^\]]*\]\([^\)]+\)/', '', $text);
		$text = preg_replace('/[\n\r]+/', ' ', $text);
		$text = trim($text);
		return $text;
	}

	public function tableName() {
		return '{{posts}}';
	}

	public function rules() {
		return array(
			array('title, text', 'required'),
			array('title', 'length', 'max' => Constants::
				MAXIMAL_LENGTH_OF_TITLE_FIELD),
			array('tags', 'safe'),
			array('published', 'numerical', 'min' => 0, 'max' => 1)
		);
	}

	public function attributeLabels() {
		return array(
			'title' => 'Заголовок:',
			'text' => 'Текст:',
			'create_time' => 'Дата создания:',
			'modify_time' => 'Дата изменения:',
			'tags' => 'Теги:',
			'published' => 'Опубликован'
		);
	}

	protected function beforeSave() {
		$result = parent::beforeSave();
		if ($result) {
			$current_time = date("Y-m-d H:i:s");
			if ($this->isNewRecord) {
				$this->create_time = $current_time;
			}
			$this->modify_time = $current_time;

			$this->tags = implode(',', array_map('trim', explode(',', $this->
				tags)));

			return true;
		} else {
			return false;
		}
	}

	private static function cut($text) {
		$result = preg_match(Constants::CUT_TAG_PATTERN, $text,
			$matches, PREG_OFFSET_CAPTURE);
		if ($result) {
			$text = substr($text, 0, $matches[0][1]);
		}

		return $text;
	}
}
