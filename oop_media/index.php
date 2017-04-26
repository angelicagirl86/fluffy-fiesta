<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php 
        
            require_once ("subClasses.php");
            
            
            //$channelName, $broadcast, $length, $director, $actor, $producer
            $scrubs = new Tv("tv6", 100, 20, "George White", "Brad Pitt", "Angelica");
           
            
             print_r($scrubs->checkAge()."<br>");
           
            //$distributor, $cinemascreening, $length, $director, $actor, $producer
            $dirtydancing = new Film("SF Bio", 20, 120, "Francis Ford Coppola", "Jenna Jameson", "Dragana");
            
            
            echo "<pre>".print_r($scrubs, TRUE)."</pre>";
            echo "<pre>".print_r($dirtydancing, TRUE)."</pre>";
            
         ?>
    </body>
</html>
 