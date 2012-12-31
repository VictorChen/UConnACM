<?php
    require_once('categorySystem.php');

    if ($handle = opendir($categoriesLocation.$_POST['category'])) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                echo "<li>".$entry."</li>";
            }
        }
        closedir($handle);
    }
?>