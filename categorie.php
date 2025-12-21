<?php 
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_category"])) {
    if (isset($_POST["category_name"]) && isset($_POST["category_monthly_limite"])) {

        $category_name = $_POST["category_name"];
        $category_monthly_limite = $_POST["category_monthly_limite"];
        $check_recurring = $_POST["check_recurring"] ?? 0;

        $stmt = $pdo->prepare("SELECT category_name FROM category WHERE category_name = ?");
        $stmt->execute([$category_name]);
        $category_existe = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($category_existe)) {
            $stmt1 = $pdo->prepare("INSERT INTO category(user_id,category_name,monthly_limite,check_recurring) VALUES (?,?,?,?)");
            $stmt1->execute([$user_id,$category_name,$category_monthly_limite,$check_recurring]);
            
            header("Location: cards.php");
            exit();
        }
        else {
            $_SESSION["category_existe"] = "<p class='text-yellow-500'>⚠️ Cette Categorie <spam class='text-yellow-300 font-bold text-xl'>$category_name</spam> existe déja</p>";
            header("Location: cards.php");
            exit();
        }
    }
}