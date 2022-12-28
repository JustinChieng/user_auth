<?php 
    
    session_start();

    if ( !isset( $_SESSION['home_csrf_token'] ) ) {
        // generate csrf token
        $_SESSION['home_csrf_token'] = bin2hex( random_bytes(32) );
      }

    $database = new PDO(
        'mysql:host=localhost;dbname=user_auth',
        'root',
        'root'
    );


        // grap from SQL
        $query = $database->prepare('SELECT * FROM students');
        $query-> execute();
        $students_00 = $query->fetchAll();
    
        if(
            $_SERVER['REQUEST_METHOD'] === 'POST'){

         
      
            if ($_POST['action']==='add')
            {
                if ( $_POST['home_csrf_token'] !== $_SESSION['home_csrf_token'] )
                {
                  die("Nice try! But I'm smarter than you!");
                }
                unset( $_SESSION['home_csrf_token'] );
                
                //add 
                $statement = $database -> prepare(
                    "INSERT INTO students (`name`) 
                    VALUES (:name)"
                );
                $statement -> execute([
                    'name' => $_POST['students'],
                ]);
        
                header('Location:/');
                exit;

               
          

            }
    
            if ($_POST['action'] === 'delete')
            {
                //delete 
                $statement = $database->prepare(
                    'DELETE FROM students WHERE id = :id'
                );
    
                $statement->execute([
                    'id' => $_POST['students_id']
                ]);
    
                header('Location:/');
                exit;
            }
    
        }


?>
<!-- ============--------PHP----------============================================= -->


<!DOCTYPE html>
<html>

<head>
    <title>User Authentication System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="styleshee t" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
    body {
        background: #f1f1f1;
    }
    </style>
</head>

<body>
    <div class="card rounded shadow-sm mx-auto my-4" style="max-width: 500px;">
        <div class="card-body">
            <div>

                <div class="d-flex justify-content-between">
                    <div>
                        <h5>My classroom</h5>
                    </div>
                    <div>
                        <?php if (isset ($_SESSION['user'] ) ) : ?>
                        <a href="/logout" class="btn btn-link" id="logout">Logout</a>
                        <?php else: ?>
                        <a href="/login" class="btn btn-link" id="login">Login</a>
                        <a href="/signup" class="btn btn-link" id="signup">Sign Up</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- add -->
                <div>
                    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
                        class="mt-4 d-flex justify-content-between align-items-center">

                        <!-- if user login show,not login hide -->
                        <?php 
                            if (isset ($_SESSION['user'] )){
                                echo "
                                <input type='text' class='form-control' placeholder='Add new students...' name='students'
                                required />
                                <input type='hidden' name='action' value='add'>
                                <button class='btn btn-primary btn-sm rounded ms-2'>Add</button>
                                ";
                            }else {
                                echo " ";
                            }
                        ?>
                         <input 
                type="hidden"
                name="home_csrf_token"
                value="<?php echo $_SESSION['home_csrf_token']; ?>"
                />
                    </form>
                </div>
                <!-- add -->

            </div>
        </div>
    </div>

    <section>
        <div class="card rounded shadow-sm" style="max-width: 500px; margin: 60px auto;">
            <div class="card-body">
                <h3 class="card-title mb-3">Students</h3>

                <!-- delete -->
                <?php 
                // for loop +1
                $counter = 1;

                foreach ($students_00 as $students): ?>
                <div class="d-flex justify-content-between gap-3">
                    <!-- =============----form----================== -->
                    <div class=" d-flex justify-contect-center align-items-center">
                        <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

                            <input type="hidden" name="students_id" value="<?php echo $students ['id']; ?>">
                            <input type="hidden" name="action" value="update">

                            <!-- for loop counter ++ -->
                            <?php echo $counter++; echo ". "; echo $students['name'];  ?>

                        </form>
                    </div>
                    <!-- =============----form----================== -->

                    <!-- =============----Delete ----================== -->


                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <input type="hidden" name="students_id" value="<?php echo $students ['id']; ?>">
                        <input type="hidden" name="action" value="delete">

                        <!-- if user login show,not login hide -->
                        <?php 
                        if (isset ($_SESSION['user'] )){
                            echo "<button class='btn btn-danger mb-1'>Delete</button>";
                        }else{
                            echo " ";
                        }
                        
                        ?>
                       
                    </form>
                    <!-- =============----Delete ----================== -->
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>