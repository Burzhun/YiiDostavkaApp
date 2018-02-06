<?php
$text=file(getcwd()."/protected/runtime/application.log");
//$text=scandir(getcwd()."/protected");
?>
<div style="margin-left: 15px; font-size: 15px; margin-top: 10px;">
    <?
    foreach($text as $line){
        echo html_entity_decode($line)."<br>";
    }
    ?>
</div>
