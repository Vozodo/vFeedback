<?php

require_once 'entity.php';

class Admin{
    
    use Entity;

    private int $id = 0;
    private string $uname = "";
    private string $password = "";


    public function loeschen(){

        $sql = 'DELETE FROM admins WHERE id=?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute( array($this->getId()) );
        $this->id = 0;
        
    }

    private function _insert() {

        $sql = 'INSERT INTO admins (uname, password)' 
                . 'VALUES (:uname, :password)';

        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute($this->toArray(false));
        $this->setId = DB::getDB();
    }

    private function _update(){

        $sql ='UPDATE admins SET uname=:uname, password=:password WHERE id=:id';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute($this->toArray());
    }


    public function __toString(){

        return  "Username: " . $this->getUname() . " Password: " . $this->getPassword();
   
    }

    public static function find($id){
        $sql = 'SELECT * FROM admins WHERE id = ?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute(array($id));
        $abfrage->setFetchMode(PDO::FETCH_CLASS, 'Admin');
        return $abfrage->fetch();
    }

    public static function findAll(){
        $sql = 'SELECT * FROM admins';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute();
        $abfrage->setFetchMode(PDO::FETCH_CLASS, 'Admin');
        return $abfrage->fetchAll();
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
     * Get the value of uname
     */ 
    public function getUname()
    {
        return $this->uname;
    }

    /**
     * Set the value of uname
     *
     * @return  self
     */ 
    public function setUname($uname)
    {
        $this->uname = $uname;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}

?>