function toCent(input) {
	if(input == '' || input == undefined) {return 0;}
	input = input.toString();
	input = input.replace(/,/g, '.');
	if (input.indexOf('-') !== input.lastIndexOf('-') || input.indexOf('.') !== input.lastIndexOf('.') || input.replace('.', '').replace('-', '') == '' || isNaN(parseInt(input.replace('.', '').replace('-', '')))) {
		console.log("abbr");
		return 0;
	}
	input = input.split('.');
	if (input[0] == '-' || (input[1] != undefined && input[1].indexOf('-') !== -1)) {console.log('abbr2'); return 0;}
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

function number_format(number,decimals,dec_point,thousands_sep) {
    number  = number*1;//makes sure `number` is numeric value
    var str = number.toFixed(decimals?decimals:0).toString().split('.');
    var parts = [];
    for ( var i=str[0].length; i>0; i-=3 ) {
        parts.unshift(str[0].substring(Math.max(0,i-3),i));
    }
    str[0] = parts.join(thousands_sep?thousands_sep:',');
    return str.join(dec_point?dec_point:'.');
}
