<?php

class list_sql extends list_renderer {

	/**
	 * Returns the list as a string
	 * @param array $data
	 * @return string
	 */
	public function get( $sql, mysql_connection $db = null ) {
		if( !isset( $db ))
			if(function_exists ('db')) $db = db();
			else throw new Exception( 'No Database given.');

		$pagination = new data_pagination($this->link, $this->pagesize, $this->prefix);
		$res = $pagination->query($sql);
		$pagination = $pagination->get();

		$content = $pagination.$this.$this->header().'<tbody>';
		foreach( $res as $row ) $content .= $this->row( $row );
		return $content.'</tbody></table>'.$pagination;
	}

	/**
	 * Outputs the list
	 * @param array $data
	 */
	public function display( $sql, mysql_connection $db = null ) {
		echo $this->get( $sql, $db );
	}
}
