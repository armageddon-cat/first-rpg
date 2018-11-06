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
    <div style="display: none!important;">
        <?php // preloading images
        //// this part is needed for preloading images instead of onload event which slows down image rendering and
        //                // makes images to blink all the time. ?>
        <img src="src/img/hallview_end_left.png">
        <img src="src/img/hallview_end_right.png">
        <img src="src/img/hallview_front_wall_left.png">
        <img src="src/img/hallview_front_wall_right.png">
        <img src="src/img/hallview_full_left.png">
        <img src="src/img/hallview_full_right.png">
        <img src="src/img/hallview_half_left.png">
        <img src="src/img/hallview_half_right.png">
        <img src="src/img/mob3dFull.png">
        <img src="src/img/mob3dMedium.png">
        <img src="src/img/mob3dSmall.png">
        <img src="src/img/mob_jaws.jpg">
        <img src="src/img/mob_jaws_half.jpg">
        <img src="src/img/mob_jaws_last.jpg">
        <img src="src/img/player.jpg">
        <img src="src/img/wall.jpg">
    </div>
    <script>
        var wall = new Image;
        var player = new Image;
        var mobJaws = new Image;

        previousViewGameImage = '';


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

            var viewGameImage = new Image();
            // before loading
            // //if (mapData need animation do animation) { // todo add logic on server to rule animation
            // console.log('previousViewGameImage'); // TODO remove debug!!
            // console.log(previousViewGameImage); // TODO remove debug!!
            // canvasContext3D.save();
            // if (previousViewGameImage !== '') {
            //     console.log("!=="); // TODO remove debug!!
            //     var scale = 1;
            //
            //     var timerId = setInterval(animate, 50);
            //     function animate() {
            //         console.log("animate"+scale); // TODO remove debug!!
            //         if (scale === 1.05) {
            //             clearInterval(timerId);
            //         }
            //         canvasContext3D.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
            //         canvasContext3D.translate(CANVAS_SIZE/2, CANVAS_SIZE/2);
            //         canvasContext3D.scale(scale, scale);
            //         canvasContext3D.translate(-CANVAS_SIZE/2, -CANVAS_SIZE/2);
            //
            //         // before loading next image, animate current
            //         console.log('animate.previousViewGameImage.src');
            //         console.log(previousViewGameImage.src);
            //         canvasContext3D.drawImage(previousViewGameImage, 0, 0);
            //         scale = scale + 0.01;
            //     }
            //
            //     setTimeout(drawView,5000);
            //     function drawView() {
            //         clearInterval(timerId);
            //         canvasContext3D.restore();
            //         canvasContext3D.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
            //         canvasContext3D.scale(1, 1);
            //         viewGameImage.src = mapData.gameView.src;
            //         console.log('drawView');
            //         console.log(viewGameImage.src);
            //         canvasContext3D.scale(1, 1);
            //         canvasContext3D.drawImage(viewGameImage, mapData.gameView.x, mapData.gameView.y);
            //         canvasContext3D.scale(1, 1);
            //         previousViewGameImage = viewGameImage;
            //     }
            // } else {
            //     canvasContext3D.restore();
            //     console.log("eslse"); // TODO remove debug!!
            //     viewGameImage.src = mapData.gameView.src;
            //     console.log('eslsedrawView');
            //     console.log(viewGameImage.src);
            //     canvasContext3D.drawImage(viewGameImage, mapData.gameView.x, mapData.gameView.y);
            //     previousViewGameImage = viewGameImage;
            // }
            //}
            viewGameImage.src = mapData.gameView.src;
            console.log('eslsedrawView');
            console.log(viewGameImage.src);
            canvasContext3D.drawImage(viewGameImage, mapData.gameView.x, mapData.gameView.y);

            if (typeof mapData.mobs !== typeof undefined) {
                var mobs = mapData.mobs;
                mobs.forEach(function(itemMobs) {
                    mobJaws.src = itemMobs.src;
                    canvasContext.drawImage(mobJaws, itemMobs.x, itemMobs.y);
                });

                if (typeof mapData.mobsView !== typeof undefined) {
                    var mobsView = mapData.mobsView;
                    var viewMobsImg = new Image;
                    mobsView.forEach(function(itemMobsView) {
                        viewMobsImg.src = itemMobsView.src;
                        canvasContext3D.drawImage(viewMobsImg, itemMobsView.x, itemMobsView.y);
                    });
                }
            }

            canvasContext.drawImage(player, mapData.player.x, mapData.player.y);
        };
    </script>
</body>
</html>