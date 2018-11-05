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
    <canvas id="canvas_view" width="800" height="800" style="border: 1px solid"></canvas>
    <canvas id="canvas" width="800" height="800" style="border: 1px solid;width: 200px;height: 200px"></canvas>
    <script>
        var wall = new Image;
        var player = new Image;
        var mobJaws = new Image;


        wall.src = 'src/img/wall.jpg';
        player.src = 'src/img/player.jpg';


        CANVAS_SIZE = 800;
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

        var canvas3D = document.getElementById('canvas_view');
        var canvasContext3D = canvas3D.getContext('2d');
        canvasContext3D.save();
        // setInterval(function () {
        //     if (buffer != '') {
        //         socket.send(buffer);
        //     }
        //     buffer = '';
        // }, 200);

        window.addEventListener('keydown', function (e) {
            if (CODE_ARROWS.indexOf(e.keyCode) === -1) { // not arrow
                return;
            }
            buffer = JSON.stringify({'direction':e.keyCode});
            socket.send(buffer)
        });

        socket.onmessage = function (event) {
            var mapData = JSON.parse(event.data);
            console.log(mapData); // TODO remove debug!!
            canvasContext.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
            canvasContext3D.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
            var walls = mapData.walls;
            walls.forEach(function(item) {
                canvasContext.drawImage(wall, item.x, item.y);
            });
            if (typeof mapData.mobs !== typeof undefined) {
                // this part is needed for preloading images instead of onload event which slows down image rendering and
                // makes images to blink all the time.
                var mobJaws1 = new Image; // preloading
                var mobJaws2 = new Image; // preloading
                var mobJaws3 = new Image; // preloading
                mobJaws1.src = 'src/img/mob_jaws.jpg'; // preloading
                mobJaws2.src = 'src/img/mob_jaws_half.jpg'; // preloading
                mobJaws3.src = 'src/img/mob_jaws_last.jpg'; // preloading
                var mobs = mapData.mobs;

                mobs.forEach(function(itemMobs) {
                    mobJaws.src = itemMobs.src;
                    canvasContext.drawImage(mobJaws, itemMobs.x, itemMobs.y);
                });
            }

            canvasContext.drawImage(player, mapData.player.x, mapData.player.y);

            var viewPreloading1 = new Image;// preloading
            var viewPreloading2 = new Image;// preloading
            var viewPreloading3 = new Image;// preloading

            var viewPreloading4 = new Image;// preloading
            var viewPreloading5 = new Image;// preloading
            var viewPreloading6 = new Image;// preloading

            viewPreloading1.src = 'src/img/hallview_full_left.png'; // preloading
            viewPreloading2.src = 'src/img/hallview_half_left.png'; // preloading
            viewPreloading3.src= 'src/img/hallview_end_left.png'; // preloading

            viewPreloading4.src= 'src/img/hallview_full_right.png'; // preloading
            viewPreloading5.src= 'src/img/hallview_half_right.png'; // preloading
            viewPreloading6.src= 'src/img/hallview_end_right.png'; // preloading

            // if(mapData.gameView.src === 'src/img/hallview_full.png') { // todo change this shit
            //     viewPreloading1.onload = function() {
            //         canvasContext3D.drawImage(viewPreloading1, mapData.gameView.x, mapData.gameView.y);
            //     };
            //     canvasContext3D.drawImage(viewPreloading1, mapData.gameView.x, mapData.gameView.y);
            // }
            // if(mapData.gameView.src === 'src/img/hallview_half.png') {
            //     viewPreloading2.onload = function() {
            //         canvasContext3D.drawImage(viewPreloading2, mapData.gameView.x, mapData.gameView.y);
            //     };
            //     canvasContext3D.drawImage(viewPreloading2, mapData.gameView.x, mapData.gameView.y);
            // }
            // if(mapData.gameView.src === 'src/img/hallview_end.png') {
            //     viewPreloading3.onload = function() {
            //         canvasContext3D.drawImage(viewPreloading3, mapData.gameView.x, mapData.gameView.y);
            //     };
            //     canvasContext3D.drawImage(viewPreloading3, mapData.gameView.x, mapData.gameView.y);
            // }

            var viewG = new Image;
            console.log(mapData); // TODO remove debug!!
            viewG.src = mapData.gameView.src;
            viewG.onload = function() {
                canvasContext3D.drawImage(viewG, mapData.gameView.x, mapData.gameView.y);
            };
            canvasContext3D.drawImage(viewG, mapData.gameView.x, mapData.gameView.y);


        };
    </script>
</body>
</html>