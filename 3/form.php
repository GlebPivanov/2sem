<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>
  <link rel="stylesheet" href="style.css">
  <title>3</title>
</head>
<body>
  <div class="col col-10 col-md-6" id="forma">
        <form id="form" action="" method="POST">
            <div class="group">
                <label for="name">Имя:
                <input name="fio" id="name" class="form-control" placeholder="Введите ваше имя"></label>
            </div>
            <div class="group">
                <label for="email">E-mail:

                <input name="email" type="email" id="email" class="form-control" placeholder="Введите вашу почту">
                </label>
            </div>
            <div class="group">

                Дата рождения:
                <input name="date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" />

            </div>
            <div class="group">
                Пол:
                <label for="g1"><input type="radio" class="form-check-input" name="sex" id="g1" value="1">
                    Мужской</label>
                <label for="g2"><input type="radio" class="form-check-input" name="sex" id="g2" value="2">
                    Женский</label>
            </div>
            <div class="group">
                Количество конечностей:
                <label for="l1"><input type="radio" class="form-check-input" name="limbs" id="l1" value="1">
                    1</label>
                    <label for="l2"><input type="radio" class="form-check-input" name="limbs" id="l2" value="2">
                    2</label>
                <label for="l3"><input type="radio" class="form-check-input" name="limbs" id="l3" value="3">
                    3</label>
                <label for="l4"><input type="radio" class="form-check-input" name="limbs" id="l4" value="4">
                    4</label>

            </div>
            <div class="group">
                <label for="powers">Сверхспособности:
                <select class="form-control" name="abilities[]" id="powers" multiple="multiple">
                    <option value="1">Бессмертие</option>
                    <option value="2">Прохождение сквозь стены</option>
                    <option value="3">Левитация</option>
                </select></label>
            </div>


            <div class="group">
                <label for="bio">Биография:
                <textarea name="bio" id="bio" rows="3" class="form-control"></textarea></label>
            </div>
            <label><input type="checkbox" id="checkbox" value="1" name="checkbox">
                C контрактом ознакомлен(а) </label><br>
            <input type="submit" id="end" class="btn btn-primary" value="Отправить">
        </form>
  </div>
</body> 
</html>
