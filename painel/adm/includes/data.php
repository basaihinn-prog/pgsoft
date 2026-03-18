<?php
error_reporting(0);
ini_set('display_errors', 1);
function custom_pg_result($res, $row = 0, $col = 0) {
    $numrows = pg_num_rows($res);
    if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
        pg_result_seek($res, $row);
        $response_row = (is_numeric($col)) ? pg_fetch_row($res) : pg_fetch_assoc($res);
        if (isset($response_row[$col])) {
            return $response_row[$col];
        }
    }
    return false;
}

function qSELECT($query, $object = NULL) {
    global $conn;
    $result = pg_query($conn, $query);
    $return = [];
    if ($result) {
        $num = pg_num_rows($result);
        for ($i=0; $i<$num; $i++) {
            if (!is_null($object)) {
                $row = pg_fetch_object($result);
            } else {
                $row = pg_fetch_assoc($result);
            }
            $return[$i] = $row;
        }
    }
    return $return;
}

function counting($table, $what) {
    global $conn;
    $query = "SELECT COUNT(1) FROM ".$table;
    $result = pg_query($conn, $query);
    $num = pg_result($result, 0, 0);
    return $num;
}

function getById($table, $id) {
    $query = "SELECT * FROM ".$table." WHERE id=".$id." ";
    $result = qSELECT($query);
    if ($result) return $result[0];
    else return $result;
}

function getByIdd($table) {
    $id_get = 2;
    $query = "SELECT * FROM ".$table." WHERE agentid=".$id_get." ";
    $result = qSELECT($query);
    if ($result) return $result[0];
    else return $result;
}

function getByAg($table) {
    $query = "SELECT * FROM ".$table." WHERE agentid=".print_r($_COOKIE['admin_id'], true)." ";
    $result = qSELECT($query);
    if ($result) return $result[0];
    else return $result;
}

function getAll($table) {
    $query = "SELECT * FROM ".$table." WHERE agentid=".$_COOKIE['admin_id']." ";
    $result = qSELECT($query);
    return $result;
}

function getAG($table) {
    $query = "SELECT * FROM ".$table." WHERE id=".$_COOKIE['admin_id']." ";
    $result = qSELECT($query);
    return $result;
}

function queryToSelect($table, $where, $operator, $zero_value, $key, $value, $id) {
    $ul = '<option value="'.$zero_value.'">Please select</option>';
    $query = "SELECT * FROM ".$table." WHERE \"".$where."\" ".$operator." ".$zero_value." ";
    $result = qSELECT($query);
    foreach ($result as $row) {
        $ul .= '<option value="'.$row[$key].'" ';
        $ul .= $id == $row[$key] ? "selected" : "" ;
        $ul .= '>'.$row[$value].'</option>';
    }
    return $ul;
}
?>