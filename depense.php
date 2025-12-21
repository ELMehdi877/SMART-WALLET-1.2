<?php
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["depense_affect"])){
    if (isset($_POST["card_id"]) && isset($_POST["expense_amount"]) && isset($_POST["expense_description"]) && isset($_POST["expense_date"])) {

        $card_id = $_POST["card_id"];
        $expense_amount = $_POST["expense_amount"];
        $expense_description = $_POST["expense_description"];
        $expense_date = $_POST["expense_date"];
        
        $stmt1 = $pdo->prepare("INSERT INTO expenses(user_id,card_id,amount,description,expense_date) VALUES (?,?,?,?,?)");
        $stmt1->execute([$user_id,$card_id,$expense_amount,$expense_description,$expense_date]);
        
        $stmt2 = $pdo->prepare("UPDATE cards SET balance = balance - ? WHERE id = ? AND user_id = ?");
        $stmt2->execute([$expense_amount,$card_id,$user_id]);
        
        if () {
            
        }
        
        header("Location: cards.php");
        exit();
    }
}