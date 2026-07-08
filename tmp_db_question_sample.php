<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=grapecultivation01;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "MASTER_DATA rows:\n";
    $stmt = $pdo->query("SELECT id, category, code, label FROM master_data ORDER BY category, id");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }

    echo "\nRECENT QUESTIONS:\n";
    $stmt = $pdo->query("SELECT question_id, farmer_id, category_id, status_id, expert_id, title, image, created_at FROM questions ORDER BY created_at DESC LIMIT 10");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
