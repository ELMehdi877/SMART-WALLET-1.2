<?php session_start();
#insert incomes
$incomeCategory = $_POST["incomeCategory"] ?? null;
$incomeAmount = $_POST["incomeAmount"] ?? null;
$incomeDesc = $_POST["incomeDesc"] ?? null; 
$incomeDate = $_POST["incomeDate"] ?? null; 

#insert expenses
$expenseCategory = $_POST["expenseCategory"] ?? null;
$expenseAmount = $_POST["expenseAmount"] ?? null;
$expenseDesc = $_POST["expenseDesc"] ?? null;
$expenseDate = $_POST["expenseDate"] ?? null;

#modification incomes
$incomeUpdateid = $_POST['incomeUpdateid'] ?? null;
$incomeUpdateCategory = $_POST['incomeUpdateCategory'] ?? null;
$incomeUpdateAmount = $_POST['incomeUpdateAmount'] ?? null;
$incomeUpdateDesc = $_POST['incomeUpdateDesc'] ?? null;
$incomeUpdateDate = $_POST['incomeUpdateDate'] ?? null;

#modification expenses
$expenseUpdateid = $_POST['expenseUpdateid'] ?? null;
$expenseUpdateCategory = $_POST['expenseUpdateCategory'] ?? null;
$expenseUpdateAmount = $_POST['expenseUpdateAmount'] ?? null;
$expenseUpdateDesc = $_POST['expenseUpdateDesc'] ?? null;
$expenseUpdateDate = $_POST['expenseUpdateDate'] ?? null;

#id delete incomes
$incomeDelete = $_POST['incomeDelete'] ?? null;

#id delete expenses
$expenseDelete=$_POST['expenseDelete'] ?? null;

// if (!isset($_SESSION["icomses"]) ) {
//     $_SESSION["icomses"]=[];
// }

// if (!isset($_SESSION["expense"]) ) {
    // $_SESSION["expense"]=[];
    // }
    
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");


// ✅ حط كود PDF هنا في الأول
if (isset($_GET['incomes_pdf'])) {  // ← شرط جديد
    require("fpdf.php");
    
    $stmt = $pdo->query("SELECT categorie, montants, description, date FROM incomes");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 16);
    $pdf->Cell(0, 10, "Incomes List", 0, 1, "C");
    $pdf->Ln(5);
    
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(40, 10, "Categorie", 1);
    $pdf->Cell(30, 10, "Montant", 1);
    $pdf->Cell(80, 10, "Description", 1);
    $pdf->Cell(40, 10, "Date", 1);
    $pdf->Ln();
    
    $pdf->SetFont("Arial", "", 12);
    foreach ($data as $row) {
        $pdf->Cell(40, 10, utf8_decode($row["categorie"]), 1);
        $pdf->Cell(30, 10, $row["montants"], 1);
        $pdf->Cell(80, 10, utf8_decode($row["description"]), 1);
        $pdf->Cell(40, 10, $row["date"], 1);
        $pdf->Ln();
    }
    
    // $pdf->Output();
    $pdf->Output('D', 'incomes_list.pdf');
    exit;  // ✅ نوقفو هنا
}

if (isset($_GET['expenses_pdf'])) {  // ← شرط جديد
    require("fpdf.php");
    
    $stmt = $pdo->query("SELECT categorie, montants, description, date FROM expenses");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 16);
    $pdf->Cell(0, 10, "Expenses List", 0, 1, "C");
    $pdf->Ln(5);
    
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(40, 10, "Categorie", 1);
    $pdf->Cell(30, 10, "Montant", 1);
    $pdf->Cell(80, 10, "Description", 1);
    $pdf->Cell(40, 10, "Date", 1);
    $pdf->Ln();
    
    $pdf->SetFont("Arial", "", 12);
    foreach ($data as $row) {
        $pdf->Cell(40, 10, utf8_decode($row["categorie"]), 1);
        $pdf->Cell(30, 10, $row["montants"], 1);
        $pdf->Cell(80, 10, utf8_decode($row["description"]), 1);
        $pdf->Cell(40, 10, $row["date"], 1);
        $pdf->Ln();
    }
    
    // $pdf->Output();
    $pdf->Output('D', 'expenses_list.pdf');
    exit;  // ✅ نوقفو هنا
}

# filtrage incomes par categorie
$categorie_income = $_GET['categorie_income'] ?? null;
if(isset($categorie_income) && !empty($categorie_income)){
    if ($categorie_income == 'ALL') {
        $stmt = $pdo->query("SELECT * FROM incomes");
    }
    else{
        $stmt = $pdo->prepare("SELECT * FROM incomes WHERE categorie = ?");
        $stmt -> execute([$categorie_income]);
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

# filtrage incomes par date
$date_income = $_GET['date_income'] ?? null;
if (isset($date_income) ) {
   if (!empty($date_income)) {
    $stmt = $pdo->prepare("SELECT * FROM incomes WHERE DATE(date) = ?");
    $stmt->execute([$date_income]);
    } 
    else {
        $stmt = $pdo->query("SELECT * FROM incomes");
    }
     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}




# filtrage expense categorie
$categorie_expense = $_GET['categorie_expense'] ?? null;
if(isset($categorie_expense) && !empty($categorie_expense)){
    if ($categorie_expense == 'ALL') {
        $stmt = $pdo->query("SELECT * FROM expenses");
    }
    else{
        $stmt = $pdo->prepare("SELECT * FROM expenses WHERE categorie = ?");
        $stmt -> execute([$categorie_expense]);
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

# filtrage expense par date
$date_expense = $_GET['date_expense'] ?? null;
if (isset($date_expense) ) {
   if (!empty($date_expense)) {
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE DATE(date) = ?");
    $stmt->execute([$date_expense]);
    } 
    else {
        $stmt = $pdo->query("SELECT * FROM expenses");
    }
     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
    // ============================================
// 🔍 FILTRAGE (AJAX uniquement - doit être en premier)
// ============================================
// if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
//     $incomeCategory_filtre = $_GET['categorie'];
    
//     if ($incomeCategory_filtre === 'ALL') {
//         $stmt = $pdo->query("SELECT * FROM incomes ORDER BY date DESC");
//     } else {
//         $stmt = $pdo->prepare("SELECT * FROM incomes WHERE categorie = ? ORDER BY date DESC");
//         $stmt->execute([$incomeCategory_filtre]);
//     }
    
//     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     header('Content-Type: application/json');
//     echo json_encode($data);
//     exit; // Important: arrêter ici pour le filtrage
// }

#insert incomes
if (!empty($incomeCategory) && !empty($incomeAmount) && !empty($incomeDesc)) {
    if (!empty($incomeDate)) {
        // $_SESSION['incomes'][]=[$incomeCategory,$incomeAmount,$incomeDesc];
        // print_r($_SESSION['incomes']);
        $stmt=$pdo->prepare("INSERT INTO incomes (categorie,montants,description,date) VALUES (?,?,?,?)");
        $stmt->execute([$incomeCategory,$incomeAmount,$incomeDesc,$incomeDate]);
    }

    else {
        $stmt=$pdo->prepare("INSERT INTO incomes (categorie,montants,description) VALUES (?,?,?)");
        $stmt->execute([$incomeCategory,$incomeAmount,$incomeDesc]);
    }
}
#insert expenses
if (!empty($expenseCategory) && !empty($expenseAmount) && !empty($expenseDesc)) {
    if (!empty($expenseDate)) {
        $_SESSION['expenses'][]=[$expenseCategory,$expenseAmount,$expenseDesc];
        print_r($_SESSION['expenses']);
        $stmt=$pdo->prepare("INSERT INTO expenses (categorie,montants,description,date) VALUES (?,?,?,?)");
        $stmt->execute([$expenseCategory,$expenseAmount,$expenseDesc,$expenseDate]);
    }

    else {
        $stmt=$pdo->prepare("INSERT INTO expenses (categorie,montants,description) VALUES (?,?,?)");
        $stmt->execute([$expenseCategory,$expenseAmount,$expenseDesc]);
    }
}

#modifie incomes
if (!empty($incomeUpdateid) && !empty($incomeUpdateCategory) && !empty($incomeUpdateAmount) && !empty($incomeUpdateDesc)) {
    // $_SESSION['incomes'][]=[$incomeUpdateCategory,$incomeUpdateAmount,$incomeUpdateDesc];
    // print_r($_SESSION['incomes']);
    $stmt=$pdo->prepare("UPDATE incomes SET categorie = ? , montants = ? , description = ? , date = ? WHERE id = ? ");
    $stmt->execute([$incomeUpdateCategory,$incomeUpdateAmount,$incomeUpdateDesc,$incomeUpdateDate,$incomeUpdateid]);
    }
    else if (!empty($incomeUpdateCategory) && !empty($incomeUpdateAmount) && !empty($incomeUpdateDesc)) {
    // $_SESSION['incomes'][]=[$incomeUpdateCategory,$incomeUpdateAmount,$incomeUpdateDesc];
    // print_r($_SESSION['incomes']);
    $stmt=$pdo->prepare("UPDATE incomes SET categorie = ? , montants = ? , description = ? WHERE id = ? ");
    $stmt->execute([$incomeUpdateCategory,$incomeUpdateAmount,$incomeUpdateDesc,$incomeUpdateid]);
    }


#modifie expenses
    // if (!empty($expenseUpdateCategory) && !empty($expenseUpdateAmount) && !empty($expenseUpdateDesc) && !empty($expenseUpdateDate)) {
    // $_SESSION['expenses'][]=[$expenseUpdateCategory,$expenseUpdateAmount,$expenseUpdateDesc];
    // print_r($_SESSION['expenses']);
    // $stmt=$pdo->prepare("UPDATE expenses SET categorie = ? , montants = ? , description = ? , date = ? WHERE id = ? ");
    // $stmt->execute([$expenseUpdateCategory,$expenseUpdateAmount,$expenseUpdateDesc,$expenseUpdateDate,$expenseUpdateid]);
    // }
    // else if (!empty($expenseUpdateCategory) && !empty($expenseUpdateAmount) && !empty($expenseUpdateDesc)) {
    // $_SESSION['expenses'][]=[$expenseUpdateCategory,$expenseUpdateAmount,$expenseUpdateDesc];
    // print_r($_SESSION['expenses']);
    // $stmt=$pdo->prepare("UPDATE expenses SET categorie = ? , montants = ? , description = ? WHERE id = ? ");
    // $stmt->execute([$expenseUpdateCategory,$expenseUpdateAmount,$expenseUpdateDesc,$expenseUpdateid]);
    // }
if (!empty($expenseUpdateid) && !empty($expenseUpdateCategory) && !empty($expenseUpdateAmount) && !empty($expenseUpdateDesc)) {
    if (!empty($expenseUpdateDate)) {
        $stmt = $pdo->prepare("UPDATE expenses SET categorie = ?, montants = ?, description = ?, date = ? WHERE id = ?");
        $stmt->execute([$expenseUpdateCategory, $expenseUpdateAmount, $expenseUpdateDesc, $expenseUpdateDate, $expenseUpdateid]);
    } else {
        $stmt = $pdo->prepare("UPDATE expenses SET categorie = ?, montants = ?, description = ? WHERE id = ?");
        $stmt->execute([$expenseUpdateCategory, $expenseUpdateAmount, $expenseUpdateDesc, $expenseUpdateid]);
    }
}

#delete income
if (!empty($incomeDelete)) {
    $stmt = $pdo->prepare("DELETE FROM incomes WHERE id = ?");
    $stmt->execute([$incomeDelete]);
}

#delete espense
if (!empty($expenseDelete)) {
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->execute([$expenseDelete]);
}



header("Location: index.php");
exit;

