var keyZones = [
	["right", [39]],
	["left", [37]],
	["up", [38]],
	["down", [40]],
	["a", [88, 74]],
	["b", [90, 81, 89]],
	["select", [16]],
	["start", [13]]
];

function cout(message, colorIndex) {
	console.log(message);
}

function keyDown(event) {
	var keyCode = event.keyCode;
	var keyMapLength = keyZones.length;
	for (var keyMapIndex = 0; keyMapIndex < keyMapLength; ++keyMapIndex) {
		var keyCheck = keyZones[keyMapIndex];
		var keysMapped = keyCheck[1];
		var keysTotal = keysMapped.length;
		for (var index = 0; index < keysTotal; ++index) {
			if (keysMapped[index] == keyCode) {
				GameBoyKeyDown(keyCheck[0]);
				try {
					event.preventDefault();
				}
				catch (error) {
				}
			}
		}
	}
}

function keyUp(event) {
	var keyCode = event.keyCode;
	var keyMapLength = keyZones.length;
	for (var keyMapIndex = 0; keyMapIndex < keyMapLength; ++keyMapIndex) {
		var keyCheck = keyZones[keyMapIndex];
		var keysMapped = keyCheck[1];
		var keysTotal = keysMapped.length;
		for (var index = 0; index < keysTotal; ++index) {
			if (keysMapped[index] == keyCode) {
				GameBoyKeyUp(keyCheck[0]);
				try {
					event.preventDefault();
				}
				catch (error) {
				}
			}
		}
	}
}

function loadGame(rom) {
	var mainCanvas = document.getElementById('mainCanvas');
	var oReq = new XMLHttpRequest();

	oReq.responseType = 'blob';
	oReq.open("GET", rom, true);
	oReq.onload = function () {
		var binaryHandle = new FileReader();

		binaryHandle.onload = function () {
			document.onkeyup = keyUp;
			document.onkeydown = keyDown;
			start(mainCanvas, this.result);
		};

		binaryHandle.readAsBinaryString(oReq.response);
	};

	oReq.send();
}