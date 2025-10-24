<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список пользователей</title>
</head>

<body>
    <h1>
        Список пользователей
    </h1>
    <?php if ($users): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <?= htmlspecialchars($user->name); ?> — <?= htmlspecialchars($user->email); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>
            Нет пользователей
        </p>
    <?php endif; ?>
</body>

</html>
