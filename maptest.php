<!DOCTYPE html>
<html>
  
  <head>
    <script type="text/javascript">
      // Global variables
      var canvas;
      var ctx;

      // This function is called when the page loads.


      function init() {

        // Initialize canvas and get context.
        canvas = document.getElementById("canvas");
        ctx = canvas.getContext("2d");

        // Draw on the context.
        draw(ctx);
      }

      function draw(ctx) {

        // Draw on the canvas.
        // Get the drawing coordinates.
        makeLake();

        // Then fill and stroke the shape.
        // The fill is white.
        ctx.fillStyle = "white";
        ctx.fill();

        // The stroke is blue.
        ctx.strokeStyle = "blue";
        ctx.stroke();
      }

      function paintLake() {

        // If the shape is clicked, display a fact
        // and paint the lake.
        // Display a fact.
        alert("Lake Michigan is one of the five Great Lakes of America.");

        // Fill the lake with blue.
        ctx.fillStyle = "blue";
        ctx.fill();
      }

      function makeLake() {

        // These methods draw the shape of the lake.
        ctx.beginPath();
        ctx.moveTo(263.3, 11.3);
        ctx.lineTo(252, 28);
        ctx.lineTo(217.3, 28);
        ctx.lineTo(184, 74);
        ctx.lineTo(154, 123.3);
        ctx.lineTo(142, 170);
        ctx.lineTo(127.3, 214);
        ctx.lineTo(112.7, 252);
        ctx.lineTo(124, 284.7);
        ctx.lineTo(120, 318.7);
        ctx.lineTo(124, 356);
        ctx.lineTo(154, 389.3);
        ctx.lineTo(196.5, 356.7);
        ctx.lineTo(218.7, 318);
        ctx.lineTo(206, 240);
        ctx.lineTo(212.7, 182);
        ctx.lineTo(230.7, 121.3);
        ctx.lineTo(253.3, 103.3);
        ctx.lineTo(288, 74);
        ctx.lineTo(288, 23.3);
        ctx.closePath();
      }
    </script>
  </head>
  
  <body onload="init()">
    <h1>
      Lake Michigan
    </h1>
    <canvas id="canvas" width="400" height="400" onclick="paintLake()">
    </canvas>
    <p>
      Click on the lake to learn a fact about Lake Michigan.
    </p>
  </body>

</html>