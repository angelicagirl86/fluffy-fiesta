<?php


trait tr_checkAge {
    public function checkAge(){
        echo "Godkänt för en ålderskategori, från 12 år.";
    }
}


interface i_checkAge {
    public function checkAge();
}
