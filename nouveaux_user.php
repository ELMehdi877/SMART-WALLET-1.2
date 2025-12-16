<?php
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if($stmt->execute([$username, $password])){
        echo "تم التسجيل بنجاح!";
    } else {
        echo "حدث خطأ!";
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="modal_autenfication"
    class="hidden flex items-center justify-center px-4 fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10 animate-slide-up">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <span class="text-3xl">💳</span>
                <h3 class="text-2xl font-bold text-gray-800">Ajouter une Dépense</h3>
            </div>
            <button onclick="closeModal('expenseModalModifie')"
                class="text-gray-400 hover:text-red-600 text-3xl transition-colors">
                &times;
            </button>
        </div>
        <form method="post">
            <input type="text" name="username" placeholder="اسم المستخدم" required><br>
            <input type="password" name="password" placeholder="كلمة المرور" required><br>
            <button type="submit" name="register">تسجيل</button>
        </form>

        
    </div>
</div>
</body>
</html>
