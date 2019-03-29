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
        <div class="container admin-main">
            <div class="row">
                <h1><strong> Lstes des items </strong></h1> <div><a href="insert.php" class="btn btn-success ajouter" role="button"><i class="fa fa-plus"></i> Ajouter</a></div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr> <!--ligne-->
                            <th>Nom</th>
                            <th>Description</th> <!--colone-->
                            <th>Prix</th>
                            <th>Categorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            require 'database.php';
                            $db = Database::connect(); //retourne la connection dans la var
                            $statement = $db->query('SELECT items.id, items.name, items.description, items.price, categories.name AS category
                                                     FROM items LEFT JOIN categories ON items.category = categories.id
                                                     ORDER BY items.id DESC
                                                   '); //recup

                                //affich
                            while($data = $statement->fetch())
                                {
                                    echo '<tr>';

                                        echo '<td>'.$data['name'].'</td>';
                                        echo '<td>'.$data['description'].'</td>';
                                        echo '<td>'.number_format((float)$data['price'],2, '.', '').'</td>';
                                        echo '<td>'.$data['category'].'</td>'; 
                                        echo '<td width=360>';
                                              echo '<a href="view.php?id=' .$data['id']. '" class="btn btn-secondary mr-2 mb-1"><i class="fa fa-eye"></i> Voir</a>';
                                              echo '<a href="update.php?id=' .$data['id']. '" class="btn btn-primary mr-2 mb-1"><i class="fa fa-pen"></i> Modifier</a>';
                                              echo '<a href="delete.php?id=' .$data['id']. '" class="btn btn-danger mr-2 mb-1"><i class="fa fa-window-close"></i> Supprimer</a>';
                                        echo '</td>';  

                                    echo '</tr>';
                                }
                            Database::disconnect();
                        ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </body>
</html>