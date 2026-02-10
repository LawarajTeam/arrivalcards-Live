<?php
require 'includes/config.php';

$stmt = $pdo->query("SELECT country_code, ct.country_name 
                     FROM countries c
                     LEFT JOIN country_translations ct ON c.id = ct.country_id
                     WHERE ct.lang_code = 'en'
                     ORDER BY country_code");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['country_code'] . "\n";
}
