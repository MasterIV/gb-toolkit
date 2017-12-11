<?php

class widget_box {
	protected $title;
	protected $content;
	protected $max_width;

	public function __construct($content, $title = "Content Box", $max_width = NULL) {
		$this->title = $title;
		$this->content = $content;
		$this->max_width = $max_width;
	}

	public function __toString() {
		return template('box')->render(array(
			'title' => $this->title,
			'content' => $this->content,
			'max_width' => $this->max_width));
	}
}
