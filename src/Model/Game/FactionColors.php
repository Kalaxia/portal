<?php

namespace App\Model\Game;

abstract class FactionColors implements \JsonSerializable
{
     /** @var int **/
     protected $id;
     /** @var string **/
     protected $black;
     /** @var string **/
     protected $grey;
     /** @var string **/
     protected $white;
     /** @var string **/
     protected $main;
     /** @var string **/
     protected $mainLight;
     /** @var string **/
     protected $mainLighter;

     /**
      * @param int $id
      * @return $this
      */
     public function setId($id)
     {
         $this->id = $id;

         return $this;
     }

     /**
      * @return int
      */
     public function getId()
     {
         return $this->id;
     }

     /**
      * @return string
      */
     public function getBlack()
     {
         return $this->black;
     }

     /**
      * @param string
      * @return $this
      */
     public function setBlack($black)
     {
         $this->black = $black;

         return $this;
     }

     /**
      * @return string
      */
     public function getGrey()
     {
         return $this->grey;
     }

     /**
      * @param string
      * @return $this
      */
     public function setGrey($grey)
     {
         $this->grey = $grey;

         return $this;
     }

     /**
      * @return string
      */
     public function getWhite()
     {
         return $this->white;
     }

     /**
      * @param string
      * @return $this
      */
     public function setWhite($white)
     {
         $this->white = $white;

         return $this;
     }

     /**
      * @return string
      */
     public function getMain()
     {
         return $this->main;
     }

     /**
      * @param string
      * @return $this
      */
     public function setMain($main)
     {
         $this->main = $main;

         return $this;
     }

     /**
      * @return string
      */
     public function getMainLight()
     {
         return $this->mainLight;
     }

     /**
      * @param string
      * @return $this
      */
     public function setMainLight($mainLight)
     {
         $this->mainLight = $mainLight;

         return $this;
     }

     /**
      * @return string
      */
     public function getMainLighter()
     {
         return $this->mainLighter;
     }

     /**
      * @param string
      * @return $this
      */
     public function setMainLighter($mainLighter)
     {
         $this->mainLighter = $mainLighter;

         return $this;
     }

     public function jsonSerialize()
     {
         return [
             'black' => $this->black,
             'grey' => $this->grey,
             'white' => $this->white,
             'main' => $this->main,
             'mainLight' => $this->mainLight,
             'mainLighter' => $this->mainLighter,
         ];
     }
}
