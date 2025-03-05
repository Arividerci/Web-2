<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма</title>
</head>
<body>
    <h3>Введите данные</h3>
    <?php phpinfo(); ?>
    <?php if (!empty($_GET['message'])): ?>
        <p><strong><?= htmlspecialchars($_GET['message']) ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="form.php">
        <label for="name">Имя:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <button type="submit">Отправить</button>
    </form>
</body>
</html>
