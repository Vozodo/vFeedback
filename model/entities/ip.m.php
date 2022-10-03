<?php

require_once 'entity.php';

class Ip {

    use Entity;

    private int $id = 0;
    private string $ipaddress = "";
    private string $date = "Y.m.d";


    public function loeschen(){

        $sql = 'DELETE FROM ip WHERE id=?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute( array($this->getId()) );
        $this->id = 0;
        
    }

    private function _insert() {

        $sql = 'INSERT INTO ip (ipaddress, date)' 
                . 'VALUES (:ipaddress, :date)';

        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute($this->toArray(false));
        $this->setId = DB::getDB();
    }

    private function _update(){

        $sql ='UPDATE ip SET ipaddress=:ipaddress, date=:date WHERE id=:id';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute($this->toArray());
    }


    public function __toString(){

        return  "IP: " . $this->getIpaddress() . " Date: " . $this->getDate();
   
    }

    public static function find($id){
        $sql = 'SELECT * FROM ip WHERE id = ?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute(array($id));
        $abfrage->setFetchMode(PDO::FETCH_CLASS, 'Ip');
        return $abfrage->fetch();
    }

    public static function findAll(){
        $sql = 'SELECT * FROM ip';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute();
        $abfrage->setFetchMode(PDO::FETCH_CLASS, 'Ip');
        return $abfrage->fetchAll();
    }

    public static function allreadyFilled($ip) {

        $sql = 'SELECT COUNT(*) as count FROM ip WHERE ipaddress = ?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute(array($ip));

        if ((int) ($abfrage->fetch()['count']) >= 1) {
            return true;
        } else {
            return false;
        }
        
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of ipaddress
     */ 
    public function getIpaddress()
    {
        return $this->ipaddress;
    }

    /**
     * Set the value of ipaddress
     *
     * @return  self
     */ 
    public function setIpaddress($ipaddress)
    {
        $this->ipaddress = $ipaddress;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}


?>
