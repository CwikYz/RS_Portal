//
// Poruka razg.
//
function focusInputRazglas(o) {
	o.style.color = "#000000";
	if (o.value == "")
	    o.value = "";
}

function blurInputRazglas(o) {
	o.style.color = "#ababab";
	if (o.value == "")
		o.value = "Reci sta ti je na umu?";
}

//
// Potrazi prij.
//
function focusInputFriendFind(o) {
	o.style.color = "#000000";
	o.value = "";
}

function blurInputFriendFind(o) {
	o.style.color = "#ababab";
	if (o.value == "")
		o.value = "Trazi prijatelja...";
}