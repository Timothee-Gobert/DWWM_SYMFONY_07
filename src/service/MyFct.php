<?php

namespace App\service;

class MyFct{

      public function printr($array){
            echo '<pre>';
            echo print_r($array, true);
            echo '</pre>';
      }
      
}