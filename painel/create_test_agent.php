<?php
// Database connection
try {
    $host = "localhost";
    $dbname = "pgsoft";
    $user = "postgres";
    $password = "root123";
    
    $conn = new PDO("pgsql:host={$host};port=5432;dbname={$dbname}", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if demo agent already exists
    $stmt = $conn->prepare("SELECT id FROM agents WHERE agentcode = 'demo' LIMIT 1");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "Demo agent already exists!<br>";
        $row = $stmt->fetch();
        echo "Agent ID: " . $row['id'] . "<br>";
    } else {
        // Insert new test agent
        $sql = "INSERT INTO agents (
            agentcode,
            saldo,
            senha,
            probganho,
            probbonus,
            probganhortp,
            probganhoinfluencer,
            probbonusinfluencer,
            probganhoaposta,
            probganhosaldo,
            callbackurl
        ) VALUES (
            'demo',
            '10000',
            'demo123',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            'http://localhost/callback'
        )";
        
        $conn->exec($sql);
        echo "✓ Test agent created successfully!<br>";
        echo "Agent Code: <strong>demo</strong><br>";
        echo "Password: <strong>demo123</strong><br>";
        echo "Initial Balance: <strong>10000</strong><br>";
    }
    
    $conn = null;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
