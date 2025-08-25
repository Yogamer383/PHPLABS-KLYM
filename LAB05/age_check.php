<?php
// Варіант 9 – Задача 1: Перевірка віку
// Форму передаємо методом GET

// Перевірка, чи передано параметр 'age'
if (isset($_GET['age'])) {
    // Отримання віку та приведення до цілого числа
    $age = intval($_GET['age']);
    
    // Перевірка, чи користувач старший за 18 років
    if ($age > 18) {
        $message = "Користувач старший за 18 років.";
    } else {
        $message = "Користувач не досяг 18 років.";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Перевірка віку</title>
</head>
<body>
    <h1>Перевірка віку</h1>
    <form action="" method="get">
        <label for="age">Вік:</label>
        <input type="number" id="age" name="age" min="0" required>
        <button type="submit">Перевірити</button>
    </form>
    <?php if (isset($message)): ?>
        <p><strong>Результат:</strong> <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
</body>
</html>