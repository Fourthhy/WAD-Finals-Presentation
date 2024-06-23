<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iVote Join Poll</title>
    <link rel="stylesheet" href="Styles/Styles.css">
</head>
<body>
    <?php
    $errorUserMesssage = '';
    $errorKeyMessage = '';
    
    ?>
    <div class="ParentDiv">
        <h2 class="Page-Title">iVote Join Poll</h2>
        <div class="ChildDiv">
            <div class="GrandchildDiv">
                <form method="post">
                    <div class="Credential-Input">
                        <input required type="text" name="username" class="Voter-Registration-nameAndKey" placeholder="Enter Username...">
                        <label for="Error Handling" class="errorHandling"><?php echo $errorUserMesssage; ?></label>
                        <input required type="text" name="pollKey" class="Voter-Registration-nameAndKey" placeholder="Enter Poll key...">
                        <label for="Error Handling" class="errorHandling"><?php echo $errorKeyMessage; ?></label>
                        <input type="submit" class="Register-User-Submit">
                    </div>
                </form>
            </div>
        </div>
        <?php
        require "config.php";
        try {
            $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            if (isset($_POST['username'])) {
                $username = $_POST['username'];
                $pollKey = $_POST['pollKey'];

                $sql = "SELECT username FROM registered_users WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->execute();
               
                $userExist = $stmt->rowCount() > 0;

                if ($userExist) {
                    $sql = "SELECT poll_key FROM poll_session WHERE poll_key = :pollKey";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':pollKey', $pollKey, PDO::PARAM_STR);
                    $stmt->execute();

                    $keyExist = $stmt->rowCount() > 0;
                    if ($keyExist) {
                        echo "<script> alert('The code exist') </script>";  
                        $_SESSION['poll_key'] = $pollKey;
                        $_SESSION['voter'] = $username;
                        header("Location: votePoll.php");
                    } else {
                        echo "<script> alert('The code is incorrect or doesnt exist') </script>";
                    }
                } else {
                    echo "<script> alert('The user does not exist') </script>";
                }

                
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        
        ?>
        <div class="Poll-Creations">
            <button class="Join-Poll" onclick="window.location.href='index.php'">Back to Dashboard</button>
        </div>
    </div>
</body>
</html>