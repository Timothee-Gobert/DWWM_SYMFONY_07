## faire son projet dans

``` xampp\htdoc\DOSSIER_du_projet ```

## dans l'invite de commande tapper 

``` > composer require symfony/apache-pack ```

## pour ne pas afficher le dossier il faut creer un fichier index.php a la racine avec a l'interrieur

``` 
index.php

<?php
      header('location:public/index.php');