<?php

class list_column_text extends list_column {
	public function cell( $row ) {
		return htmlspecialchars( $row[$this->data] );
	}
}
