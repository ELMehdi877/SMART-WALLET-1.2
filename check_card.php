<?php
// session_start();
// if (!isset($_SESSION["user_existe"])) {
//     header("Location: index.php");
//     exit;
// }
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");
$stmt1 = $pdo->prepare("SELECT * FROM cards WHERE user_id = ?");
$stmt1->execute([$user_id]);
$existe_card = $stmt1->fetchAll(PDO::FETCH_ASSOC);

if (empty($existe_card)) {
    $_SESSION["not_card"]= "<p class='text-red-500'>⚠️ Il Faut Crée Une <spam class='font-bold'>CARTE</spam> Pour L'Affectation</p>";
    header("Location: cards.php");
    exit();
}