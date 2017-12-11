<?php

class list_column_static extends list_column {
	public function cell( $row ) {
		return $this->data;
	}
}
