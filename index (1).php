<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- Link to Google Fonts for custom font styles -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Baumans&family=Poppins:wght@600&display=swap" rel="stylesheet">
        <!-- Link to Bootstrap CSS for styling -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Link to my CSS file -->
        <link rel="stylesheet" href="styles/mystyle.css">

        <title>SCP Foundation</title>
    </head>
  
  <body>
    <section id="homepage-image">
        <header>  
          
            <?php include "connection.php"; ?>
            <!-- Nav Menu -->
            <nav class="navbar navbar-dark navbar-expand-lg">
                <div class="container-fluid">
                    <div class="logo">
                        <!-- Logo image -->
                        <img style="width: 50%; position: relative; float: left; margin-left:2%;" src="images/image.png" alt="Logo">
                    </div>
                    <!-- hambuger menu for when the screen shrinks-->
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon bg-transparent"></span>
                    </button>
                    <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
                        <ul class="navbar-nav  ms-auto">
                            <!-- using php loop through database and retrive subject values -->
                            <?php foreach($Result as $link): ?>
                            <li>
                                <a href="index.php?link='<?php echo $link['subject']; ?>'" class="nav-link" ><?php echo $link['subject']; ?></a>
                            </li>
                            <?php endforeach; ?>
                            <li class="nav-item active">
                                <a href="create.php" class="nav-link">Add New SCP Record</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>  
        </header>
        
        <?php 
            
            // Enable error reporting
            error_reporting(E_ALL);
 
            // Display errors
            ini_set('display_errors', 1);
            
                    // Delete funtionality
            if(isset($_GET['delete']))
            {
               $deleteID = $_GET['delete'];
               $delete_query = $connection->prepare("delete from scp_database where id = ?");
               $delete_query->bind_param("i", $deleteID);
               
               if($delete_query->execute())
               {
                   echo "<div class='alert alert-danger'>Recorded Deleted...</div>";
               }
               else
               {
                    echo "<div class='alert alert-danger'>Error: {$delete_query->error}</div>";
               }
            } // end of delete funtionality
            
            
            if(isset($_GET['link'])) 
            {
                // remove sigle quotes (%27 27%) from returned get value
                //value to trim, character to trim out
                $subject = trim($_GET['link'], "'");
                
                // // run sql command to retrive record based on $model
                // $record = $connection->query("select * from kenworth where model='$model'");
                
                // // save each field in record as an array
                // $array = $record->fetch_assoc();
                
                //Prepared Statement
                $statement = $connection->prepare("select * from scp_database where subject= ?");
                if(!$statement)
                {
                    echo "<p>Error in preparing sql statement</p>";
                    exit;
                }
                // bind parameters takes 2 arguments the type of data and the var to bind to.
                $statement->bind_param("s", $subject);
                
                if($statement->execute())
                {
                    $get_result = $statement->get_result();
                    // check if record has been retrived
                    if($get_result->num_rows > 0)
                    {
                        $array = array_map('htmlspecialchars', $get_result->fetch_assoc());
                        
                        $update = "update.php?update=" . $array['id'];
                        $delete = "index.php?delete=" .$array['id'];
                        
                        echo "
                        <div class='containment'>
                            <h4><b>Item #: </b>{$array['subject']}</h4>
                            <h2><b>Object Class: </b>{$array['class']}</h2>
                        </div>";
                        
                        if(!empty($array['image']))
                        {
                            echo "<p class='cover-image'>
                                    <img src='{$array['image']}' alt='{$array['subject']}' class='img-fluid'>
                                    </p>";
                        }
                        echo "
                            <!-- Special Containment Procedures -->
                            <div class='containment'>
                            
                                <!-- Special Containment Procedures -->
                                <h2>Special Containment Procedures:</h2>
                                <p>{$array['containment']}</p>
                            </div>

                            <!-- Description -->
                            <div class='holder'>
                                <h2>Description:</h2>
                                <p>{$array['description']}</p>
                            </div>
                            <p class='buttons'><a href='{$update}' class='btn btn-dark'>Update Record</a> &nbsp;
                            <a href='{$delete}' class='btn btn-danger'>Delete Record</a></p>

                        ";
                    } 
                    else
                    {
                        echo "<p>No record found for subject:{$subject}</p>";
                    }
                }
                else
                {
                    echo "<p>Error executing statement.</p>";
                }
                
                
            }
            
            else
            {
                // this will display the first time a user visits the site
                echo "
                    <!-- Header Message -->
                    <div class='container'>
                        <h1 style='margin-top: 3%;'>Scp Classified:</h1>
                        <h2>Classified reports from the scp Foundation</h2>

                    </div>
                    <!-- Homepage image -->
                    <img style='width: 45%; float: left;' src='images/Hompage_img.png' alt='Hompage_image'>

                    <!-- Classified Message -->
                    <div class='classified'>
                        <h2>THE FOLLOWING FILES HAVE BEEN CLASSIFIED</h2>
                        <h1 style='margin-top: 1%; margin-bottom: 1%;'>TOP SECRET</h1>
                        <h2>BY ORDER OF THE ADMINISTRATOR</h2>
                    </div>
                    
                    <!-- Warning Message -->
                    <div class='warning'>
                        <h1>WARNING:</h1>
                        <p>ANY NON-AUTHORIZED PERSONNEL ACCESSING THIS FILE WILL BE IMMEDIATELY
                            TERMINATED THROUGH BERRYMAN-LANGFORD MEMETIC KILL AGENT. SCROLLING 
                            DOWN WITHOUT PROPER MEMETIC INOCULATION WILL RESULT IN IMMEDIATE CARDIAC ARREST FOLLOWED BY DEATH.</p>
                        <h2>YOU HAVE BEEN WARNED.</h2>  
                    </div> 
        
                    <!-- SCP-002 image & description -->
                    <div class='images' style='width:25%; float:left; margin:0 5% 0 2%;'>
                        <img style='width:100%;' src='images/800px-SCP002.jpg' alt='The Living Room'>        
                        <div class='overlay'>
                            <div class='text'>The Living Room</div>
                        </div>
                    </div>
                    <div class='box'>
                        <h2 >Description:</h2>
                        <p>SCP-002, also known as 'The Living Room' resembles a tumorous, fleshy growth with a volume of roughly 60 m続 (or 2000 ft続). An iron valve hatch on one side leads to its interior,
                            which appears to be a standard low-rent apartment of modest size. One wall of the room possesses a single window,
                            though no such opening is visible from the exterior. The room contains furniture which, upon close examination,
                            appears to be sculpted bone, woven hair, and various other biological substances produced by the human body.
                                All matter tested thus far show independent or fragmented DNA sequences for each object in the room.</p>
                    </div>
        
                    <!-- SCP-003 image & description -->
                    <div class='images' style='width:25%; float:right; margin:5% 5% 0 2%;'>
                        <img style='width:100%;' src='images/classified.jpg' alt='Biological Motherboard'>         
                        <div class='overlay'>
                            <div class='text'>Biological Motherboard</div>
                        </div>
                    </div>
                    <div class='box-two'>
                        <h2 >Description:</h2>
                        <p>SCP-003, also known as 'Biological Motherboard', is a mysterious and potentially dangerous anomalous object in the
                                fictional universe of the SCP Foundation. It appears as a complex mechanical and organic structure resembling a motherboard,
                                with intricate circuitry and biological components. SCP-003 is capable of self-repair and has shown signs of life,
                                making it a unique and enigmatic entity. Interactions with SCP-003 are strictly controlled due to its potential threat,
                                and its true nature and origin remain largely unknown within the SCP Foundation lore.</p>
                    </div>
        
                    <!-- SCP-004 image & description -->
                    <div class='images' style='width:25%; float:left; margin:7% 5% 0 2%;'>
                        <img style='width:100%;' src='images/SCP004_door.jpg' alt='The 12 Rusty Keys and the Door'>       
                        <div class='overlay'>
                            <div class='text'>The 12 Rusty Keys and the Door</div>
                        </div> 
                    </div>
                    <div class='box-three'>
                        <h2 >Description:</h2>
                        <p>SCP-004, also known as 'The 12 Rusty Keys and the Door', is a containment object within the SCP Foundation universe. 
                            It consists of a set of twelve rusted keys, labeled SCP-004-1 through SCP-004-12, and an anomalous door, designated SCP-004-EX. 
                            The keys are required to unlock the door, but the specific order in which they must be used remains unknown.
                            <br>
                            <br>
                            SCP-004-EX is a massive, ornate door of unknown origin, constructed from an unidentified material. 
                            Attempts to breach or open the door without using the keys have been unsuccessful.
                            When the keys are used in the correct sequence, the door opens to reveal an unknown space or dimension, 
                            which varies each time the door is opened. Exploration teams sent through the door encounter diverse and 
                            often dangerous environments, and the door's unpredictable nature makes it a subject of ongoing research 
                            and containment efforts by the SCP Foundation.</p>
                    </div>
        
                    <!-- SCP-005 image & description -->
                    <div class='images' style='width:25%; float:right; margin:9% 5% 0 2%;'>
                        <img style='width:100%;' src='images/SCP-005.jpg' alt='Skeleton Key'>          
                        <div class='overlay'>
                            <div class='text'>Skeleton Key</div>
                        </div>
                    </div>
                    <div class='box-four'>
                        <h2>Description:</h2>
                        <p>SCP-005, also known as 'Skeleton Key', resembles an ornate key, displaying the characteristics of a typical mass produced key used in the 1920s. 
                            The key was discovered when a civilian used it to infiltrate a high security facility. SCP-005 seems to have the unique ability 
                            to open any and all forms of lock (See Appendix A), be they mechanical or digital, with relative ease. The origin of this ability
                            has yet to be determined.</p>
                    </div>
                
                    <!-- SCP-006 image & description -->
                    <div class='images' style='width:25%; float:left; margin:10% 5% 0 2%;'>
                        <img style='width:100%;' src='images/classified.jpg' alt='Fountain of Youth'>        
                        <div class='overlay'>
                            <div class='text'>Fountain of Youth</div>
                        </div>
                    </div>
                    <div class='box-five'>
                        <h2 >Description:</h2>
                        <p>SCP-006, also known as 'Fountain of Youth', is an anomalous substance within the SCP Foundation universe.
                            It appears as a clear, viscous liquid with potent regenerative properties. When applied to living organisms,
                            SCP-006 can significantly reverse the aging process, promoting rapid cellular regeneration and extending 
                            the subject's lifespan.
                            <br>
                            <br>
                            Despite its potential benefits, SCP-006 poses a considerable risk as it can lead to uncontrolled cell growth and mutations,
                            resulting in unpredictable and often hazardous outcomes. As a result, access to SCP-006 is strictly controlled, 
                            and experimentation with the substance requires authorization from the highest levels of the SCP Foundation. 
                            The true origin and nature of SCP-006 remain unknown.</p>
                    </div>
                    
                    <!-- SCP-007 image & description -->
                    <div class='images' style='width:25%; float:right; margin:9% 5% 0 2%;'>
                        <img style='width:100%;' src='images/classified.jpg' alt='Abdominal Planet'>          
                        <div class='overlay'>
                            <div class='text'>Abdominal Planet</div>
                        </div>
                    </div>
                    <div class='box-six'>
                        <h2>Description:</h2>
                        <p>SCP-007 is located in the abdomen of a Caucasian male subject, approximately 25 years old and 176 cm tall. The subject lacks most abdominal organs and flesh, 
                        which is replaced by a 60 cm diameter sphere of soil and water resembling a miniature Earth. This sphere has unique weather patterns, minimal gravitational pull, 
                        and microscopic organisms. Two intelligent species inhabit the sphere, with technology levels comparable to 15th-century Earth. The subject, claiming to be ███████████████, 
                        does not require food or water despite consuming them occasionally. He is intelligent (IQ 128), friendly, and curious about the sphere in his abdomen but shows no distress 
                        about his condition. His identity cannot be verified through provided social security and driver's license numbers, which do not exist in any records. 
                        Weekly chess games with Dr. ███████ are used to assess the subject's mental health, which remains stable. The subject has not attempted to escape or shown signs of 
                        violence or mental illness but has persistently requested a computer with internet access, which is denied to maintain security.</p>
                    </div>
                    
                    <!-- SCP-011 image & description -->
                    <div class='images' style='width:25%; float:left; margin:9% 5% 0 2%;'>
                        <img style='width:100%;' src='images/SCP011.jpg' alt='Sentient Civil War Memorial'>          
                        <div class='overlay'>
                            <div class='text'>Sentient Civil War Memorial</div>
                        </div>
                    </div>
                    <div class='box-seven'>
                        <h2>Description:</h2>
                        <p>SCP-011 is a Civil War memorial statue located in Woodstock, Vermont. The statue is the image of a young male soldier holding a musket at his side, 
                        and is carved out of granite quarried within the area. Occasionally, SCP-011 has been observed lifting its musket to the sky to fire at birds which attempt 
                        to land or defecate on it. Reports detail that its movements produce soft grinding sounds but do not cause it any structural failure. Oddly, the gunfire is 
                        very similar to that of a standard firearm, despite observations that the item only loads granite bullets and granite powder into the musket 
                        (which is also unharmed by the firing). In spite of its efforts, some fecal matter does manage to strike SCP-011, and it has reportedly become 
                        distressed when it has had a large amount of feces on it, on some rare occasions even firing at humans.</p>
                    </div>
                    
                    <!-- SCP-015 image & description -->
                    <div class='images' style='width:25%; float:right; margin:9% 5% 0 2%;'>
                        <img style='width:100%;' src='images/medium.jpg' alt='Pipe Nightmare'>          
                        <div class='overlay'>
                            <div class='text'>Pipe Nightmare</div>
                        </div>
                    </div>
                    <div class='box-eight'>
                        <h2>Description:</h2>
                        <p>SCP-015 is a complex mass of pipes and plumbing completely filling a warehouse in ███████. The pipes, which grow when unobserved, attempt to connect to nearby structures.
                        SCP-015 includes over 190 kilometers of pipes made from various unusual materials such as bone, wood, steel, human flesh, glass, and granite. SCP-015 reacts violently
                        to tools and aggression. Any attempts to damage or repair it cause nearby pipes to burst, spraying hazardous substances like oil, mercury, rats, insects, glass, seawater,
                        entrails, and molten iron until the person retreats or dies.Originally, SCP-015 had attached to 11 nearby structures before being cut back. This has resulted in 11 deaths
                        and 20 missing personnel, with reports of banging and screaming from within SCP-015.</p>
                    </div>
                    
                    <!-- SCP-017 image & description -->
                    <div class='images' style='width:25%; float:left; margin:9% 5% 0 2%;'>
                        <img style='width:100%;' src='images/scp017.jpg' alt='the Shadow Person'>          
                        <div class='overlay'>
                            <div class='text'>the Shadow Person</div>
                        </div>
                    </div>
                    <div class='box-nine'>
                        <h2>Description:</h2>
                        <p>SCP-017 is a humanoid figure approximately 80 centimeters in height, anatomically similar to a small child, but with no discernible identifying features. 
                        SCP-017 seems to be composed of a shadowy, smoke-like shroud. No attempt to find any object beneath the shroud has been successful, but the possibility has not been ruled out.
                        SCP-017's reaction to shadows cast upon it is immediate and swift. SCP-017 leaps at the object casting the shadow and completely encloses it in its shroud, 
                        whereupon it returns to its normal size, leaving no trace of the object behind.</p>
                    </div>
                    
                    <!-- SCP-043 image & description -->
                    <div class='images' style='width:25%; float:right; margin:9% 5% 0 2%;'>
                        <img style='width:100%;' src='images/scp043.jpg' alt='The White Album'>          
                        <div class='overlay'>
                            <div class='text'>The White Album</div>
                        </div>
                    </div>
                    <div class='box-ten'>
                        <h2>Description:</h2>
                        <p>SCP-043 appears to be a vinyl copy of 'The White Album' by the Beatles; however, upon closer inspection, the record has no grooves. In spite of this,
                        the record will play from start to finish regardless of the starting position of the needle.When the twenty-ninth track is reached, instead of playing 'Revolution 9',
                        the disc stops spinning and faint breathing can be heard. Occasionally the entity responsible for the breathing will speak in a male voice. The entity will respond to 
                        questions and shows a profound encyclopedic knowledge of the music industry, musical theory, and obscure trivia about many bands and artists. However, the entity refuses
                        to answer questions regarding The Beatles or its own personal details. Inside the jacket, a small handwritten note was found, reading: Limited Edition: 1/1 Thanks, John! xxx</p>
                    </div>
                ";
            }

        ?>
        
    
        <footer>
            <div class="logo">
                <img style="width: 50%; position: relative; float: left; margin-left:2%;" src="images/image.png" alt="Logo">
            </div>
        </footer>
    
    
    </section>
        <!-- Link to Bootstrap Script -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Link to my Script -->
        <script src="scripts/script.js"></script>
        </body>
</html>