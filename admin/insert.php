<?php  
    require 'database.php';
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
                    $imageError = 'ce champ ne peut pas être vide';
                    $isSuccess = false;
                }
            else
                {
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

        
        if($isSuccess && $isUploadSuccess)
            {
                $db = Database::connect();
                $statement = $db->prepare('INSERT INTO items(name, description, price, category, image ) VALUES(?,?,?,?,?)');
                $statement->execute(array($name,$description,$price,$category,$image));
                Database::disconnect();
                header('location: index.php');
            }

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
                <h1><strong>Ajouter un item</strong></h1><br/>
                <form class="form" role="form" method="POST" action="insert.php" enctype="multipart/form-dat">
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
                                        echo '<option value="'.$row['id'].'">'.$row['name'].'<option>';
                                    }
                                Database::disconnect()
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $categoryError; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="image">Selectionner une image:</label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php echo $imageError; ?></span>
                    </div>
                    <div class="form-action">
                        <a href="index.php" class="btn btn-primary m-2"><i class="fa fa-arrow-left"></i> Retour</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-pen"></i>Ajouter</button>
                    </div>
                </form>               
            </div>
        </div>
    </body>
</html>