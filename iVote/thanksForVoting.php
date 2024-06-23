<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thanks for voting</title>
        <link rel="stylesheet" href="Styles/Styles.css">
    </head>
    <body>
        <div class="ParentDiv">
            <h2 class="Page-Title">Vote Recorded</h2>
            <div class="ChildDiv">
                <div class="GrandchildDiv">
                    <div>
                        <h2 class="Message" style="height: 100px; display: grid; place-items: center; ">
                            <?php 
                                require "config.php"; 
                                echo "Thanks for voting!, ". $_SESSION['voter'];
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="Poll-Creations">
                <button class="Join-Poll" onclick="window.location.href='index.php'">Back to Dashboard</button>
            </div>
        </div>
    </body>
</html>