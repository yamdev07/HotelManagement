<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $stmt = $pdo->query('SHOW DATABASES');
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases available:\n";
    foreach ($dbs as $db) {
        echo "  - $db\n";
    }
    if (in_array('hotelio', $dbs)) {
        echo "\n[OK] Database 'hotelio' exists.\n";
    } else {
        echo "\n[ERROR] Database 'hotelio' NOT found!\n";
    }
} catch (Exception $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
