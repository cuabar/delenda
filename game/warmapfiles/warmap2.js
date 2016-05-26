//Create the Canvas


var canvas = new Kinetic.Stage({
	container: 'container',
	width: 1205,
	height: 605,
	x: 75,
	y: 10
});

var maplayer = new Kinetic.Layer();
var starMap;
var commandRect;
var map = new Image();
map.onload = function(){
	starMap = new Kinetic.Image({
		x: 0,
		y: 0,
		width: 900,
		height: 600,
		image: map
	});
	
	drawAll();
}

map.src = "warmapfiles/starfield3.jpg";

commandRect = new Kinetic.Rect({
	x: 900,
	y: 0,
	width: 300,
	height: 600,
	fill: 'white',
	opacity: 0.25,
	stroke: 'white',
	strikeWidth: 2
});

function drawAll(){
	maplayer.add(starMap);
	maplayer.add(commandRect)
	canvas.add(maplayer);
	maplayer.draw();
}
