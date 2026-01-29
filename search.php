<?php

$host = 'localhost';
$dbname = 'vivecode';
$user = 'root';
$pass = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$search = isset($_POST['search']) ? $_POST['search'] : '';

$sql = "SELECT
            dev.name as Developer,
            systems.name as System,
            llms.name as Llm
        FROM
            dev
        INNER JOIN systems ON dev.system_id=systems.id
        INNER JOIN llms ON systems.llm_id=llms.id
        WHERE dev.name LIKE :search";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$results = $stmt->fetchAll();

if (count($results) > 0) {
  echo "<table border='1'>";
  echo "<tr>
            <th>Developer</th>
            <th>System</th>
            <th>Llm</th>
          </tr>";
  foreach ($results as $row) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Developer']) . "</td>";
    echo "<td>" . htmlspecialchars($row['System']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Llm']) . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "No results found";
}
?>

