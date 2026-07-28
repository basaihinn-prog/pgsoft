<?php
echo "<h2>Database Connection Test</h2>";

try {
    $host = "localhost";
    $port = "5432";
    $dbname = "pgsoft";
    $user = "postgres";
    $password = "root123";
    
    echo "Attempting connection to: pgsql:host=$host;port=$port;dbname=$dbname<br>";
    echo "User: $user<br><br>";
    
    $conn = new PDO("pgsql:host={$host};port={$port};dbname={$dbname}", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<span style='color:green;'><strong>✓ Connection successful!</strong></span><br><br>";
    
    // Test agents table
    echo "<h3>Agents Table:</h3>";
    $result = $conn->query("SELECT * FROM agents");
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Agent Code</th><th>Password</th><th>Balance</th></tr>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['agentcode'] . "</td>";
        echo "<td>" . $row['senha'] . "</td>";
        echo "<td>" . $row['saldo'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<span style='color:red;'><strong>✗ Error:</strong> " . $e->getMessage() . "</span>";
}
?>
<br><br>
<a href="/">Back to Login</a>
