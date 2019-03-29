<?php  
    require 'database.php';

    if(!empty($_GET['id']))
        {
            $id = checkInput(($_GET['id']));
        }

    $nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";

        /*function */
    function checkInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

            /*condition */
    if(!empty($_POST))
        {
            $name           = checkInput($_POST['name']);
            $description    = checkInput($_POST['description']);
            $price          = checkInput($_POST['price']);
            $category       = checkInput($_POST['category']);
            $image          = checkInput($_FILES['image']['name']);
            $imagePath      = '../images/'.basename($image);
            $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

            $isSuccess = true;
            $isUploadSuccess = false;
        

            if(empty($name))
                {
                    $nameError = 'ce champ ne peut pas être vide';
                    $isSuccess = false;
                }

            if(empty($description))
                {
                    $descriptionError = 'ce champ ne peut pas être vide';
                    $isSuccess = false;
                }

            if(empty($price))
                {
                    $priceError = 'ce champ ne peut pas être vide';
                    $isSuccess = false;
                }

            if(empty($category))
                {
                    $categoryError = 'ce champ ne peut pas être vide';
                    $isSuccess = false;
                }

            if(empty($image))
                {
                    $isImageUpdated = false;
                }
            else
                {
                    $isImageUpdated = true;
                    $isUploadSuccess = true;

                    if($imageExtension != "png" && $imageExtension != "jpg" && $imageExtension !="jpeg" && $imageExtension != "gif")
                        {
                            $imageError = 'les formats d\'images autorisés sont .jpg .png .jpeg .gif';
                            $isUploadSuccess = false; 
                        }

                    if(file_exists($imagePath))
                        {
                            $imageError = 'le fichier existe deja';
                            $isUploadSuccess = false;
                        }

                    if($_FILES['image']['size'] > 500000)
                        {
                            $imageError = 'le fichier ne doit pas depasser les 500KB';
                            $isUploadSuccess = false;
                        }

                    if($isUploadSuccess)
                        {
                            if(!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath))
                                {
                                    $imageError =' il ya eu une erreure lors de l\'upload';
                                    $isUploadSuccess = false;
                                }
                        }
                }

        
            if($isSuccess && $isImageUpdated && $isUploadSuccess || ($isSuccess && !$isImageUpdated))
                {
                    $db = Database::connect();
                    if($isImageUpdated)
                        {
                            $statement = $db->prepare('UPDATE items 
                                                       SET name = ?, description = ?, price = ?, category = ?, image = ?
                                                       WHERE id = ?
                                                    ');
                            $statement->execute(array($name,$description,$price,$category,$image,$id));
                        }
                    else
                        {
                            $statement = $db->prepare('UPDATE items 
                                                    SET name = ?, description = ?, price = ?, category = ?
                                                    WHERE id = ?
                                                    ');
                            $statement->execute(array($name,$description,$price,$category,$id));
                        }
                    
                    Database::disconnect();
                    header('location: index.php');
                }
            else if($isImageUpdated && !$isUploadSuccess)
                {
                    $db = Database::connect();
                    $statement = $db->prepare('SELECT image FROM items WHERE id = ?');
                    $statement->execute(array($id));
                    $item = $statement->fetch();
                    $image = $item['image'];
                    Database::disconnect();
                }
        }

    else
        {
            $db = Database::connect();
            $statement = $db->prepare('SELECT * FROM items WHERE id = ?');
            $statement->execute(array($id));
            $item = $statement->fetch();
            
            $name        = $item['name'];
            $description = $item['description'];
            $price       = $item['price'];
            $category    = $item['category'];
            $image       = $item['image'];

            Database::disconnect();
        }

?>
    
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="../fontawesome-free-5.6.3-web/css/all.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <title>Mamy Burger Admin</title>
    </head>
    <body>
        <h1 class="text-logo"><i class="fa fa-birthday-cake"></i> Mamy Burger <i class="fa fa-birthday-cake"></i></h1> 
        <div class="container admin-view">
            <div class="row">
                <div class="col-sm-6">
                    <h1><strong>Modifier un item</strong></h1><br/>
                    <form class="form" role="form" method="POST" action="<?php echo 'update.php?id='.$id; ?>" enctype="multipart/form-dat">
                        <div class="form-group">
                            <label for="name">Nom:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                            <span class="help-inline"><?php echo $nameError; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>">
                            <span class="help-inline"><?php echo $descriptionError; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="price">Prix:</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $price; ?>">
                            <span class="help-inline"><?php echo $priceError; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="category">categorie:</label>
                            <select class="form-control" name="category" id="category">
                                <?php
                                    $db = Database::connect();
                                    foreach($db->query('SELECT * FROM categories') as $row)
                                        {
                                            if($row['id'] == $category)
                                                {
                                                    echo '<option selected = "selected" value="'.$row['id'].'">'.$row['name'].'<option>';
                                                }
                                            else
                                                {
                                                    echo '<option value="'.$row['id'].'">'.$row['name'].'<option>'; 
                                                }
                                        }
                                    Database::disconnect()
                                ?>
                            </select>
                            <span class="help-inline"><?php echo $categoryError; ?></span>
                        </div>

                        <div class="form-group">
                            <label>image: </label>
                            <p><?php echo $image; ?></p>
                            <label for="image">Selectionner une image:</label>
                            <input type="file" id="image" name="image">
                            <span class="help-inline"><?php echo $imageError; ?></span>
                        </div>
                        <div class="form-action">
                            <a href="index.php" class="btn btn-primary m-2"><i class="fa fa-arrow-left"></i> Retour</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-pen"></i>Modifier</button>
                        </div>
                    </form> 
                </div> 

                <div class="col-sm-6">
                    <div class="img-thumbnail mb-3">
                        <img src="<?php echo '../images/'.$image; ?>" />
                    <div class="price"><?php echo number_format((float)$price,2, '.', '').' €'; ?></div>
                    <div class="caption">
                        <h4><?php echo $name; ?></h4>
                        <p><?php echo $description; ?></p>
                        <a href="#" class="btn btn-success commander" role="button"><i class="fa fa-shopping-bag"> Commander</i></a>
                    </div>
                </div>              
            </div>
        </div>
    </body>
</html>