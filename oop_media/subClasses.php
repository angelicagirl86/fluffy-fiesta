<?php


require_once ("media.php");
require_once ("interface.php");


class Tv extends media implements i_checkAge {
    
    use tr_checkAge;
    
    public $channelName;
    public $broadcast;
    
    public function __construct($channelName, $broadcast, $length, $director, $actor, $producer) {
        
        $this->channelName = $channelName;
        $this->broadcast = $broadcast;
       
        parent::__construct($length, $director, $actor, $producer);
    }
}
                                 /* interface */
class Film extends media implements i_checkAge {
    /* trait */
    use tr_checkAge;
    
    public $distributor;
    public $cinemascreening;
    
    public function __construct($distributor, $cinemascreening, $length, $director, $actor, $producer) {
        
        $this->distributor = $distributor;
        $this->cinemascreening = $cinemascreening;
       
        // :: är när klasser kommunicerar med varandra
        parent::__construct($length, $director, $actor, $producer);
     
} 
}
