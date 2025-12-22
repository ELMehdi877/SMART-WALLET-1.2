<?php
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_existe"][0];

$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

require("check_card.php");

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["expense_affect"])){
    if (isset($_POST["card_id"]) && isset($_POST["category_id"]) && isset($_POST["expense_amount"]) && isset($_POST["expense_description"]) && isset($_POST["expense_date"])) {

        $card_id = $_POST["card_id"];
        $category_id = $_POST["category_id"];
        $expense_amount = $_POST["expense_amount"];
        $expense_description = $_POST["expense_description"];
        $expense_date = $_POST["expense_date"];
        
        $stmt3 = $pdo->prepare("SELECT SUM(e.amount) AS total_expense , c.category_name,c.monthly_limite 
                        FROM expenses e
                        INNER JOIN category c 
                        ON e.category_id = c.id  
                        WHERE c.user_id = ? AND c.id = ? ");
        $stmt3->execute([$user_id,$category_id]);
        $limite = $stmt3->fetch(PDO::FETCH_ASSOC);
        $total = $expense_amount + $limite["total_expense"];
        if ($total > $limite["monthly_limite"]) {
        $_SESSION['limite'] = "⚠️ tu as depasser la limite pour le categorie  {$limite["category_name"]}";
        }

        else {

            $stmt_check = $pdo->prepare("SELECT balance FROM cards WHERE id = ? AND user_id = ?");
            $stmt_check->execute([$card_id, $user_id]);
            $card = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($expense_amount > $card['balance']) {

                $_SESSION['not_balance'] = "⚠️ Pas assez de solde sur la carte !";
            }
            
            else{

                $stmt1 = $pdo->prepare("INSERT INTO expenses(category_id,card_id,amount,description,expense_date) VALUES (?,?,?,?,?)");
                $stmt1->execute([$category_id,$card_id,$expense_amount,$expense_description,$expense_date]);

                $stmt2 = $pdo->prepare("UPDATE cards SET balance = balance - ? WHERE id = ? AND user_id = ?");
                $stmt2->execute([$expense_amount,$card_id,$user_id]);
            }
        }
        
        
        header("Location: cards.php");
        exit();
    }
}
