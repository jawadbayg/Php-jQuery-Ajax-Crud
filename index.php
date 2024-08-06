<?php
require 'db.php';
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? '';
    $id = $_POST['id'] ?? '';

    if ($action == 'create') {
        $sql = "INSERT INTO data (name, email, age) VALUES ('$name', '$email', '$age')";
        if ($conn->query($sql) === TRUE) {
            header('Location: index.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action == 'update') {
        $sql = "UPDATE data SET name='$name', email='$email', age='$age' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header('Location: index.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    exit();
}
 elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM data WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    exit();
}

$sql = "SELECT * FROM data";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<div class="container">
    <h2 class="mt-4">Users List</h2>
    <a href="create.php" class="btn btn-info mb-2">Add User</a>
    <input type="text" id="search" placeholder="Search..." class="form-control mb-3">
    <table class="table table-bordered" id="formData">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['age'] ?></td>
                    <td>
                        <a href="read.php?id=<?= $user['id'] ?>" class="btn btn-success btn-sm">Read</a>
                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                        <a href="index.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- <script>
$(document).ready(function() {
    $('#formData').DataTable();
    
    $('#search').on('keyup', function() {
        $('#formData').DataTable().search(this.value).draw();
    });

    $('#formData').on('click', '.btn-danger', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = url;
        }
    });
});
</script> -->

<script>
$(document).ready(function() {
    function loadTableData(query = '') {
        $.ajax({
            url: 'fetch_data.php',
            type: 'GET',
            data: { search: query },
            success: function(response) {
                $('#formData tbody').html(response);
            }
        });
    }

    // Load initial table data
    // loadTableData()

    $('#search').on('keyup', function() {
        var query = $(this).val();
        loadTableData(query);
    });

    $('#formData').on('click', '.btn-danger', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function() {
                    const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
                });
                Toast.fire({
                icon: "success",
                title: "Deleted Successfully"
                });
                    loadTableData($('#search').val());
                }
            });
        }
    });
});

});
</script>


</body>
</html>




<!-- 

<!DOCTYPE html>
<html lang="en">
<head>
    
    <style>
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: auto;
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<button id="openModal">Open Modal</button>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Modal Header</h2>
        <p>This is a simple modal popup.</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var modal = $('#myModal');
        var btn = $('#openModal');
        var span = $('.close');

        btn.on('click', function() {
            modal.show();
        });

        span.on('click', function() {
            modal.hide();
        });

        $(window).on('click', function(event) {
            if ($(event.target).is(modal)) {
                modal.hide();
            }
        });
    });
</script>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .accordion {
            margin: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .accordion .header {
            background-color: #f1f1f1;
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .accordion .content {
            display: none;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .accordion .header.active {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="accordion">
    <div class="header">Section 1</div>
    <div class="content">Content for Section 1</div>
    <div class="header">Section 2</div>
    <div class="content">Content for Section 2</div>
    <div class="header">Section 3</div>
    <div class="content">Content for Section 3</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.accordion .header').on('click', function() {
            var $content = $(this).next('.content');
            
            $content.slideToggle();
            
            $('.accordion .content').not($content).slideUp();
            
            $(this).toggleClass('active');
            
            $('.accordion .header').not($(this)).removeClass('active');
        });
    });
</script>

</body>
</html> -->
