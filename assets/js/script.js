function sendMessage() {
    const fio = document.getElementById('fio').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const comments = document.getElementById('comments').value;
    const file = document.getElementById('file').files[0]; // Обработка файла

    if (fio && email && phone) {
        const formData = new FormData();
        formData.append("fio", fio);
        formData.append("email", email);
        formData.append("phone", phone);
        formData.append("comments", comments);

        if (file) {
            formData.append("file", file); // Добавление файла в форму, если он выбран
        }

        fetch("send_email.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            closeMessageModal();
        })
        .catch(error => {
            alert("Ошибка отправки сообщения: " + error);
        });
    } else {
        alert("Пожалуйста, заполните все обязательные поля.");
    }
}
