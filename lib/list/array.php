<?php

class list_array extends list_renderer {
	/**
	 * Returns the list as a string
	 * @param array $data
	 * @return string
	 */
	public function get( $data ) {
		$this->total = count( $data );

		$pagination = data_pagination::pagination($this->link, $this->total, $this->page, $this->pagesize, $this->prefix );
		$content = $pagination.$this.$this->header().'<tbody>';

		$from = $this->page*$this->pagesize;
		$to = min( $from+$this->pagesize, $this->total );

		for( $i = $from; $i < $to; $i++ ) $content .= $this->row( $data[$i] );

		return $content.'</tbody></table>'.$pagination;
	}

	/**
	 * Outputs the list
	 * @param array $data
	 */
	public function display( $data ) {
		echo $this->get( $data );
	}
}
