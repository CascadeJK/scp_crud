<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Update record</title>
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
            
                // Enable error reporting
                error_reporting(E_ALL);
         
                // Display errors
                ini_set('display_errors', 1);
                
                include "connection.php";
                
                // initialise $row as empty array
                $row = [];
                
                //directed from index page record [update] button
                if(isset($_GET['update']))
                {
                    $id = $_GET['update'];
                    // based on id select appropriate record from db
                    $recordID = $connection->prepare("select * from scp_database where id = ?");
                    
                    if(!$recordID)
                    {
                        echo "<div class='alert alert-danger p-3 m-2'>Error preparing recored for updating.</div>";
                        exit;
                    }
                    
                    $recordID->bind_param("i", $id);
                    
                    if($recordID->execute())
                    {
                        echo "<div class='alert alert-primary p-3 m-2'>Record ready for updating.</div>";
                        $temp = $recordID->get_result();
                        $row = $temp->fetch_assoc();
                    }
                    else
                    {
                        echo "<div class='alert alert-danger p-3 m-2'>Error: {$recordID->error}</div>";
                    }
                }
                
                
                if(isset($_POST['update']))
                {
                    // Write a prepare statemnet to insert data
                    $update = $connection->prepare("update scp_database set subject=?, class=?, description=?, containment=?, image=? where id=?");
                
                    $update->bind_param("sssssi",$_POST['subject'], $_POST['class'], $_POST['description'], $_POST['containment'], $_POST['image'], $_POST['id']);
                    
                    if($update->execute())
                {
                    echo "<div class='alert alert-success p-3 m-2'>Record updated successfully</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger p-3 m-2'>Error: {$update->error}</div>";
                }
                }
                
                
            ?>
              
              
            <h1>Update record</h1>
            
            <p><a href="index.php" class="back btn btn-danger">Baack to index page.</a></p>
            
            <form method="post" action="update.php" class=" form form-group">
                <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : '' ; ?>">
                <label>Scp Subject:</label>
                <br>
                <input type="text" name="subject" placeholdoer="Subject..." class="form-control" value="<?php echo isset($row['subject']) ? $row['subject'] : '' ; ?>">
                <br><br>
                
                <label>Class:</label>
                <br>
                <input type="text" name="class" placeholder="Class..." class="form-control"value="<?php echo isset($row['class']) ? $row['class'] : '' ; ?>">
                <br><br>
                
                <label>SCP Description:</label>
                <br>
                <textarea name="description" class="form-control" ><?php echo isset($row['description']) ? $row['description'] : '' ; ?></textarea>
                <br><br>
                
                <label>SCP Containment:</label>
                <br>
                <textarea name="containment" class="form-control" ><?php echo isset($row['containment']) ? $row['containment'] : '' ; ?></textarea>
                <br><br>
                
                <label>Image:</label>
                <br>
                <input type="text" name="image" placeholdoer="images/name_of_image.png" class="form-control"value="<?php echo isset($row['image']) ? $row['image'] : '' ; ?>">
                <br><br>
                
                <input type="submit" name="update" class="btn btn-danger">
                
            </form>
        
        </section>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>