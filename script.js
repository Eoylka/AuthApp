// Обработка отправки формы входа без перезагрузки страницы
document.getElementById('login-form').addEventListener('submit', function(event) {
  event.preventDefault();

  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;

  // Отправка данных формы на сервер для проверки авторизации
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'login.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
      if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
              // Перенаправление на страницу пользователя при успешной авторизации
              window.location.href = 'user.php';
          } else {
              // Вывод сообщения об ошибке при неправильном логине или пароле
              document.getElementById('error-message').textContent = response.message;
          }
      }
  };
  xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
});

// Анимационный блок
var successBlock = document.getElementById('success-block');
if (successBlock) {
  // Показать блок успешной авторизации
  successBlock.style.display = 'block';

  // Скрыть блок через 10 секунд
  setTimeout(function() {
      successBlock.style.display = 'none';
  }, 10000);
}
