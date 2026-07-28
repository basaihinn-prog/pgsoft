<?php
error_reporting(1);
ini_set('display_errors', 1);

echo "=== PGSoft Panel Setup ===<br><br>";

try {
    // Database connection
    $host = "localhost";
    $port = "5432";
    $dbname = "pgsoft";
    $user = "postgres";
    $password = "root123";
    
    echo "Connecting to database...<br>";
    $conn = new PDO("pgsql:host={$host};port={$port};dbname={$dbname}", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection successful!<br><br>";
    
    // Check if agents table exists
    echo "Checking agents table...<br>";
    $result = $conn->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_name = 'agents'");
    $table_exists = $result->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if ($table_exists) {
        echo "✓ Agents table exists<br><br>";
        
        // Check if demo agent exists
        echo "Checking for demo agent...<br>";
        $stmt = $conn->prepare("SELECT id, agentcode, senha FROM agents WHERE agentcode = 'demo' LIMIT 1");
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $agent = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "✓ Demo agent already exists!<br>";
            echo "  - ID: " . $agent['id'] . "<br>";
            echo "  - Code: " . $agent['agentcode'] . "<br>";
            echo "  - Password: " . $agent['senha'] . "<br><br>";
        } else {
            echo "Demo agent not found. Creating one...<br>";
            
            $sql = "INSERT INTO agents (agentcode, saldo, senha, probganho, probbonus, probganhortp, probganhoinfluencer, probbonusinfluencer, probganhoaposta, probganhosaldo, callbackurl)
                    VALUES ('demo', '10000', 'demo123', '0', '0', '0', '0', '0', '0', '0', 'http://localhost/callback')";
            
            $conn->exec($sql);
            echo "✓ Demo agent created successfully!<br>";
            echo "  - Agent Code: <strong>demo</strong><br>";
            echo "  - Password: <strong>demo123</strong><br>";
            echo "  - Balance: 10000<br><br>";
        }
        
        // List all agents
        echo "All agents in database:<br>";
        $stmt = $conn->query("SELECT id, agentcode, saldo FROM agents ORDER BY id");
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($agents) > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Agent Code</th><th>Balance</th></tr>";
            foreach ($agents as $a) {
                echo "<tr><td>" . $a['id'] . "</td><td>" . $a['agentcode'] . "</td><td>" . $a['saldo'] . "</td></tr>";
            }
            echo "</table><br>";
        } else {
            echo "No agents found<br><br>";
        }
        
    } else {
        echo "✗ Agents table does not exist<br>";
    }
    
    $conn = null;
    
    echo "<br><strong>Login with these credentials:</strong><br>";
    echo "Agent Code: <strong>demo</strong><br>";
    echo "Password: <strong>demo123</strong><br><br>";
    echo "<a href='/index.php'>Go to login page</a>";
    
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "<br>";
    echo "Connection String: pgsql:host={$host};port={$port};dbname={$dbname}<br>";
    echo "User: {$user}<br>";
}
?>
