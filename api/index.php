<?php
$api = "https://makima-api.vercel.app";

$id=$_GET["id"];

$episodeLink = file_get_contents("$api/download/$id");
$episodeLink = json_decode($episodeLink, true);

$episodeLink = $episodeLink['sources_bk'][0]['file'];

$json = file_get_contents("$api/playerDetails/$id"); 
$json = json_decode($json, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://player.anikatsu.me/style.css">
    <meta name="robots" content="noindex, nofollow" />
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
</head>
<body>
    <style>
    .wrap #player {
        position: absolute;
        height: 100% !important;
        weight: 100 !important;
    }

    .wrap .btn {
        position: absolute;
        top: 15%;
        left: 90%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        background-color: white;
        color: black;
        font-size: 12px;
        padding: 6px 12px;
        border: 1px solid white;
        cursor: pointer;
        border-radius: 5px;
    }

    .jw-flag-touch .jw-slider-time::before, .jw-flag-touch .jw-horizontal-volume-container::before{
        height:0px;
    }

    @media screen and (max-width:600px) {
        .wrap .btn {
            font-size: 08px;
        }
    }
    </style>
    <div class="wrap">

        <div id="player"></div>
        <div id="skipIntro"></div>

    </div>
    <script src='https://cors.zimjs.com/https://raw.githubusercontent.com/Aijazmakerb/cors-anywhere/master/player.js?v=0.0002'></script>
    <script>
        const playerInstance = jwplayer("player").setup({
        controls: true,
        displaytitle: true,
        displaydescription: true,
        fullscreen: false,
        allowFullscreen: false,
        abouttext: "AijaZ",
        aboutlink: "http://makimaa.infinityfreeapp.com/",
        autostart: false,
        skin: {
            name: "netflix"
        },
        

        logo: {
            file: "",
            link: ""
        },
        
        playlist: [{
            title: "<?=$json['title']?>",
            description: "You're Watching:",
            image: "<?=$json['image']?>",
            sources: [{"file": "https://cors.zimjs.com/<?=$episodeLink?>"}],
            autostart: false,
        }],
        advertising: {
            client: "vast",
            schedule: [{
                offset: "pre",
                tag: ""
            }]
        }
    })

    playerInstance.on("ready", function() {

        // Move the timeslider in-line with other controls
        const playerContainer = playerInstance.getContainer();
        const buttonContainer = playerContainer.querySelector(".jw-button-container");
        const spacer = buttonContainer.querySelector(".jw-spacer");
        const timeSlider = playerContainer.querySelector(".jw-slider-time");
        buttonContainer.replaceChild(timeSlider, spacer);

        const player = playerInstance;

        // display icon
        const rewindContainer = playerContainer.querySelector('.jw-display-icon-rewind');
        const forwardContainer = rewindContainer.cloneNode(true);
        const forwardDisplayButton = forwardContainer.querySelector('.jw-icon-rewind');
        forwardDisplayButton.style.transform = "scaleX(-1)";
        forwardDisplayButton.ariaLabel = "Forward 10 Seconds"
        const nextContainer = playerContainer.querySelector('.jw-display-icon-next');
        nextContainer.parentNode.insertBefore(forwardContainer, nextContainer);


        // control bar icon
        playerContainer.querySelector('.jw-display-icon-next').style.display = 'none'; // hide next button
        const rewindControlBarButton = buttonContainer.querySelector(".jw-icon-rewind");
        rewindControlBarButton.ariaLabel = "Backward 10 Seconds";
        const forwardControlBarButton = rewindControlBarButton.cloneNode(true);
        forwardControlBarButton.style.transform = "scaleX(-1)";
        forwardControlBarButton.ariaLabel = "Forward 10 Seconds";
        rewindControlBarButton.parentNode.insertBefore(forwardControlBarButton, rewindControlBarButton
            .nextElementSibling);

        // add onclick handlers
        [forwardDisplayButton, forwardControlBarButton].forEach(button => {
            button.onclick = () => {
                player.seek((player.getPosition() + 10));
            }
        })

        // New Features
        const fullScreenButton = document.getElementsByClassName("jw-icon jw-icon-inline jw-button-color jw-reset jw-icon-fullscreen");
        fullScreenButton[1].addEventListener("click", function(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                // true for mobile device
                screen.orientation.lock("landscape");
            }
        });
        // Find the JW Player element on your page
        var playerElement = document.getElementById('player');

        // Double-tap gesture for skipping backward or forward
        var lastTapTime = 0;
        playerElement.addEventListener("click", function(event) {
            var currentTime = new Date().getTime();
            var tapTimeDiff = currentTime - lastTapTime;
            if (tapTimeDiff <= 300) {
                // Double-tap detected
                var player = jwplayer("player");
                if (event.clientX < window.innerWidth / 2) {
                    // Tap on the left side (skip backward)
                    player.seek(player.getPosition() - 10);
                } else {
                    // Tap on the right side (skip forward)
                    player.seek(player.getPosition() + 10);
                }
            }
            lastTapTime = currentTime;
        });
    });
     </script>
</body>

</html>
