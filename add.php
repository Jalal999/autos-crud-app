<?php 
    session_start();

    if (!isset($_SESSION['email'])){
        die("ACCESS DENIED");
    }

    if ( isset($_POST['cancel'] ) ) {
        // Redirect the browser to index.php
        header("Location: index.php");
        return;
    }

    $pdo = new PDO('mysql:host=localhost; port=8889; dbname=crud', 'jalal', 'jalal');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    
    $added = false;

    if( isset($_POST['make']) &&  isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])){
        if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
            $_SESSION['error'] = "All fields are required";
            header('Location: add.php');
            return;
        }
        else if(!is_numeric($_POST['mileage']) || !is_numeric($_POST['year'])){
            $_SESSION['error'] = "Mileage and year must be numeric";
            header('Location: add.php');
            return;
        } else {
            $stmt = $pdo->prepare('INSERT INTO autos
                                    (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
            $stmt->execute(array(
                                ':mk' => $_POST['make'],
                                ':md' => $_POST['model'],
                                ':yr' => $_POST['year'],
                                ':mi' => $_POST['mileage'])
                            );
            $_SESSION['success'] = "Record added";
            header('Location: index.php');
            return;
        }
    }

 ?>

 <html>
 <head>
    <title>Jalal Mammadli's Auto Database</title>
 </head>
 <body>
    <div class="container">
    <?php
    echo "<h1>Tracking Autos for ".$_SESSION['email']."</h1>\n"; 
    if(isset($_SESSION['error'])){
        echo ('<p style="color:red">'.$_SESSION['error']."</p>\n");
        unset($_SESSION['error']);
    }


     ?>
     <form method="post">
        <p>Make: <input type="text" name="make"></p>
        <p>Model: <input type="text" name="model"></p>
        <p>Year: <input type="text" name="year"></p>
        <p>Mileage: <input type="text" name="mileage"></p>
        <input type="submit" value="Add">
        <input type="submit" value="Cancel" name="cancel">
     </form>

    </div>
 </body>
 </html>