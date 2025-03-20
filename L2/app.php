<?php
session_start();

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidPhone($phone) {
    return preg_match("/^\+?\d{10,13}$/", $phone);
}

function isValidName($name) {
    return preg_match("/^[А-Яа-яЁёA-Za-z\s]{2,500}+$/u", $name);
}

$title = "Интернет-магазин электроники";
$brands = ["Samsung", "Apple", "Xiaomi", "Sony", "LG", "Asus", "HP"];
$file = "orders.csv";
$message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $phone = htmlspecialchars(trim($_POST["phone"] ?? ''));
    $brand = htmlspecialchars(trim($_POST["brand"] ?? ''));
    $model = htmlspecialchars(trim($_POST["model"] ?? ''));
    $quantity = htmlspecialchars($_POST["quantity"] ?? 1);
    $date = date("Y-m-d H:i:s");

    if (empty($name)) {
        $errors[] = "ФИО обязательно для заполнения.";
    } elseif (!isValidName($name)) {
        $errors[] = "ФИО должно содержать только буквы и пробелы от 2х символов.";
    }

    if (empty($email)) {
        $errors[] = "Email обязателен.";
    } elseif (!isValidEmail($email)) {
        $errors[] = "Некорректный email.";
    }

    if (empty($phone)) {
        $errors[] = "Телефон обязателен.";
    } elseif (!isValidPhone($phone)) {
        $errors[] = "Телефон должен быть в формате +79991234567.";
    }

    if (empty($brand) || !in_array($brand, $brands)) {
        $errors[] = "Выберите корректный бренд.";
    }

    if (empty($model)) {
        $errors[] = "Укажите модель товара.";
    }

    if (!is_numeric($quantity) || $quantity < 1) {
        $errors[] = "Количество должно быть числом больше 0.";
    }

    if (empty($errors)) {
        $order = [$date, $name, $email, $phone, $brand, $model, $quantity];
        $file_handle = fopen($file, "a");
        fputcsv($file_handle, $order, ";");
        fclose($file_handle);

        $message = "Спасибо, $name! Ваш заказ на $quantity шт. $brand $model успешно оформлен.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        header {
            background: #007bff;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            font-size: 24px;
        }
        .container {
            width: 90%;
            max-width: 500px;
            background: #fff;
            margin: 30px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            color: #333;
        }
        .message {
            background: #28a745;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        .error-message {
            background: #dc3545;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<header><?= $title ?></header>

<div class="container">
    <h3>Оформление заказа</h3>

    <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <p class="error-message"><?= implode($errors) ?></p>
    <?php endif; ?>

    <form method="post">

        <label>ФИО:</label>
        <input type="text" name="name" value="<?= $_POST['name'] ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?= $_POST['email'] ?>">

        <label>Телефон:</label>
        <input type="tel" name="phone" value="<?= $_POST['phone'] ?>">

        <label>Выберите бренд:</label>
        <select name="brand">
            <?php foreach ($brands as $brandOption): ?>
                <option value="<?= $brandOption ?>">
                    <?= $brandOption ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Модель товара:</label>
        <input type="text" name="model" value="<?= $_POST['model'] ?>">

        <label>Количество:</label>
        <input type="number" name="quantity" min="1" value="<?= $_POST['quantity'] ?>">

        <button type="submit">Оформить заказ</button>
    </form>
</div>

</body>
</html>
