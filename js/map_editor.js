function MapEditor(w, h, tiles, raw) {
	var data = [];
	var encoder = new SpriteEncoder();
	var size = 32;
	var self = this;

	var draw = false;
	var brush = {tile: 0, w: 1, h: 1};

	var total = 0;
	var palette = [];

	var canvasSpace = $('#canvas');
	var thumbsSpace = $('#thumbs');

	var canvas = document.createElement('canvas');
	var ctx = canvas.getContext('2d');
	canvas.onmousemove = paint;
	canvas.onmouseup = paint;
	canvasSpace.append(canvas);

	document.onmousedown = function () {
		draw = true;
	};
	document.onmouseup = function () {
		draw = false;
	};

	function addTile(d) {
		var sprite = new SpriteTile(d, self);
		var index = total++;

		sprite.thumb.onclick = function () {
			select(index);
		};

		thumbsSpace.append(sprite.thumb);
		return sprite;
	}

	function paint(evt) {
		if (!draw) return;
		var x = Math.min(evt.layerX / size, w - 1) | 0;
		var y = Math.min(evt.layerY / size, h - 1) | 0;

		for (var px = 0; px < brush.w; px++)
			for (var py = 0; py < brush.h; py++)
				if (x + px < w && y + py < h) {
					var t = brush.tile + brush.w * py + px
					if (palette[t]) data[y + py][x + px] = t;
				}

		render();
	}

	function select(t) {
		brush.tile = t;
		$('#thumbs canvas').removeClass('active');

		var display = brush.w * brush.h;
		for (var i = 0; i < display; i++)
			$(palette[t + i].thumb).addClass('active');
	}

	function render() {
		for (var y = 0; y < h; y++)
			for (var x = 0; x < w; x++)
				if (palette[data[y][x]])
					ctx.drawImage(palette[data[y][x]].thumb, x * size, y * size);
	}

	this.save = function (url) {
		var bgmap = [];

		for (var y = 0; y < h; y++)
			for (var x = 0; x < w; x++)
				bgmap.push(formatImageData(data[y][x]));

		$.post(url, {
			data: bgmap,
			w: w,
			h: h,
			tiles: tiles
		});
	};

	this.brush = function (width, height) {
		var c = width + '-' + height;
		$('.dimension').each(function (k, v) {
			var e = $(v);
			if (e.hasClass(c)) e.addClass('active');
			else e.removeClass('active');
		});

		brush.w = width;
		brush.h = height;
		select(brush.tile);
	};

	this.resize = function (width, height) {
		w = Math.max(1, Math.min(width, 32));
		h = Math.max(1, Math.min(height, 32));
		canvas.width = w * size;
		canvas.height = size * h;

		for (var y = 0; y < h; y++) {
			if (!data[y]) data[y] = [];
			for (var x = 0; x < w; x++)
				if (!data[y][x]) data[y][x] = raw[y*w+x] | 0;
		}

		render();
	};

	this.setTiles = function (file) {
		tiles = file;
		$.get('?modul=background&tiles&id=' + file, function (data) {
			var raw = encoder.decode(eval(data));

			total = 0;
			thumbsSpace.empty();
			palette = raw.map(addTile);

			brush.tile = 0;
			self.brush(1, 1);
			render();
		});
	};

	this.resize(w, h);
	this.setTiles(tiles);
}