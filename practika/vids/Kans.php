<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style.css">
    <link type="image/x-icon" href="images/logo.png" rel="shortcut icon">
    <link type="Image/x-icon" href="images/logo.png" rel="icon">
    <title>Company</title>
    <script>
    function toggleFilter() {
        var filterBlock = document.getElementById("filter-block");
        if (filterBlock.style.display === "none") {
            filterBlock.style.display = "block";
        } else {
            filterBlock.style.display = "none";
        }
    }

    var expanded = false;
    function showCheckboxes(checkboxesId) {
        var checkboxes = document.getElementById(checkboxesId);
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }
</script>
</head>
<body>
    <header>
        <div class="header-items">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="logo" width="37" height="37">
                <h1>Древо Индастрис</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="Sotrudniki.php">Список сотрудников</a></li>
                    <li><a href="Zatrats.php">Список видов затрат</a></li>
                    <li><a href="Departmentss.php">Список департаментов</a></li>
                    <li><a class="active" href="#">Журнал учёта расхода канцтоваров</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <?php
            if (!empty($_COOKIE['messages'])) {
                echo '<div class="messages">';
                $messages = unserialize($_COOKIE['messages']);
                foreach ($messages as $message) {
                    echo $message . '</br>';
                }
                echo '</div>';
                setcookie('messages', '', time() + 24 * 60 * 60);
            }
            if (!empty($_COOKIE['errors'])) {
                echo '<div class="errors">';
                $errors = unserialize($_COOKIE['errors']);
                foreach ($errors as $error) {
                    echo $error . '</br>';
                }
                echo '</div>';
                setcookie('errors', '', time() + 24 * 60 * 60);
            }
        ?>
        <form action="" method="POST">
            <div class="main-content">
                <h2>Журнал учёта расхода канцтоваров</h2>
            </div>
            <div class="main-content">
                <div class="top-table">
                    <div class="newdates">
                        <div class="newdates-item">
                            <label for="SotrudnikID">Имя сотрудника</label>
                        </div>
                        <div class="newdates-item">
                            <select name="SotrudnikID">
                                <?php
                                $stmt = $db->prepare("SELECT id, name FROM Sotrudniki");
                                $stmt->execute();
                                $Sotrudniki = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                print("<option selected disabled>выберите сотрудника</option>");
                                foreach ($Sotrudniki as $sotrudnik) {
                                    if (!empty($new['SotrudnikID']) && ($new['SotrudnikID'] ==  $sotrudnik['id'])) {
                                        printf('<option selected value="%d">%d. %s</option>', $sotrudnik['id'], $sotrudnik['id'], $sotrudnik['name']);
                                    } else {
                                        printf('<option value="%d">%d. %s</option>', $sotrudnik['id'], $sotrudnik['id'], $sotrudnik['name']);
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="newdates-item">
                            <label for="ZatratId">Вид затрат</label>
                        </div>
                        <div class="newdates-item">
                            <select name="ZatratId">
                                <?php
                                $stmt = $db->prepare("SELECT id, name FROM Zatrats");
                                $stmt->execute();
                                $Zatrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                print("<option selected disabled>выберите вид затрат</option>");
                                foreach ($Zatrats as $zatrat) {
                                    if (!empty($new['ZatratId']) && ($new['ZatratId'] ==  $zatrat['id'])) {
                                        printf('<option selected value="%d">%d. %s</option>', $zatrat['id'], $zatrat['id'], $zatrat['name']);
                                    } else {
                                        printf('<option value="%d">%d. %s</option>', $zatrat['id'], $zatrat['id'], $zatrat['name']);
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="newdates-item">
                            <label for="DepartmentID">Название департамента</label>
                        </div>
                        <div class="newdates-item">
                            <select name="DepartmentID">
                                <?php
                                $stmt = $db->prepare("SELECT id, name FROM Departments");
                                $stmt->execute();
                                $Departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                print("<option selected disabled>выберите департамент</option>");
                                foreach ($Departments as $department) {
                                    if (!empty($new['DepartmentID']) && ($new['DepartmentID'] ==  $department['id'])) {
                                        printf('<option selected value="%d">%d. %s</option>', $department['id'], $department['id'], $department['name']);
                                    } else {
                                        printf('<option value="%d">%d. %s</option>', $department['id'], $department['id'], $department['name']);
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="newdates-item">
                            <label for="date">Дата покупки</label>
                        </div>
                        <div class="newdates-item">
                            <input type="date" name="date" value=<?php print($new['date']); ?>>
                        </div>
                        <div class="newdates-item">
                            <input type="submit" name="addnewdate" value="Добавить">
                        </div>
                    </div>
                    <div id="filter-block" style="display:none;">
                        <h3>Фильтр</h3>
                        <input type="date" name="date" value="<?php echo isset($_COOKIE["datex"]) ? $_COOKIE["datex"] : ""?>">
                        </br></br>
                        <div class="row">
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckboxes('checkboxes1')">
                                    <select>
                                        <option>Вид затрат</option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes1">
                                    <?php
                                    $stmt = $db->prepare("SELECT id, name FROM Zatrats");
                                    $stmt->execute();
                                    $Zatrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($Zatrats as $zatrat) {
                                        echo '<label for="zatrat'.$zatrat['id'].'"><input type="checkbox" ';
                                        echo empty($filter_zatrat_ids) ? "" : (in_array($zatrat['id'], $filter_zatrat_ids) ? "checked " : "");
                                        echo 'name="filter_zatrat_'.$zatrat['id'].'" id="zatrat'.$zatrat['id'].'">'.$zatrat['name'].'</label>';
                                    }
                                    ?>
                                    <button type="button" id="checkAll1">Отменить всё</button>
                                </div>
                            </div>
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckboxes('checkboxes2')">
                                    <select>
                                        <option>Департамент</option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes2">
                                    <?php
                                    $stmt = $db->prepare("SELECT id, name FROM Departments");
                                    $stmt->execute();
                                    $Departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($Departments as $department) {
                                        echo '<label for="department'.$department['id'].'"><input type="checkbox" ';
                                        echo empty($filter_department_ids) ? "" : (in_array($department['id'], $filter_department_ids) ? "checked " : "");
                                        echo 'name="filter_department_'.$department['id'].'" id="department'.$department['id'].'">'.$department['name'].'</label>';
                                    }
                                    ?>
                                    <button type="button" id="checkAll2">Отменить всё</button>
                                </div>
                            </div>
                        </div>
                        </br></br>
                        <input type="submit" name="filter" value="Применить">
                        <input type="submit" name="resetall" value="Сбросить всё">
                    </div>     

                </div>
            </div>
            <div class="main-content">
            <?php
                echo    '<table>
                            <tr>
                                <th>Имя сотрудника</th>
                                <th>Вид затрат</th>
                                <th>Название департамента</th>
                                <th>Дата покупки</th>
                                <th colspan=2>
                                    <button type="button" onclick="toggleFilter()">
                                        <img src="https://cdn-icons-png.flaticon.com/512/107/107799.png" alt="filters" width="20" height="20">
                                    </button>
                                </th>
                            <tr>';
                foreach ($values as $value) {
                    echo    '<tr>';
                    echo        '<td>';
                                    $stmt = $db->prepare("SELECT id, name FROM Sotrudniki");
                                    $stmt->execute();
                                    $Sotrudniki = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo            '<select'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['id'])) print(" disabled ");
                    else print(" "); echo 'name="SotrudnikID'.$value['id'].'">';
                                        foreach ($Sotrudniki as $sotrudnik) {
                                            if ($sotrudnik['id'] == $value['SotrudnikID']) {
                                                printf('<option selected value="%d">%d. %s</option>', $sotrudnik['id'], $sotrudnik['id'], $sotrudnik['name']);
                                            } else {
                                                printf('<option value="%d">%d. %s</option>', $sotrudnik['id'], $sotrudnik['id'], $sotrudnik['name']);
                                            }
                                        }
                    echo            '</select>';
                    echo        '</td>';

                    echo        '<td>';
                                    $stmt = $db->prepare("SELECT id, name FROM Zatrats");
                                    $stmt->execute();
                                    $Zatrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo            '<select'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['id'])) print(" disabled ");
                    else print(" "); echo 'name="ZatratId'.$value['id'].'">';
                                        foreach ($Zatrats as $zatrat) {
                                            if ($zatrat['id'] == $value['ZatratId']) {
                                                printf('<option selected value="%d">%d. %s</option>', $zatrat['id'], $zatrat['id'], $zatrat['name']);
                                            } else {
                                                printf('<option value="%d">%d. %s</option>', $zatrat['id'], $zatrat['id'], $zatrat['name']);
                                            }
                                        }
                    echo            '</select>';
                    echo        '</td>';

                    echo        '<td>';
                                    $stmt = $db->prepare("SELECT id, name FROM Departments");
                                    $stmt->execute();
                                    $Departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo            '<select'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['id'])) print(" disabled ");
                    else print(" "); echo 'name="DepartmentID'.$value['id'].'">';
                                        foreach ($Departments as $department) {
                                            if ($department['id'] == $value['DepartmentID']) {
                                                printf('<option selected value="%d">%d. %s</option>', $department['id'], $department['id'], $department['name']);
                                            } else {
                                                printf('<option value="%d">%d. %s</option>', $department['id'], $department['id'], $department['name']);
                                            }
                                        }
                    echo            '</select>';
                    echo        '</td>';

                    echo        '<td> <input'; if(empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['id'])) print(" disabled ");
                                                else print(" "); echo 'type="date" name="date'.$value['id'].'" value="'.$value['date'].'"> 
                                </td>';

                if (empty($_COOKIE['edit']) || ($_COOKIE['edit'] != $value['id'])) {
                    echo        '<td> <input name="edit'.$value['id'].'" type="image" src="https://static.thenounproject.com/png/2185844-200.png" width="20" height="20" alt="submit"/> </td>';
                    echo        '<td> <input name="clear'.$value['id'].'" type="image" src="https://cdn-icons-png.flaticon.com/512/860/860829.png" width="20" height="20" alt="submit"/> </td>';
                } else {
                    echo        '<td colspan=2> <input name="save'.$value['id'].'" type="image" src="https://cdn-icons-png.flaticon.com/512/84/84138.png" width="20" height="20" alt="submit"/> </td>';
                }
                    echo    '</tr>';
                }
                echo '</table>';
            ?>
            </div>
        </form>
    </main>
<script>
    document.getElementById('checkAll1').addEventListener('click', 
        function() {
            var checkboxes = document.querySelectorAll('#checkboxes1 input[type=checkbox]');
            if (this.innerHTML === 'Выбрать все') {
                checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
                this.innerHTML = 'Отменить все';
            } else {
                checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
                this.innerHTML = 'Выбрать все';
            }
        });

    document.getElementById('checkAll2').addEventListener('click',
        function() {
            var checkboxes = document.querySelectorAll('#checkboxes2 input[type=checkbox]');
            if (this.innerHTML === 'Выбрать все') {
                checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
                this.innerHTML = 'Отменить все';
            } else {
                checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
                this.innerHTML = 'Выбрать все';
            }
        });

</script>
</body>
</html>
