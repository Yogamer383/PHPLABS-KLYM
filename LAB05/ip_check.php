<?php
// Варіант 9 – Задача 2: Перевірка IP‑адреси
// Форму передаємо методом POST

// Ініціалізація змінної результату
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо IP‑адресу з форми
    $ip = trim($_POST['ip'] ?? '');
    
    // Перевірка коректності IP (IPv4 або IPv6)
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        $message = "IP‑адреса коректна.";
    } else {
        $message = "Введено некоректну IP‑адресу.";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Перевірка IP‑адреси</title>
</head>
<body>
    <h1>Перевірка IP‑адреси</h1>
    <form action="" method="post">
        <label for="ip">IP‑адреса:</label>
        <input type="text" id="ip" name="ip" required>
        <button type="submit">Перевірити</button>
    </form>
    <?php if (!is_null($message)): ?>
        <p><strong>Результат:</strong> <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
</body>
</html>