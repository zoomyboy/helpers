function toCent(input) {
	input = input.replace(',', '.');
	if (input.indexOf('.') !== input.lastIndexOf('.')) {
		return 0;
	}
	input = input.split('.');
	if (input[1] == undefined || input[1].length == 0) {input[1] = "0";}
	if (input[0].charAt(0) == '-') {
		switch(input[1].length) {
			case 1: input[1] += "0"; break;
			case 2:
				if (input[1].charAt(0) == 0) {
					input[1] = input[1].charAt(1);
				}
		}
		return parseInt(input[0]) * 100 - parseInt(input[1]);
	} else {
		switch(input[1].length) {
			case 1: input[1] += "0"; break;
			case 2:
				if (input[1].charAt(0) == 0) {
					input[1] = input[1].charAt(1);
				}
		}
		return parseInt(input[0]) * 100 + parseInt(input[1]);
	}
}
