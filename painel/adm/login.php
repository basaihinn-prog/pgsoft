?php
include("includes/connect.php");
error_reporting(0);
ini_set('display_errors', 1);
$admin_agentCode = pg_escape_string($conn, $_POST['agentCode']);
$admin_senha = pg_escape_string($conn, $_POST['senha']);
$auth = 'admin_in';

$query = pg_query($conn, "SELECT * FROM agents WHERE agentCode = '$admin_agentCode' AND senha = '$admin_senha'");
if (pg_num_rows($query) == 0) {
header("location: index.php");
} else {
$row = pg_fetch_array($query);
setcookie("admin_id", $row["id"]);
setcookie("admin_pass", $admin_senha);
setcookie("auth", $auth);
header("location: home.php");
}
?>