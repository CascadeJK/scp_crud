<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create a record</title>
        <!-- Link to Google Fonts for custom font styles -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Baumans&family=Poppins:wght@600&display=swap" rel="stylesheet">
        <!-- Link to Bootstrap CSS for styling -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Link to my CSS file -->
        <link rel="stylesheet" href="styles/mystyle.css">  
    </head>
    
    <body>
        <section id="homepage-image">
          
            <?php
              
                include "connection.php";
                
                if(isset($_POST['submit']))
                {
                    // Write a prepare statemnet to insert data
                    $insert = $connection->prepare("insert into scp_database(subject, class, description, containment, image) values(?,?,?,?,?)");
                
                    $insert->bind_param("sssss",$_POST['subject'], $_POST['class'], $_POST['description'], $_POST['containment'], $_POST['image']);
                    
                    if($insert->execute())
                {
                    echo "<div class='alert alert-success'>Record added successfully</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger'>Error: {$insert->error}</div>";
                }
                }
                
            ?>
              
              
            <h1>Create a new record</h1>
            
            <p><a href="index.php" class="back btn btn-danger">Back to index page.</a></p>
            
            <form method="post" action="create.php" class="form form-group">
                <label>Enter SCP:</label>
                <br>
                <input type="text" name="subject" placeholdoer="Subject..." class="form-control" required>
                <br><br>
                
                <label>Enter Class:</label>
                <br>
                <input type="text" name="class" placeholdoer="Class..." class="form-control" required>
                <br><br>
                
                <label>Enter SCP Description:</label>
                <br>
                <textarea name="description" class="form-control" required>Enter Description...</textarea>
                <br><br>
                
                <label>Enter SCP Containment:</label>
                <br>
                <textarea name="containment" class="form-control" required>Enter Containment...</textarea>
                <br><br>
                
                <label>Enter Image:</label>
                <br>
                <input type="text" name="image" placeholdoer="images/name_of_image.png" class="form-control">
                <br><br>
                
                <input type="submit" name="submit" class="btn btn-danger">
                
            </form>
            
        </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>