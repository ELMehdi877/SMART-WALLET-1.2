<?php
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["income_affect"])){
    if (isset($_POST["card_id"]) && isset($_POST["income_amount"]) && isset($_POST["income_description"]) && isset($_POST["income_date"])) {

        $card_id = $_POST["card_id"];
        $income_amount = $_POST["income_amount"];
        $income_description = $_POST["income_description"];
        $income_date = $_POST["income_date"];
        
        $stmt1 = $pdo->prepare("INSERT INTO incomes(user_id,card_id,amount,description,income_date) VALUES (?,?,?,?,?)");
        $stmt1->execute([$user_id,$card_id,$income_amount,$income_description,$income_date]);
        
        $stmt2 = $pdo->prepare("UPDATE cards SET balance = balance + ? WHERE id = ? AND user_id = ?");
        $stmt2->execute([$income_amount,$card_id,$user_id]);

        
        header("Location: cards.php");
        exit();
    }
}