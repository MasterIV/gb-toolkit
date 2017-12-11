<?php

class list_column_callback extends list_column {
	public function cell( $row ) {
		return call_user_func( $this->data, $row );
	}
}
