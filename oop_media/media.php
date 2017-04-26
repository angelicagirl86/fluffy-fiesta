<?php

//skapa abstract class
abstract class media  {
    
    //protected scope för det ska ärvas från barnen bara
    protected $length;
    protected $director;
    protected $actor;
    protected $producer;
    
    /*
     * En konstuktor använder för att objeket vi instanserar av en klass ska
     * ha en viss struktur och data. Den kan ta emot paramterar
     * Först lägger vi till paratmetrarna specifikt för denna barnklass $time och
     * $service, men sidan måste ordningsföjden för paratmetrarna från vår 
     * föräldrarklass som vi ärver till den klass vara detsamma.
     * OBS!!! "Signaturen" eller ordningsföjden utav argeument måste vara exakt
     * detsamma som i föräldrarklassen, i dett fall den abstrakta klassen
     * BankAccount     
     */
    public function __construct($length, $director, $actor, $producer) {
        
        $this->length = $length;
        $this->director = $director;
        $this->actor = $actor;
        $this->producer = $producer;
        }
}