<?php
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["expense_affect"])){
    if (isset($_POST["card_id"]) && isset($_POST["expense_category"]) && isset($_POST["expense_amount"]) && isset($_POST["expense_description"]) && isset($_POST["expense_date"])) {

        $card_id = $_POST["card_id"];
        $expense_category = $_POST["expense_category"];
        $expense_amount = $_POST["expense_amount"];
        $expense_description = $_POST["expense_description"];
        $expense_date = $_POST["expense_date"];
        $expense_limite = $_POST["expense_limite"] ?? NULL;
        $check_recurring = $_POST["check_recurring"] ?? 0;
        
        $stmt1 = $pdo->prepare("INSERT INTO expenses(user_id,card_id,category,amount,description,monthly_limite,expense_date,check_recurring) VALUES (?,?,?,?,?,?,?,?)");
        $stmt1->execute([$user_id,$card_id,$expense_category,$expense_amount,$expense_description,$expense_limite,$expense_date,$check_recurring]);
        
        $stmt2 = $pdo->prepare("UPDATE cards SET balance = balance - ? WHERE id = ? AND user_id = ?");
        $stmt2->execute([$expense_amount,$card_id,$user_id]);
        
        //definie une limite a une categorie
        // if (!empty($_POST["expense_limite"])) {
        //     $stmt1 = $pdo->prepare("UPDATE expenses SET expense_limite = ? WHERE user_id = ? ");
        //     $stmt1->execute([$expense_limite,$user_id]);
        // }

        //definier une recurence a une categorie
        // if (!empty($_POST["check_recurring"])) {
        //     $stmt1 = $pdo->prepare("UPDATE expenses SET check_recurring = ? WHERE user_id = ?");
        //     $stmt1->execute([$check_recurring,$user_id]);
        // }
        
        header("Location: cards.php");
        exit();
    }
}