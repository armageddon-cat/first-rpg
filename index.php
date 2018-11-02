<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game</title>
</head>
<body>
    <canvas id="canvas" width="800" height="800" style="border: 1px solid;"></canvas>
    <script>
        SIZE_CANVAS = 800;
        CANVAS_START = 0;
        CODE_LEFT_ARROW = 37;
        CODE_UP_ARROW = 38;
        CODE_RIGHT_ARROW = 39;
        CODE_DOWN_ARROW = 40;
        CODE_ENTER = 13;
        CODE_ARROWS = [CODE_LEFT_ARROW, CODE_UP_ARROW, CODE_RIGHT_ARROW, CODE_DOWN_ARROW];
        RAD_TO_DEG = Math.PI/180;
        ROTATE_COUNTERCLOCKWISE = RAD_TO_DEG*270;
        ROTATE_CLOCKWISE = RAD_TO_DEG*90;
        ROTATE_OPPOSITE = RAD_TO_DEG*180;
        buffer = '';
        
        var socket = new WebSocket("ws://127.0.0.1:8000");
//        var socket = new WebSocket("ws://185.154.13.92:8124");
        socket.onclose = function(event) {
            if (event.wasClean) {
                console.log('Connection closed clean');
            } else {
                console.log('Connection lost'); // example: server process was killed
            }
            console.log('Code: ' + event.code + ' reason: ' + event.reason);
        };
        socket.onopen = function () {
            console.log("socket opened");
        };
        
        var canvas = document.getElementById('canvas');
        var canvasContext = canvas.getContext('2d');
        canvasContext.save();
        setInterval(function () {
            if (buffer != '') {
                socket.send(buffer);
            }
            buffer = '';
        }, 200);

        window.addEventListener('keydown', function (e) {});

        socket.onmessage = function (event) {};
    </script>
</body>
</html>