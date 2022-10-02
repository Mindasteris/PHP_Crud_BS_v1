<?php
    require_once ('inc/db.php');

    // Get variable for link
    if(isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
    }

    // Edit data
    $query = "SELECT * FROM users WHERE id = $edit_id";
    $result = $conn->query($query);  
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $editName = $row['name'];
        $editEmail = $row['email'];
        $editSport = $row['fav_sport'];
        $editNumber = $row['fav_number'];
    }
    $result->close();
    //$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="crud.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="./Bootstrap/bootstrap.min.css">
    <title>PHP Crud App</title>
</head>

<body>
    <h1 class="bg-dark text-light text-center py-2">EDIT USER</h1>

    <!-- Form Validation -->
    <?php
        // Check for submit data
        if(isset($_POST['update'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $fav_sport = $_POST['fav_sport'];
            $fav_number=$_POST['fav_number'];
    
            // Empty fields
            if(empty($name) || empty($email) || empty($fav_sport) || empty($fav_number)) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> All fields are required. Please fill all required fields.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            // Favorite Number field
            else if($fav_number <= 0 || strlen((string)$fav_number) > 12) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> "Favorite number" cannot be less or equal to 0. 
                <strong>Additional:</strong> "Favorite number" cannot be longer than 12 characters long.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            // Check for inputs not allow numbers
            else if(preg_match("/[0-9]+/", $name) || preg_match("/[0-9]+/", $fav_sport)) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> "Name" and "Favorite Sport" fields cannot contain numbers. 
                Please use only letters.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else { // Excecute SQL
                $query = $conn->prepare("UPDATE users SET name = ?, email = ?, fav_sport = ?, fav_number = ? WHERE id = ?");
                $query->bind_param("sssii", $name, $email, $fav_sport, $fav_number, $edit_id);
                $query->execute();
                        
                if(!$query) {
                    die("QUERY FAILED: " . $conn->connect_error);
                }

                $query->close();
                $conn->close();
                header("location: index.php");
            }
    }
    ?>

    <!-- Form -->
    <div class="container mt-5">
        <form action="#" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input class="form-control" type="text" name="name" value="<?php echo $editName; ?>" autocomplete="off">
                <label for="email">Email:</label>
                <input class="form-control" type="email" name="email" value="<?php echo $editEmail; ?>"
                    autocomplete="off">
                <label for="fav_sport">Favorite Sport:</label>
                <input class="form-control" type="text" name="fav_sport" value="<?php echo $editSport; ?>"
                    autocomplete="off">
                <label for="fav_number">Favorite Number:</label>
                <input class="form-control" type="number" name="fav_number" value="<?php echo $editNumber; ?>"
                    autocomplete="off">
            </div>
            <div class="form-group">
                <input class="form-control btn btn-success mt-3" type="submit" name="update" value="Update">
                <a href="index.php"><button type="button" class="form-control btn btn-danger mt-3">Cancel</button></a>
            </div>
        </form>
    </div>

    <script src="Bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>