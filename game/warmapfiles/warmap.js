//create the canvas
var body = document.getElementById("body");

var canvas = document.createElement("canvas");
var context = canvas.getContext("2d");

canvas.width = 1200;
canvas.height = 608;

canvas.id = "warmap";
canvas.style.left = "75px";
canvas.style.top = "10px";
canvas.style.position = "absolute";

body.appendChild(canvas);

//background image
var bgReady = false;
var bgImage = new Image();

//Sector Information
var sectorname = "Unknown";
var sectorownership = "Contested";
var sectorfriendly = "5";
var sectortroops = "50,000";

bgImage.onload = function(){
	bgReady = true;
	render();
};
bgImage.src = "warmapfiles/starfield3.jpg";

//draw images
function render(){

	if(bgReady)
	{
		//draw bg and map
		context.fillStyle = "rgba(192,192,192, 1)";
		context.fillRect(0, 0, 1200, 608);
		context.drawImage(bgImage, 0, 0, 952, 608);
		canvas.backgroundColor = "white";
		//Prepare text
		context.fillStyle = "rgb(0, 0, 0)";
		context.font = "12px Helvetica";
		context.textAlign = "left";
		context.textBaseline = "top";
		
		context.fillText("Sector: " + sectorname, 960, 10);
		context.fillText("Ownership: " + sectorownership, 960, 30);
		context.fillText("Controlled Systems: " + sectorfriendly, 960, 50);
		context.fillText("Deployed Troops: " + sectortroops, 960, 70);
	}
};