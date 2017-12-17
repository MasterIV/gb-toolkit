function SpriteEditor(data) {
	var encoder = new SpriteEncoder();
	var self  = this;

	var total = 0;
	var dimension = JSON.parse(localStorage.dimension) || [1, 1];
	var selected = 0;

	var canvasSpace = $('#canvas');
	var thumbsSpace = $('#thumbs');

	var raw = encoder.decode(data);
	var tiles = raw.map(add);

	function empty() {
		var d = [];

		for (var y = 0; y < 8; y++) {
			d[y] = [];
			for (var x = 0; x < 8; x++)
				d[y][x] = 0;
		}

		return d;
	}

	function add(d) {
		var sprite = new SpriteTile(d, self);
		var index = total++;

		sprite.thumb.onclick = function() {
			select(index);
		};

		thumbsSpace.append(sprite.thumb);
		return sprite;
	}

	function select(t) {
		var display = dimension[0] * dimension[1];
		selected = t;

		$('#thumbs canvas').removeClass('active');
		canvasSpace.empty();

		for(var i = 0; i<display; i++) {
			if(!tiles[t+i]) {
				var n = empty();
				raw.push(n);
				tiles.push(add(n));
			}

			$(tiles[t+i].thumb).addClass('active');
			canvasSpace.append(tiles[t+i].canvas);
			if((i+1) % dimension[0] == 0)
				canvasSpace.append('<br>');
		}
	}

	this.color = function (c) {
		this.brushColor = c;
		$('.colorselect').each(function (k, v) {
			if (k == c) $(v).addClass('active');
			else $(v).removeClass('active');
		});
	};

	this.dimension = function (w, h) {
		var c = w + '-' + h;
		$('.dimension').each(function (k, v) {
			var e = $(v);
			if (e.hasClass(c)) e.addClass('active');
			else e.removeClass('active');
		});

		localStorage.dimension = JSON.stringify(dimension = [w, h]);
		select(selected);
	};

	this.save = function (url) {
		$.post(url, {data: encoder.encode(raw)});
	};

	this.add = function () {
		select(total);
	};

	this.color(0);
	this.dimension(dimension[0], dimension[1]);
}