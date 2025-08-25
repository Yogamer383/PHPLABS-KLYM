<?php
// Варіант 9 – Лабораторна робота №6: Взаємодія з MySQL
// Цей скрипт створює базу даних «University» та таблицю «Courses»,
// дозволяє додавати нові курси, переглядати всі курси та видаляти
// курси, які неактивні більше двох років.

// Налаштування підключення до MySQL
$host = 'localhost';          // адреса сервера бази даних
$user = 'root';               // ім'я користувача MySQL
$pass = '';                   // пароль користувача MySQL (замініть на свій)
$dbname = 'University';       // ім'я бази даних

try {
    // Підключаємося до сервера MySQL без зазначення бази даних
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Створюємо базу даних, якщо її не існує
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Вибираємо створену базу даних
    $pdo->exec("USE `$dbname`");

    // Створюємо таблицю Courses, якщо її не існує
    $pdo->exec("CREATE TABLE IF NOT EXISTS Courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_name VARCHAR(255) NOT NULL,
        duration INT NOT NULL,
        credits INT NOT NULL,
        last_active DATE NOT NULL DEFAULT CURRENT_DATE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {
    die('Помилка підключення: ' . $e->getMessage());
}

// Додавання нового курсу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name'] ?? '');
    $duration    = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $credits     = isset($_POST['credits']) ? intval($_POST['credits']) : 0;

    // Перевірка даних
    if ($course_name !== '' && $duration > 0 && $credits > 0) {
        $stmt = $pdo->prepare('INSERT INTO Courses (course_name, duration, credits, last_active) VALUES (?, ?, ?, CURDATE())');
        $stmt->execute([$course_name, $duration, $credits]);
    }
}

// Видалення курсів, які неактивні більше 2 років
if (isset($_GET['delete_old'])) {
    $pdo->exec("DELETE FROM Courses WHERE last_active < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)");
}

// Отримуємо список усіх курсів
$courses = $pdo->query('SELECT * FROM Courses ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Управління курсами – Варіант 9</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        table { border-collapse: collapse; width: 100%; margin-top: 1em; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Управління курсами</h1>
    <p>Ця сторінка дозволяє додавати нові курси, переглядати всі курси та видаляти ті, які неактивні більше двох років.</p>
    <h2>Додати новий курс</h2>
    <form method="post" action="">
        <label>
            Назва курсу:
            <input type="text" name="course_name" required>
        </label><br>
        <label>
            Тривалість (у тижнях):
            <input type="number" name="duration" min="1" required>
        </label><br>
        <label>
            Кількість кредитів:
            <input type="number" name="credits" min="1" required>
        </label><br>
        <button type="submit">Додати курс</button>
    </form>

    <h2>Список курсів</h2>
    <?php if (count($courses) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Назва курсу</th>
                <th>Тривалість</th>
                <th>Кредити</th>
                <th>Дата активності</th>
            </tr>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo $course['id']; ?></td>
                    <td><?php echo htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $course['duration']; ?></td>
                    <td><?php echo $course['credits']; ?></td>
                    <td><?php echo $course['last_active']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Наразі курсів немає.</p>
    <?php endif; ?>

    <p><a href="?delete_old=1" onclick="return confirm('Видалити курси, які неактивні більше 2 років?');">Видалити застарілі курси</a></p>
</body>
</html>