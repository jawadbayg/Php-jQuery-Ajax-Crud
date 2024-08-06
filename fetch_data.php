<?php
require 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM data";
if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

$result = $conn->query($sql);

$tableRows = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tableRows .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['age']}</td>
            <td>
                <a href='read.php?id={$row['id']}' class='btn btn-success btn-sm'>Read</a>
                <a href='edit.php?id={$row['id']}' class='btn btn-info btn-sm'>Edit</a>
                <a href='index.php?action=delete&id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
            </td>
        </tr>";
    }
} else {
    $tableRows = "<tr><td colspan='5'>No results found</td></tr>";
}

echo $tableRows;
?>
