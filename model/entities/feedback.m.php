<?php

require_once 'entity.php';

class Feedback{
    
    use Entity;

    private int $id = 0;
    private string $title = "";
    private string $feedback = "";
    private string $name = "";
    private string $date = "";
    private string $country = "";


    public function loeschen(){

        $sql = 'DELETE FROM feedbacks WHERE id=?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute( array($this->getId()) );
        $this->id = 0;
        
    }

    private function _insert() {

        $sql = 'INSERT INTO feedbacks (title, feedback, name, country, date)' 
                . 'VALUES (:title, :feedback, :name, :country, :date)';

        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute($this->toArray(false));
        $this->setId = DB::getDB();
    }

    private function _update(){

        $sql ='UPDATE feedbacks SET title=:title, feedback=:feedback, name=:name, country=:country, date=:date WHERE id=:id';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute($this->toArray());
    }


    public function __toString(){

        return  "<b>Title:</b> " . $this->getTitle() . "<br/><b>Feedback:</b> " . $this->getFeedback() . "<br/><b>Name:</b> " . $this->getFeedback() . "<br /><b>Country:</b> " . $this->getCountry() . "<br /><b>Date:</b> " . $this->getDate() . "<br />";
   
    }

    public static function find($id){
        $sql = 'SELECT * FROM feedbacks WHERE id = ?';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute(array($id));
        $abfrage->setFetchMode(PDO::FETCH_CLASS, 'Feedback');
        return $abfrage->fetch();
    }

    public static function findAll(){
        $sql = 'SELECT * FROM feedbacks';
        $abfrage = DB::getDB()->prepare($sql);
        $abfrage->execute();
        $abfrage->setFetchMode(PDO::FETCH_CLASS, 'Feedback');
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
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of feedback
     */ 
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Set the value of feedback
     *
     * @return  self
     */ 
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

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
    }

    /**
     * Get the value of country
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */ 
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}

?>