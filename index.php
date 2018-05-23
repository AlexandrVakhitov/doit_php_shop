<?
    require_once ('header.php');
//	error_reporting( E_ERROR );
	require_once('connect.php');
	
	/* Удаление Раздела в месте с его Товаром*/
    if (isset($_GET['del_razdel']) and is_numeric($_GET['del_razdel'])) {
		
		$sql = mysqli_query($conn, "DELETE FROM `razdel` WHERE id_razdel='" . $_GET['del_razdel'] . "'");
		$sql = mysqli_query($conn, "DELETE FROM `tovar`  WHERE id_razdel='" . $_GET['del_razdel'] . "'");
		
		echo '<script>location="./"</script>';
	}
	
	/* Удаление Товара */
    if (isset($_GET['del_tovar']) and is_numeric($_GET['del_tovar'])) {
		# mysqli_query () выполняет запрос к базе данных
		$sql = mysqli_query($conn, "DELETE FROM `tovar` WHERE id_tovar='" . $_GET['del_tovar'] . "'");
		
		echo '<script>location="./"</script>';
	}
	#=========================================
	
    /*Создание нового или редактирование Раздела*/
	if (isset($_GET['new_razdel']) || (isset($_GET['edit_razdel']) and is_numeric($_GET['edit_razdel']))) {
		
	    if (isset($_GET['edit_razdel'])) {
	    
			$submit = 'сохранить';
			$id = $_GET['edit_razdel'];
			$name = '';
			# Функция mysqli_fetch_assoc () используется для возврата ассоциативного массива
			if ($sql = mysqli_query($conn, "SELECT `name` FROM `razdel` WHERE id_razdel='" . $id . "'") and mysqli_fetch_assoc($sql) != '') {
				mysqli_data_seek($sql, 0);
				$r = mysqli_fetch_assoc($sql);
				$name = $r['name'];
			}
		}
		else {
	        $submit = 'добавить раздел';
			$id = 0;
			$name = '';
	    }
		?>
        <div class="col-6">
        <form method="post" action="">
            <input class="form-control form-control-sm mb-2" type="text" name="name" placeholder="<?= $name ?>">
            <button class="btn btn-primary btn-sm mb-2" type="submit"><?= $submit ?></button>
        </form>
        </div>
		<?
        if (isset($_POST["name"]) && $_POST["name"] != '' ) {
			if ($id == 0) {
				$sql = mysqli_query($conn, "INSERT INTO `razdel` (name) VALUES ('" . $_POST['name'] . "')");
			} else {
				$sql = mysqli_query($conn, "UPDATE `razdel` SET name = '" . $_POST['name'] . "' WHERE id_razdel='" . $id . "'");
			}
			echo '<script>location="./"</script>';
		}
		
	}
	#=============================================
	
    //товар: создание нового или редактирование
	if (isset($_GET['new_tovar']) or (isset($_GET['edit_tovar']) and is_numeric($_GET['edit_tovar']))) {
		if (isset($_GET['edit_tovar'])) {
		        $submit = 'сохранить';
			    $id = $_GET['edit_tovar'];
			    $name = '';
			    
			    if ($q = mysqli_query($conn, 'SELECT * FROM `tovar` WHERE id_tovar=' . $id) and mysqli_fetch_assoc($q) != '') {
				    mysqli_data_seek($q, 0);
				    $r = mysqli_fetch_assoc($q);
				    $name = $r['name'];
				    $id_razdel = $r['id_razdel'];
				    $price = $r['price'];
				    $text = $r['text'];
			    }
		} else {
			    $submit = 'добавить товар';
			    $id = 0;
			    $id_razdel = 0;
			    $name = 'введите название';
			    $price = 'введите цену';
			    $text = "введите описание";
		}
		//получаем список разделов
		$list = '';
		if ($query = mysqli_query($conn, 'SELECT * FROM razdel') and mysqli_fetch_assoc($query) != '') {
			mysqli_data_seek($query, 0);
			$list .= '<select class="form-control form-control-sm mb-2" name="id_razdel">';
			while ( $row = mysqli_fetch_assoc($query)) {
			        $list .= '<option value="' . $row['id_razdel'] . '" ' . ($id_razdel == $row['id_razdel'] ? "selected" : "") . '>' . $row['name'] . '</option>';
			}
			$list .= '</select>';
		}
		?>
        <div class="col-6">
            <form method="post" action="">
                <?= $list ?>
                <input class="form-control form-control-sm mb-2" type="text" name="name" placeholder="<?= $name ?>">
                <input class="form-control form-control-sm mb-2" type="text" name="price" placeholder="<?= $price ?>">
                <textarea class="form-control mb-2 py-3" type="text" name="text" aria-label="<?= $text ?>"></textarea>
                <button class="btn btn-dark mb-2" type="submit"><?= $submit ?></button>
            </form>
        </div>
		<?
        
        if (isset($_POST['name'])) {
			if ($id == 0) {
				$query = mysqli_query($conn, 'INSERT INTO tovar (`name`, `id_razdel`, `price`, `text`) VALUES (\'' . $_POST['name'] . '\', \'' . $_POST['id_razdel'] . '\', \'' . $_POST['price'] . '\', \'' . $_POST['text'] . '\')');
			} else {
				$query = mysqli_query($conn, 'UPDATE tovar SET `name`=\'' . $_POST['name'] . '\', `id_razdel`=\'' . $_POST['id_razdel'] . '\', `price`=\'' . $_POST['price'] . '\', `text`=\'' . $_POST['text'] . '\' WHERE `id_tovar`=\'' . $id . '\'');
			}
			echo '<script>location="./"</script>';
		}
	}
	#============================
	
    //вывод раздела
	if (!isset($_GET['id_tovar'])) {
		if ($query = mysqli_query($conn, "SELECT * FROM `razdel`") and mysqli_fetch_assoc($query) != '') {
		    ?>
            <div class="col-6 my-3">
                <div class="block border p-3">
					<?
						mysqli_data_seek($query, 0);
						while ($row = mysqli_fetch_assoc($query)) {
							$id_razdel = $row['id_razdel'];
							?>
                            <div class="py-2 px-3 my-3 bg-info d-flex justify-content-between align-items-center">
                                <h5 class="m-0 text-white font-weight-light"><?= $row['name'] ?></h5>
                                <div class="">
                                    <a href="?edit_razdel=<?= $id_razdel ?>">&#9998;</a>
                                    <a class="text-white" href="?del_razdel=<?= $id_razdel ?>">&#215;</a>
                                </div>
                            </div>
							<?
							//Вывод товаров раздела
							if ($q = mysqli_query($conn, "SELECT `id_tovar`, `name`, `price` FROM `tovar` WHERE id_razdel='" . $id_razdel . "'") and mysqli_fetch_assoc($q) != '') {
								?>
                                <ul class="list-group">
									<?
										mysqli_data_seek($q, 0);
										while ($r = mysqli_fetch_assoc($q)) {
											?>
                                            <li class="list-group-item my-1 d-flex justify-content-between align-items-center">
                                                <div class="">
                                                    <a href="?id_tovar=<?= $r['id_tovar'] ?>"><?= $r['name'] ?></a>
                                                    - <?= $r['price'] ?> руб.
                                                </div>
                                                <div class="">
                                                    <a href="?edit_tovar=<?= $r['id_tovar'] ?>">&#9998;</a>
                                                    <a href="?del_tovar=<?= $r['id_tovar'] ?>">&#215;</a>
                                                </div>
                                            </li>
											<?
										}
									?>
                                </ul>
								<?
							}
						}
					?>

                </div>
            </div>
            <?
		}
		?>
        <div class="col-6">
            <div class="mt-2 d-flex justify-content-end">
                <a class="btn btn-sm btn-danger" href="./">В начало</a>
                <a class="btn btn-sm btn-danger ml-3" href="?new_razdel">Добавить раздел</a>
                <a class="btn btn-sm btn-danger ml-3" href="?new_tovar">Добавить товар</a>
            </div>
        </div>
        <?
		
	} else {
	    //Карточка товара
		if ($sql = mysqli_query($conn, "SELECT * FROM `tovar` WHERE id_tovar='" . $_GET['id_tovar'] . "'") and mysqli_fetch_assoc($sql) != '') {
			mysqli_data_seek($sql, 0);
			$row = mysqli_fetch_assoc($sql);
			?>
            <div class="col-8 offset-2">
                <h1><?=$row['name']?></h1>
                <p><?=$row['text']?></p>
                <b>Цена: </b><?=$row['price']?>руб. <br>
                <a href="./">Назад в каталог</a>
            </div>
            <?
			
		}
	}

require_once ('footer.php');
?>
