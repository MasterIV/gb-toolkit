function formatImageData(n) {
	n = n.toString(16);
	return n.length < 2 ? '0x0' + n : '0x' + n;
}


function SpriteEncoder() {
	function decodeTile(data) {
		var result = [];

		for(var y = 0; y < data.length / 2; y++) {
			var row = [];
			var one = data[y*2];
			var two = data[y*2+1];

			for (var i = 7; i >= 0; i--) {
				row[i] = (one & 1) | ((two & 1) << 1);
				one = one >> 1;
				two = two >> 1;
			}

			result.push(row);
		}

		return result;
	}

	function encodeTile(data, result) {
		for (var y = 0; y < data.length; y++) {
			var one = 0;
			var two = 0;

			for (var x = 0; x < data[y].length; x++) {
				one = one << 1;
				two = two << 1;

				if (data[y][x] & 1) one++;
				if (data[y][x] & 2) two++;
			}

			result.push(formatImageData(one));
			result.push(formatImageData(two));
		}
	}

	this.decode = function (data) {
		var result = [];

		while(data.length)
			result.push(decodeTile(data.splice(0, 16)));

		return result;
	};

	this.encode = function (tiles) {
		var result = [];

		for(var i =0; i < tiles.length; i++)
			encodeTile(tiles[i], result);

		return result;
	};
}