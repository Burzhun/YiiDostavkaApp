<audio id="myaudio">
    <source src="http://dostavka05.ru/images/new_order.mp3" type="audio/mp3">
    <source src="http://dostavka05.ru/images/new_order.ogg" type="audio/ogg">
    <source src="http://dostavka05.ru/images/new_order.wav" type="audio/wav">
    Ваш браузер не поддерживает звуки.
</audio>

<audio id="empty">
    <source src="http://dostavka05.ru/images/empty.mp3" type="audio/mp3">
    <source src="http://dostavka05.ru/images/empty.ogg" type="audio/ogg">
    <source src="http://dostavka05.ru/images/empty.wav" type="audio/wav">
    Ваш браузер не поддерживает звуки.
</audio>

<script type="text/javascript">
    var oAudio1;

    function playAudio() {
        //alert(1)
        oAudio.play();
        //setInterval(playAudio,5000);
    }

    function playInit() {
        oAudio = document.getElementById('myaudio');
        oAudio.play();
        //oAudio.stop();
        setInterval(playAudio, 8000);
        return false;
    }


    /*// Global variable to track current file name.
     var currentFile = "";
     var oAudio;
     var btn;
     var audioURL;

     function playAudio() {
     // Check for audio element support.
     if (window.HTMLAudioElement) {

     try {
     oAudio = document.getElementById('myaudio');
     btn = document.getElementById('play');
     audioURL = document.getElementById('audiofile');

     //Skip loading if current file hasn't changed.
     if (audioURL.value !== currentFile) {
     oAudio.src = audioURL.value;
     currentFile = audioURL.value;
     }

     // Tests the paused attribute and set state.
     if (oAudio.paused) {

     oAudio.play();
     //btn.textContent = "Pause";
     }
     else {
     //oAudio.currentTime = 0;
     oAudio.pause();
     //btn.textContent = "Play";
     }
     oAudio.currentTime = 0;
     }
     catch (e) {
     // Fail silently but show in F12 developer tools console
     if(window.console && console.error("Error:" + e));
     }
     }
     }
     //playAudio();*/
</script>

<script>
    //setInterval(playAudio,10000);
    //playAudio();
    var i = 0;
    function asdf() {
        setInterval(sdfg, 1000);
    }

    function sdfg() {
        i++;
    }

    function getNum() {
        alert(i);
    }

</script>

<button id='btn' value="asdfadsf" style="width:100px;height:50px;" onclick="playInit();"/>

<button id='btn2' value="asdfadsf" style="width:100px;height:50px;" onclick="asdf();"/>
<button id='btn3' value="asdfadsf" style="width:100px;height:50px;" onclick="getNum();"/>