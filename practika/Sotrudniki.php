<?php

include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $db->prepare("SELECT id, name, age, region FROM Sotrudniki");
        $stmt->execute();
        $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }


    try {
        $stmt = $db->prepare("SELECT id, name, age, region FROM Sotrudniki");
        $params = [];

        if (!empty($_COOKIE['ages'])) {
            $filter_age_ids = unserialize($_COOKIE['ages']);
            $in_values1 = implode(',', array_fill(0, count($filter_age_ids), '?'));
            $stmt_sql = isset($stmt_sql) ? $stmt_sql." AND age IN ($in_values1)" : "age IN ($in_values1)";
            $params = array_merge($params, $filter_age_ids);
        }

        if (!empty($_COOKIE['regions'])) {
            $filter_region_ids = unserialize($_COOKIE['regions']);
            $in_values2 = implode(',', array_fill(0, count($filter_region_ids), '?'));
            $stmt_sql = isset($stmt_sql) ? $stmt_sql." AND region IN ($in_values2)" : "region IN ($in_values2)";
            $params = array_merge($params, $filter_region_ids);
        }

        if (isset($stmt_sql)) {
            $stmt_sql = "SELECT id, name, age, region FROM Sotrudniki WHERE ".$stmt_sql;
            $stmt = $db->prepare($stmt_sql);
            $stmt->execute($params);
            $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt->execute();
            $values = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("SELECT age FROM Sotrudniki");
            $stmt->execute();
            $a_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filter_age_ids = [];
            foreach ($a_ids as $a_id) {
                $filter_age_ids[] = $a_id['age'];
            }

            $stmt = $db->prepare("SELECT region FROM Sotrudniki");
            $stmt->execute();
            $c_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filter_region_ids = [];
            foreach ($c_ids as $c_id) {
                $filter_region_ids[] = $c_id['region'];
            }
        }
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }





    $new = array();
    $new['name'] = empty($_COOKIE['name']) ? '' : $_COOKIE['name'];
    $new['age'] = empty($_COOKIE['age']) ? '' : $_COOKIE['age'];
    $new['region'] = empty($_COOKIE['region']) ? '' : $_COOKIE['region'];
    include('vids/Sotrudniki.php');
} else {
    $errors = array();
    $messages = array();
    if (!empty($_POST['addnewdate'])) {
        if (empty($_POST['name'])) {
            $errors['name1'] = 'Заполните поле "Имя сотрудника"';
            setcookie('name', '', time() + 24 * 60 * 60);
        } else if (!preg_match('/^[\p{L}\p{M}\s.]+$/u', $_POST['name'])) {
            $errors['name2'] = 'Некорректно заполнено поле "Имя сотрудника"';
            setcookie('name', $_POST['name'], time() + 24 * 60 * 60);
        } else {
            setcookie('name', $_POST['name'], time() + 24 * 60 * 60);
        }

        if (empty($_POST['age'])) {
            $errors['age1'] = 'Заполните поле "Возраст сотрудника"';
            setcookie('age', '', time() + 24 * 60 * 60);
        } else if (!is_numeric($_POST['age'])) {
            $errors['age2'] = 'Некорректно заполнено поле "Возраст сотрудника"';
            setcookie('age', $_POST['age'], time() + 24 * 60 * 60);
        } else {
            setcookie('age', $_POST['age'], time() + 24 * 60 * 60);
        }

        if (empty($_POST['region'])) {
            $errors['region1'] = 'Заполните поле "Регион"';
            setcookie('region', '', time() + 24 * 60 * 60);
        } else if (!preg_match('/^[\p{L}\p{M}\s.]+$/u', $_POST['region'])) {
            $errors['region2'] = 'Некорректно заполнено поле "Регион"';
            setcookie('region', $_POST['region'], time() + 24 * 60 * 60);
        } else {
            setcookie('region', $_POST['region'], time() + 24 * 60 * 60);
        }
        
        if (empty($errors)) {
            $name = $_POST['name'];
            $age = intval($_POST['age']);
            $region = $_POST['region'];
            $stmt = $db->prepare("INSERT INTO Sotrudniki (name, age, region) VALUES (?, ?, ?)");
            $stmt->execute([$name, $age, $region]);
            $messages['added'] = 'Сотрудник "'.$name.'" успешно добавлен';
            setcookie('name', '', time() + 24 * 60 * 60);
            setcookie('age', '', time() + 24 * 60 * 60);
            setcookie('region', '', time() + 24 * 60 * 60);
        }
    } 
    foreach ($_POST as $key => $value) {
        if (preg_match('/^clear(\d+)_x$/', $key, $matches)) {
            $id = $matches[1]; 
            $stmt = $db->prepare("SELECT id FROM Kans WHERE SotrudnikID = ?");
            $stmt->execute([$id]);
            $empty = $stmt->rowCount() === 0;
            if (!$empty) {
                $errors['delete'] = 'Поле с <b>id = '.$id.'</b> невозможно удалить, т.к. оно связанно с журналом учёта расхода канцтоваров';
            } else {
                $stmt = $db->prepare("DELETE FROM Sotrudniki WHERE id = ?");
                $stmt->execute([$id]);
                $messages['deleted'] = 'Сотруник с <b>id = '.$id.'</b> успешно удалён';
            }
        }
        if (preg_match('/^edit(\d+)_x$/', $key, $matches)) {
            $id = $matches[1];
            setcookie('edit', $id, time() + 24 * 60 * 60);
        }
        if (preg_match('/^save(\d+)_x$/', $key, $matches)) {
            setcookie('edit', '', time() + 24 * 60 * 60);
            $id = $matches[1];
            $stmt = $db->prepare("SELECT name, age, region FROM Sotrudniki WHERE id = ?");
            $stmt->execute([$id]);
            $old_dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dates['name'] = $_POST['name' . $id];
            $dates['age'] = $_POST['age' . $id];
            $dates['region'] = $_POST['region' . $id];

            if (array_diff_assoc($dates, $old_dates[0])) {
                $stmt = $db->prepare("UPDATE Sotrudniki SET name = ?, age = ?, region = ? WHERE id = ?");
                $stmt->execute([$dates['name'], $dates['age'], $dates['region'], $id]);
                $messages['edited'] = 'Сотрудник с <b>id = '.$id.'</b> успешно обновлён';
            }
        }
    }

    if (!empty($_POST['resetall'])) {
        setcookie('ages', '');
        setcookie('regions', '');
    }

    if (!empty($_POST['filter'])) {

        $filter_age_ids = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'filter_age_') !== false) {
                $id = substr($key, 11);
                $filter_age_ids[] = $id;
            }
        }
        setcookie('ages', serialize($filter_age_ids));

        $filter_region_ids = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'filter_region_') !== false) {
                $id = substr($key, 15);
                $filter_region_ids[] = $id;
            }
        }
        setcookie('regions', serialize($filter_region_ids));
        
    }

    if (!empty($messages)) {
        setcookie('messages', serialize($messages), time() + 24 * 60 * 60);
    }
    if (!empty($errors)) {
        setcookie('errors', serialize($errors), time() + 24 * 60 * 60);
    }
    header('Location: Sotrudniki.php');
}
