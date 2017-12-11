function MigrationManager( url, pending, progressBar, infoBox ) {
	var migrating = false;
	var count = pending.length;
	var callback = null;

	function info( msg ) {
		infoBox.append( msg+'<br>' );
	}

	function error( msg ) {
		info( '<div class="alert alert-error">'+msg+'</div>' );
	}

	function installNext( data ) {
		if( data ) {
			error( data );
			return;
		} else {
			var width = Math.round(( count - pending.length ) / count * 100 );
			progressBar.css('width', width+'%');
		}

		if( pending.length ) {
			var file = pending.shift();
			info( 'Installing '+file+'...');
			$.get( url+'&install='+file, function( data ) { installNext(data); });
		} else {
			info('Installation complete.');
			$('#migbtn').replaceWith('<a href="'+url+'&complete" class="btn btn-info">Fertigstellen</a>');
			migrating = false;
			if( callback ) callback();
		}
	}

	this.installAll = function( cb ) {
		if( migrating ) return;
		callback = cb;
		migrating = true;
		installNext();
	}
}
