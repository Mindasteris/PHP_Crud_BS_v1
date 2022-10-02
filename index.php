<?php
    require_once ('inc/db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="crud.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="Bootstrap/bootstrap.min.css">
    <title>PHP Crud App</title>
</head>

<body>
    <h1 class="bg-dark text-light text-center py-2">PHP CRUD</h1>

    <!-- Form Validation -->
    <?php
        // Check for submit data
        if(isset($_POST['send'])) {
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
                $query = $conn->prepare("INSERT INTO users(name, email, fav_sport, fav_number) VALUES(?,?,?,?)");
                $query->bind_param("sssi", $name, $email, $fav_sport, $fav_number);
                $query->execute();

            if($query) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Data was successfully added.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else {
                die("QUERY FAILED: " . $conn->connect_error);
            }

            $query->close();
            // $conn->close();
            }
        
        }
    ?>

    <!-- Form -->
    <div class="container mt-5">
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input class="form-control" type="text" name="name" placeholder="Please enter your name"
                    autocomplete="off">
                <label for="email">Email:</label>
                <input class="form-control" type="email" name="email" placeholder="Please enter your email address"
                    autocomplete="off">
                <label for="fav_sport">Favorite Sport:</label>
                <input class="form-control" type="text" name="fav_sport" placeholder="Enter your favorite sport"
                    autocomplete="off">
                <label for="fav_number">Favorite Number:</label>
                <input class="form-control" type="number" name="fav_number" placeholder="Your favorite number"
                    autocomplete="off">
            </div>
            <div class="form-group mt-3">
                <input class="form-control btn btn-success" type="submit" name="send" value="Post">
            </div>
        </form>
    </div>

    <!-- Table for data -->
    <div class="container">
        <table class="table table-bordered table-hover text-center mt-5">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Favorite Sport</th>
                    <th>Favorite Number</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP for show data in table -->
                <?php
                    $query = "SELECT * FROM users";
                    $result = $conn->query($query);  
                    while($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['fav_sport'] . "</td>";
                        echo "<td>" . $row['fav_number'] . "</td>";
                        echo "<td><a title='Edit' href='edit.php?edit=$id'><i class='fa-solid fa-pen-to-square'></i></a></td>";
                        echo "<td><a title='Delete' class='text-danger' href='?delete_id=$id'><i class='fa-solid fa-trash'></i></a></td>";
                        echo "</tr>";
                    }
                    $result->close();
                    // $conn->close();
                    
                    // Delete user row
                    if(isset($_GET['delete_id'])) {
                        $delete_id = $_GET['delete_id'];
                        $query = "DELETE FROM users WHERE id = ?";
                        $prepare_stmt = $conn->prepare($query);
                        $prepare_stmt->bind_param("i", $delete_id);
                        $prepare_stmt->execute();
                        
                        $prepare_stmt->close();
                        $conn->close();
                        
                        // Redirect
                        header("location: index.php");
                    }                   
                ?>
            </tbody>
        </table>
    </div>

    <script src="Bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>