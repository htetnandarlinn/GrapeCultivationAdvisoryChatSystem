<?php
require __DIR__ . '/vendor/autoload.php';

function fileInfo(string $path): string {
    return file_exists($path) ? "EXISTS {" . filesize($path) . "}" : 'MISSING';
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=grapecultivation01;charset=utf8mb4','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $datadir = $pdo->query("SHOW VARIABLES LIKE 'datadir'")->fetch(PDO::FETCH_ASSOC)['Value'];
    $db = $pdo->query('SELECT DATABASE()')->fetchColumn();
    $path = rtrim($datadir, '/\\') . DIRECTORY_SEPARATOR . $db . DIRECTORY_SEPARATOR . 'users.ibd';
    $frmPath = rtrim($datadir, '/\\') . DIRECTORY_SEPARATOR . $db . DIRECTORY_SEPARATOR . 'users.frm';
    echo "datadir=$datadir\n";
    echo "db=$db\n";
    echo "users.ibd=" . fileInfo($path) . "\n";
    echo "users.frm=" . fileInfo($frmPath) . "\n";

    echo "Instantiating UserRepository...\n";
    $repo = new App\Infrastructure\Persistence\Repositories\UserRepository();
    echo "Repository instantiated\n";
    echo "After instantiate users.ibd=" . fileInfo($path) . "\n";
    echo "After instantiate users.frm=" . fileInfo($frmPath) . "\n";

    $table = $pdo->query("SHOW TABLES LIKE 'users'")->fetch(PDO::FETCH_NUM);
    var_dump($table);
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
