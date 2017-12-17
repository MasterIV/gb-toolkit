function SpriteTile(data, editor) {
	var self = this;
	var colors = ['white', 'lightgrey', 'darkgrey', 'black'];
	var size = 32;
	var small = 4;

	this.canvas = document.createElement('canvas');
	this.canvas.width = this.canvas.height = size * 8;

	this.canvas.onmousemove = paint;
	this.canvas.onmouseup = paint;

	this.thumb = document.createElement('canvas');
	this.thumb.width = this.thumb.height = small * 8;

	var ctx = this.canvas.getContext('2d');
	var ttx = this.thumb.getContext('2d');

	function paint(evt) {
		if(!editor.draw) return;
		var x = Math.min(evt.layerX / size, 7) | 0;
		var y = Math.min(evt.layerY / size, 7) | 0;
		data[y][x] = editor.brushColor;
		draw();
	}

	function draw() {
		for(var y = 0; y < 8; y++)
			for(var x = 0; x < 8; x++) {
				ctx.fillStyle = colors[data[y][x]];
				ctx.fillRect(x*size, y*size, size, size);
			}

			ttx.drawImage(self.canvas, 0, 0, size * 8, size * 8, 0, 0, small * 8, small * 8);
	}

	draw();
}