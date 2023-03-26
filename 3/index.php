
 
<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {



  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.

    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['fio']) || !preg_match('/^[А-Яа-яA-Za-z\n\r\s.,]*$/', $_POST['fio'])) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}

if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL )) {
  print('Заполните email.<br/>');
  $errors = TRUE;
}

if (empty($_POST['date']) || !preg_match('%[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]%', $_POST['date'])) {
  print('Заполните дату рождения.<br/>');
  $errors = TRUE;
}

if (empty($_POST['sex']) || !in_array($_POST['sex'], ['w','m'])) {
  print('Укажите пол.<br/>');
  $errors = TRUE;
}

if (empty($_POST['limbs']) || !in_array($_POST['limbs'], [1,2,3,4])) {
  print('Выберите количество конечностей.<br/>');
  $errors = TRUE;
}

if (empty($_POST['abilities'])) {
  print('Выберите хотя бы одну способность.<br/>');
  $errors = TRUE;
}
else{
  foreach ($_POST['abilities'] as $ability) {
    if(!in_array($ability, [1,2,3])){
      print('корректно выберите хотя бы одну способность.<br/>');
       $errors = TRUE;
    }
  }
}


if (empty($_POST['bio']) || !preg_match('/^[А-Яа-яA-Za-z\n\r\s.,]*$/', $_POST['bio'])) {
  print('Заполните биографию.<br/>');
  $errors = TRUE;
 }


if (empty($_POST['checkbox'])|| $_POST['checkbox']!=1) {
  print('Ознакомьтесь с условием контракта.<br/>');
  $errors = TRUE;
}


if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}


// Сохранение в базу данных.

$user = 'u52845';
$pass = '126720';
$db = new PDO('mysql:host=localhost;dbname=u52845', $user, $pass,
[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

// Подготовленный запрос. Не именованные метки.
try {

  $stmt = $db->prepare("INSERT INTO application (name,email,date,sex,limbs,bio,checkbox) VALUES 
  (?,?,?,?,?,?,?)");
  $stmt -> execute([$_POST['fio'], $_POST['email'], $_POST['date'], $_POST['sex'], $_POST['limbs'], $_POST['bio'], $_POST['checkbox']]);
  $id = $db->lastInsertId();
  $stmt = $db->prepare("INSERT INTO app_ability (id_app, id_ab) VALUES (?,?)");
    foreach ($_POST['abilities'] as $ability) {
          $stmt->execute([$id, $ability]);
        }



 
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}


// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
