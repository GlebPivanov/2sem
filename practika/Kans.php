<?php

include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $db->prepare("SELECT id, SotrudnikID, ZatratId, DepartmentID, date FROM Kans");
        $params = [];

        if (!empty($_COOKIE['datex'])) {
            $stmt_sql = isset($stmt_sql) ? $stmt_sql." AND date = ?" : "date = ?";
            $params[] = $_COOKIE['datex'];
        }

        if (!empty($_COOKIE['zatrats'])) {
            $filter_zatrat_ids = unserialize($_COOKIE['zatrats']);
            $in_values1 = implode(',', array_fill(0, count($filter_zatrat_ids), '?'));
            $stmt_sql = isset($stmt_sql) ? $stmt_sql." AND ZatratId IN ($in_values1)" : "ZatratId IN ($in_values1)";
            $params = array_merge($params, $filter_zatrat_ids);
        }

        if (!empty($_COOKIE['departments'])) {
            $filter_department_ids = unserialize($_COOKIE['departments']);
            $in_values2 = implode(',', array_fill(0, count($filter_department_ids), '?'));
            $stmt_sql = isset($stmt_sql) ? $stmt_sql." AND DepartmentID IN ($in_values2)" : "DepartmentID IN ($in_values2)";
            $params = array_merge($params, $filter_department_ids);
        }

        if (isset($stmt_sql)) {
            $stmt_sql = "SELECT id, SotrudnikID, ZatratId, DepartmentID, date FROM Kans WHERE ".$stmt_sql;
            $stmt = $db->prepare($stmt_sql);
            $stmt->execute($params);
            $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt->execute();
            $values = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("SELECT id FROM Zatrats");
            $stmt->execute();
            $s_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filter_zatrat_ids = [];
            foreach ($s_ids as $s_id) {
                $filter_zatrat_ids[] = $s_id['id'];
            }

            $stmt = $db->prepare("SELECT id FROM Departments");
            $stmt->execute();
            $s_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filter_department_ids = [];
            foreach ($s_ids as $s_id) {
                $filter_department_ids[] = $s_id['id'];
            }
        }
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
    $new = array();
    $new['SotrudnikID'] = empty($_COOKIE['SotrudnikID']) ? '' : $_COOKIE['SotrudnikID'];
    $new['ZatratId'] = empty($_COOKIE['ZatratId']) ? '' : $_COOKIE['ZatratId'];
    $new['DepartmentID'] = empty($_COOKIE['DepartmentID']) ? '' : $_COOKIE['DepartmentID'];
    $new['date'] = empty($_COOKIE['date']) ? '' : $_COOKIE['date'];
    include('vids/Kans.php');
} else {
    $errors = array();
    $messages = array();
    if (!empty($_POST['addnewdate'])) {

        if (empty($_POST['SotrudnikID'])) {
            $errors['SSotrudnikID'] = 'Заполните поле "SotrudnikID"';
        } else {
            setcookie('SotrudnikID', $_POST['SotrudnikID'], time() + 24 * 60 * 60);
        }

        if (empty($_POST['ZatratId'])) {
            $errors['ZatratId'] = 'Заполните поле "ZatratId"';
        } else {
            setcookie('ZatratId', $_POST['ZatratId'], time() + 24 * 60 * 60);
        }

        if (empty($_POST['DepartmentID'])) {
            $errors['DepartmentID'] = 'Заполните поле "DepartmentID"';
        } else {
            setcookie('DepartmentID', $_POST['DepartmentID'], time() + 24 * 60 * 60);
        }

        if (empty($_POST['date'])) {
            $errors['date'] = 'Заполните поле "date"';
        } else {
            setcookie('date', $_POST['date'], time() + 24 * 60 * 60);
        }

        if (empty($errors)) {
            $SotrudnikID = $_POST['SotrudnikID'];
            $ZatratId = $_POST['ZatratId'];
            $DepartmentID = $_POST['DepartmentID'];
            $date = $_POST['date'];

            $stmt = $db->prepare("INSERT INTO Kans (SotrudnikID, ZatratId, DepartmentID, date) 
                VALUES (?, ?, ?, ?)");
            $stmt->execute([$SotrudnikID, $ZatratId, $DepartmentID, $date]);
            $messages['added'] = 'Данные успешно добавлены';
            setcookie('SotrudnikID', '', time() + 24 * 60 * 60);
            setcookie('ZatratId', '', time() + 24 * 60 * 60);
            setcookie('DepartmentID', '', time() + 24 * 60 * 60);
            setcookie('date', '', time() + 24 * 60 * 60);
        }
    } 
    foreach ($_POST as $key => $value) {
        if (preg_match('/^clear(\d+)_x$/', $key, $matches)) {
            $id = $matches[1]; 
            $stmt = $db->prepare("DELETE FROM Kans WHERE id = ?");
            $stmt->execute([$id]);
            $messages['deleted'] = 'Запись с <b>id = '.$id.'</b> успешно удалена';
        }
        if (preg_match('/^edit(\d+)_x$/', $key, $matches)) {
            $id = $matches[1];
            setcookie('edit', $id, time() + 24 * 60 * 60);
        }
        if (preg_match('/^save(\d+)_x$/', $key, $matches)) {
            setcookie('edit', '', time() + 24 * 60 * 60);
            $id = $matches[1];
            $stmt = $db->prepare("SELECT SotrudnikID, ZatratId, DepartmentID, date FROM Kans WHERE id = ?");
            $stmt->execute([$id]);
            $old_dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dates['SotrudnikID'] = $_POST['SotrudnikID' . $id];
            $dates['ZatratId'] = $_POST['ZatratId' . $id];
            $dates['DepartmentID'] = $_POST['DepartmentID' . $id];
            $dates['date'] = $_POST['date' . $id];

            if (array_diff_assoc($dates, $old_dates[0])) {
                $stmt = $db->prepare("UPDATE Kans SET SotrudnikID = ?, ZatratId = ?, DepartmentID = ?, date = ? WHERE id = ?");
                $stmt->execute([$dates['SotrudnikID'], $dates['ZatratId'], $dates['DepartmentID'], $dates['date'], $id]);
                $messages['edited'] = 'Запись с <b>id = '.$id.'</b> успешно обновлена';
            }
        }
    }
    
    if (!empty($_POST['resetall'])) {
        setcookie('datex', '');
        setcookie('zatrats', '');
        setcookie('departments', '');
    }

    if (!empty($_POST['filter'])) {

        if (!empty($_POST['date']))
            setcookie('datex', $_POST['date']);

        $filter_zatrat_ids = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'filter_zatrat_') !== false) {
                $id = substr($key, 13);
                $filter_zatrat_ids[] = $id;
            }
        }
        setcookie('zatrats', serialize($filter_zatrat_ids));

        $filter_department_ids = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'filter_department_') !== false) {
                $id = substr($key, 15);
                $filter_department_ids[] = $id;
            }
        }
        setcookie('departments', serialize($filter_department_ids));
        
    }

    if (!empty($messages)) {
        setcookie('messages', serialize($messages), time() + 24 * 60 * 60);
    }
    if (!empty($errors)) {
        setcookie('errors', serialize($errors), time() + 24 * 60 * 60);
    }
    header('Location: Kans.php');
}