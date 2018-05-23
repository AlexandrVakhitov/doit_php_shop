<?
require_once ('connect.php');

//удаление раздела
function del_razdel(){
	global $sql,$conn;
	if(isset($_GET['del_razdel']) and is_numeric($_GET['del_razdel'])) {

		$sql = mysqli_query($conn, "DELETE FROM `razdel` WHERE id_razdel='".$_GET['del_razdel']."'");
		$sql = mysqli_query($conn, "DELETE FROM `tovar`  WHERE id_razdel='".$_GET['del_razdel']."'");

		echo '<script>location="./"</script>';
	}

}
//удаление товара
function del_tovar(){
	global $sql,$conn;
	if(isset($_GET['del_tovar']) and is_numeric($_GET['del_tovar'])) {

		$sql = mysqli_query($conn, "DELETE FROM `tovar` WHERE id_razdel='".$_GET['del_tovar']."'");

		echo '<script>location="./"</script>';
	}

}

//Создание нового или редактирование раздела
//конец - раздел: создание нового или редактирование
function new_razdel(){
	global $sql,$conn;
	if (isset($_GET['new_razdel']) or (isset($_GET['edit_razdel']) and is_numeric($_GET['edit_razdel']))) {



		if(isset($_GET['edit_razdel'])) {
			$submit = 'сохранить';
			$id = $_GET['edit_razdel'];
			$name = '';
			if($sql = mysqli_query($conn, "SELECT `name` FROM `razdel` WHERE id_razdel='".$id."'") and mysqli_fetch_assoc($sql)!='') {
				mysqli_data_seek($sql, 0);
				$r = mysqli_fetch_assoc($sql);
				$name = $r['name'];
			}
		} else {
			$submit = 'добавить раздел';
			$id = 0;
			$name = '';
		}
		?>
        <form method="post" action="">
            <input type=text name=name placeholder="<?=$name?>"><br>
            <input type="submit" value="<?=$submit?>">
        </form>
		<?


		if($_POST["name"]!='') {
			if($id == 0) {
				$sql = mysqli_query($conn, "INSERT INTO `razdel` (name) VALUES ('".$_POST['name']."')");
			} else {
				$sql = mysqli_query($conn, "UPDATE `razdel` SET name = '".$_POST['name']."' WHERE id_razdel='".$id."'");
			}
			echo '<script>location="./"</script>';
		}

	}

}


//товар: создание нового или редактирование
//конец - товар: создание нового или редактирование
function new_tovar(){
	global $sql,$conn;
	if (isset($_GET['new_tovar']) or (isset($_GET['edit_tovar']) and is_numeric($_GET['edit_tovar']))) {
		if($_GET['edit_tovar']) {
			$submit = 'сохранить';
			$id = $_GET['edit_tovar'];
			$name = '';
			if($q = mysqli_query($conn, 'SELECT * FROM tovar WHERE id_tovar='.$id) and mysqli_fetch_assoc($q)!='') {
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
		if($query=mysqli_query($conn, 'SELECT * FROM razdel') and mysqli_fetch_assoc($query)!='') {
			mysqli_data_seek($query, 0);
			$list .='<select name="id_razdel">';
			while ($row = mysqli_fetch_assoc($query)) {
				$list .='<option value="'.$row['id_razdel'].'" '.($id_razdel==$row['id_razdel']?"selected":"").'>'.$row['name'].'</option>';
			}
			$list.='</select>';
		}
		//конец - получаем список разделов

		?>
        <form method="post" action=""><?=$list?>
            <input type="text" name="name" placeholder="<?=$name?>">
            <br>
            <input type="text" name="text" placeholder="<?=$text?>">
            <br>
            <input type="text" name="price" placeholder="<?=$price?>">
            <br>
            <input type="submit" placeholder="<?=$submit?>">
        </form>
		<?

		if($_POST['name']!='') {
			if($id==0) {
				$query = mysqli_query($conn, 'INSERT INTO tovar (`name`, `id_razdel`, `price`, `text`) VALUES (\''.$_POST['name'].'\', \''.$_POST['id_razdel'].'\', \''.$_POST['price'].'\', \''.$_POST['text'].'\')');
			} else {
				$query = mysqli_query($conn, 'UPDATE tovar SET `name`=\''.$_POST['name'].'\', `id_razdel`=\''.$_POST['id_razdel'].'\', `price`=\''.$_POST['price'].'\', `text`=\''.$_POST['text'].'\' WHERE `id_tovar`=\''.$id.'\'');
			}
			echo '<script>location="./"</script>';
		}
	}

}


function newa( $out ){
	global $sql,$conn;
	if (!isset($_GET['id_tovar'])) {
		if($query = mysqli_query($conn,"SELECT * FROM `razdel`") and mysqli_fetch_assoc($query)!='') {
			mysqli_data_seek($query, 0);
			?><table class=""><?
			while ($row = mysqli_fetch_assoc($query)) {
				$id_razdel = $row['id_razdel'];
				?>
                <tr>
                    <td><?=$row['name']?></td>
                    <td>
                        <a href="?edit_razdel=<?=$id_razdel?>">&#9998;</a>
                    </td>
                    <td>
                        <a href="?del_razdel=<?=$id_razdel?>">&#215;</a>
                    </td>
                </tr>
				<?

				//вывод товаров раздела
				if($q = mysqli_query($conn,"SELECT `id_tovar`, `name`, `price` FROM `tovar` WHERE id_razdel='".$id_razdel."'") and mysqli_fetch_assoc($q)!='') {
					mysqli_data_seek($q, 0);
					while ($r = mysqli_fetch_assoc($q)) {
						?>
                        <tr>
                            <td>
                                <a href="?id_tovar=<?=$r['id_tovar']?>">
									<?=$r['name']?>
                                </a> - <?=$r['price']?> руб.
                            </td>
                            <td>
                                <a href="?edit_tovar=<?=$r['id_tovar']?>">&#9998;</a>
                            </td>
                            <td>
                                <a href="?del_tovar=<?=$r['id_tovar']?>">&#215;</a>
                            </td>
                        </tr>
						<?
					}

				}
			}
			?></table><?
		}


	}
	else {
		global $sql,$conn;
		//вывод карточки товара
		if($sql = mysqli_query($conn, "SELECT * FROM `tovar` WHERE id_tovar='".$_GET['id_tovar']."'") and mysqli_fetch_assoc($sql)!='') {
		    mysqli_data_seek($sql, 0);
			$row = mysqli_fetch_assoc($sql);
			$out .= '<h1>'.$row['name'].'</h1>';
			$out .= '<p>'.$row['text'].'</p>';
			$out .= '<p><b>Цена</b> '.$row['price'].' руб.</p>';
			//тут добавить остальные поля, оформить вывод карточки товара (добавить фотку?)
			$out .= '<p><a href="./	">Назад в каталог</a></p>';
			echo $out;
		}
	}
}

//вывод раздела
new_razdel();
?>

    <p><a href="./">В начало</a></p>
    <p><a href="?new_razdel">Добавить раздел</a></p>
    <p><a href="?new_tovar">Добавить товар</a></p>
<?


?>