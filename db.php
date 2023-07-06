<?php
include_once 'item.php';
function get_new_connection()
{
    $hostname = "127.0.0.1";
    $username = "root";
    $pwd = "";
    $db = "ProjectDB";
    $conn = new mysqli($hostname, $username, $pwd, $db);
    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function sqls()
{
    static $sqls = null;
    if ($sqls == null) {
        $sqls = [
            "insert_item" => file_get_contents('sql/insert_item.sql'),
            "get_max_itemId" => file_get_contents('sql/get_max_item_id.sql'),
            "get_all_item" => file_get_contents('sql/get_all_item.sql'),
            "delete_item" => file_get_contents('sql/delete_item.sql'),
            "get_busy_item" => file_get_contents('sql/get_busy_item.sql'),
            "update_item" => file_get_contents('sql/update_item.sql'),
            "generate_report" => file_get_contents('sql/generate_report.sql'),
            "get_item_image_by_id" => file_get_contents('sql/get_item_image_by_id.sql')
        ];
    }
    return $sqls;
}

function generate_report(string $id)
{
    return get_from_db("generate_report", [$id]);
}

function update_item(int $id, string $image_file, string $item_description, int $stock_item_qty, int $price)
{
    execute_query("update_item", [$image_file, $item_description, $stock_item_qty, $price, $id]);
}

function is_busy(int $item_id)
{
    return get_from_db("get_busy_item", [$item_id])->fetch_assoc()['COUNT(ITEMID)'] > 0;
}

function get_item_image_by_id(int $id)
{
    return get_from_db("get_item_image_by_id", [$id])->fetch_assoc()['IMAGEFILE'];
}

function delete_item(int $id)
{
    execute_query("delete_item", [$id]);
}
function get_max_item_id()
{
    return get_from_db("get_max_itemId")->fetch_assoc()['MAX(ITEMID)'];
}

function insert_item(array $data)
{
    return get_from_db("insert_item", $data);
}


function get_all_items(string $id)
{
    return get_list_from_db("get_all_item", function ($item) {
        return new Item($item);
    }, [$id]);
}

function return_itself($arg)
{
    return $arg;
}

function get_list_from_db(string $sql, $func = null, array $params = null)
{
    if ($func == null) {
        $func = 'return_itself';
    }
    $items = [];
    $result = get_from_db($sql, $params);
    while ($item = $result->fetch_assoc()) {
        array_push($items, $func($item));
    }
    return $items;
}

function get_from_db(string $sql_file, array $params = null)
{
    $conn = get_new_connection();
    $sql = sqls()[$sql_file];
    $result = $conn->execute_query($sql, $params);
    $conn->close();
    return $result;
}

function execute_query(string $sql_file, array $params = null)
{
    $conn = get_new_connection();
    $sql = sqls()[$sql_file];
    $conn->execute_query($sql, $params);
    $conn->close();
}
?>