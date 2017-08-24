<?php
$pseudo = $_POST['pseudo'];
setcookie('pseudo', $pseudo, time() + 365*24*3600, null, null, false, true); ?>
<?php
//connection a la database
$DB_HOST = 'localhost';
 $DB_USER = 'root';
 $DB_PASS = 'coda';
 $DB_NAME = 'minichat';
 try{
     $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $DB_con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
 }
 catch(PDOException $e){
     echo $e->getMessage();
 }

// Insertion du message à l'aide d'une requête préparée

$req = $DB_con->prepare('INSERT INTO minichat.tchat (pseudo, message) VALUES( ?,?)');
$req->execute(array($_POST['pseudo'], $_POST['message']));


// Redirection du visiteur vers la page du minichat

header('Location: index.php');

?>




