<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Получаем данные из формы и защищаем от XSS атак
    $fio = htmlspecialchars($_POST["fio"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $comments = htmlspecialchars($_POST["comments"]);

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Неверный формат email.";
        exit;
    }

    // Валидация телефона
    if (!preg_match("/^[0-9\s\+\-\(\)]*$/", $phone)) {
        echo "Неверный формат телефона.";
        exit;
    }

    // Обработка файла
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Указываем папку для загрузки
        $uploadDir = 'uploads/';
        $uploadFilePath = $uploadDir . basename($fileName);

        // Проверка расширения файла
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf']; // Можете изменить в зависимости от допустимых типов
        if (!in_array($fileType, $allowedTypes)) {
            echo "Недопустимый тип файла.";
            exit;
        }

        // Перемещаем файл в целевую папку
        if (!move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            echo "Ошибка при загрузке файла.";
            exit;
        }
    }

    // Параметры письма
    $to = "techsteel@bk.ru";
    $subject = "Новое сообщение с сайта Техсталь";
    $message = "ФИО: $fio\nEmail: $email\nТелефон: $phone\nКомментарий: $comments";

    if (isset($uploadFilePath)) {
        $message .= "\nФайл: " . $uploadFilePath;
    }

    // Заголовки письма
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Отправка сообщения
    if (mail($to, $subject, $message, $headers)) {
        echo "Сообщение отправлено успешно.";
    } else {
        echo "Ошибка отправки сообщения.";
    }
}
?>
