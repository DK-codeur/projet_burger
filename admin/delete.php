<?php  
    require 'database.php';

    if(!empty($_GET['id']))
        {
            $id = checkInput($_GET['id']);
        }

    if(!empty($_POST))
        {
            $id = checkInput($_POST['id']);
            $db = Database::connect();
            $statement = $db->prepare('DELETE FROM items WHERE id = ?');
            $statement->execute(array($id));
            Database::disconnect();
            header('location: index.php');
        }
        /*function */
    function checkInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
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
                <h1><strong>Supprimer un item</strong></h1><br/>
                <form class="form" role="form" method="POST" action="delete.php">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <p class="alert alert-warning">Êtes vous sur de vouloir supprimer ?</p>
                    <div class="form-action">
                        <button type="submit" class="btn btn-warning"></i>Oui</button>
                        <a href="index.php" class="btn btn-light m-2">Non</a>
                    </div>
                </form>               
            </div>
        </div>
    </body>
</html>