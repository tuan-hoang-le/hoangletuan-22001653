<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping cart management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
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
        button, .btn {
            display: inline-block;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            padding: 6px 12px;
            text-decoration: none;
            border: 1px solid #ccc;
            background-color: #eee;
            color: #333;
            margin-right: 5px;
            cursor: pointer;
            vertical-align: middle;
            box-sizing: border-box;
            border-radius: 3px;
        }
        button:hover, .btn:hover {
            background-color: #ddd;
        }
        input[type="text"] {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 320px;
            box-sizing: border-box;
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