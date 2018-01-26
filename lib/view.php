<?php

class view {
	public $format = 'html';
	protected $context = array();
	protected $content = array();
	protected $file;

	/**
	 * @param mixed $file
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	public function __construct($file) {
		$this->file = $file;

		$this->context['js'] = [
				'js/jquery.min.js',
				'js/jquery-ui.min.js',
				'js/bootstrap.min.js',
				'js/system.js',
		];

		$this->context['css'] = [
				'css/jquery-ui.min.css',
				'css/bootstrap.min.css',
				'css/bootstrap-responsive.min.css',
				'css/system.css',

		];

		if (isset($_GET['plain'])) $this->format = 'plain';
		if (isset($_GET['json'])) $this->format = 'json';
	}

	/**
	 * Adds Content using a box
	 * @param string $content
	 * @param string $title
	 */
	public function box($content, $title = "Content Box", $max_width = NULL) {
		$this->content(new widget_box($content, $title, $max_width));
	}

	/**
	 * Add Content to the Output
	 * @param mixed $data
	 */
	public function content($data) {
		$this->content[] = $data;
	}

	/**
	 * Converts the view to a string
	 * @return string
	 */
	public function __toString() {
		try {
			switch ($this->format) {
				case 'plain':
					if (!empty($this->context['error']))
						$this->content($this->context['error']);
					return implode($this->content);
					break;
				case 'json':
					unset($this->context['js'], $this->context['css']);
					$this->assign('content', $this->content);
					return json_encode($this->context);
					break;
				default:
					if ($this->content)
						$this->assign('content', implode($this->content));
					return template($this->file)->render($this->context);
			}
		} catch (Exception $e) {
			return $e->getTraceAsString();
		}
	}

	/**
	 * Add data with a specific key to the content
	 * Only usful if the output gets json encoded
	 * @param string $key
	 * @param mixed $value
	 */
	public function assign($key, $value) {
		$this->context[$key] = $value;
	}

	/**
	 * Displays the view
	 */
	public function display() {
		echo $this;
	}

	/**
	 * Add a js file
	 * @param string $file
	 */
	public function js($file) {
		$this->context['js'][] = $file;
	}

	/**
	 * Add a css file
	 * @param string $file
	 */
	public function css($file) {
		$this->context['css'][] = $file;
	}

	/**
	 * Set an error message
	 * @param string $msg
	 */
	public function error($msg) {
		$this->context['error'] = $msg;
	}

	/**
	 * Set an error message
	 * @param string $msg
	 */
	public function success($msg) {
		$this->context['success'] = $msg;
	}

	/**
	 * @param $content
	 */
	public function plain($content) {
		$this->format = 'plain';
		$this->content($content);
	}

	/**
	 * @param array $sizes
	 * @return widget_grid
	 */
	public function grid($sizes = array()) {
		$this->content($grid = new widget_grid($sizes));
		return $grid;
	}
}
