<?php

     trait Entity{

        public function __construct(array $array =array()){

            if($array){
                foreach ($array as $key => $value) {
                    $setter = 'set' .ucfirst($key);
                    if(method_exists($this,$setter)){
                        $this->$setter($value);
                    }
                }
            }
        }


        public function toArray($mitId = true){

            $attribute = get_object_vars($this);
            if($mitId === false){
                unset($attribute['id']);
            }
            return $attribute;
        }
        

        public function speichere(){
        
            if($this->getId() >0){
                $this->_update();
            }else{
                $this->_insert();
            }
        }


        
    }




?>