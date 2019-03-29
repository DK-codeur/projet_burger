<?php

    require 'database.php';
        /*function */
    function checkInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

    if(!empty($_GET['id']))
        {
            $id = checkInput($_GET['id']);
        }

    $db = Database::connect();
    $statement = $db->prepare('SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category
                               FROM items LEFT JOIN categories ON items.category = categories.id
                               WHERE items.id = ?
                             ');

    $statement->execute(array($id));
    $item = $statement->fetch();
    Database::disconnect();  

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
                    <h1><strong>Voir un item</strong></h1><br/>
                    <form>
                        <div class="form-group">
                            <label>Nom:</label><?php echo '  '.$item['name']; ?>
                        </div>

                        <div class="form-group">
                            <label>Description:</label><?php echo '  '.$item['description']; ?>
                        </div>

                        <div class="form-group">
                            <label>Prix:</label><?php echo '  '.number_format((float)$item['price'],2, '.', '').' €'; ?>
                        </div>

                        <div class="form-group">
                            <label>Categorie:</label><?php echo '  '.$item['category']; ?>
                        </div>

                        <div class="form-group">
                            <label>Image:</label><?php echo '  '.$item['image']; ?>
                        </div>
                    </form>
                    <div class="form-action">
                        <a href="index.php" class="btn btn-primary m-2"><i class="fa fa-arrow-left"></i> Retour</a>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="img-thumbnail mb-3">
                        <img src="<?php echo '../images/'.$item['image']; ?>" />
                    <div class="price"><?php echo number_format((float)$item['price'],2, '.', '').' €'; ?></div>
                    <div class="caption">
                        <h4><?php echo $item['name']; ?></h4>
                        <p><?php echo $item['description']; ?></p>
                        <a href="#" class="btn btn-success commander" role="button"><i class="fa fa-shopping-bag"> Commander</i></a>
                    </div>
                </div>
                </div>               
            </div>
        </div>
    </body>
</html>