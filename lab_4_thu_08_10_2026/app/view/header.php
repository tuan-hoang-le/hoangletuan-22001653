<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping cart management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .error {
            color: #ff0000;
            margin-bottom: 15px;
        }
        .success {
            color: #00ff00;
            margin-bottom: 15px;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #ccc;
            background-color: #eee;
            color: #333;
            margin-right: 5px;
        }
        nav {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Shopping cart management</h1>
    <nav>
        <a href="product_list.php" class="btn">Product list</a>
        <a href="product_add.php" class="btn">Add new product</a>
    </nav>
    <hr>