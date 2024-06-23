<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iVote Dashboard</title>
    <link rel="stylesheet" href="Styles/Styles.css">
</head>
<body>
    <div class="ParentDiv">
        <h2 class="Page-Title">iVote Sessions: </h2>
        <div class="ChildDiv">
            <div class="GrandchildDiv">
                <ul>
                    <?php
                        require "config.php";
                        try {
                            $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                            $sql = "SELECT * FROM poll_session";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            while ($Sessions = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $Session_List = "Session-List";
                                echo "<li class=$Session_List>";
                                $Session_Info_Div = "Session-Info-Div";
                                echo "<div class=$Session_Info_Div>";
                                $Session_Title_Div = "Session-Title-Div";
                                echo "<div class=$Session_Title_Div>";
                                $Session_Title = "Session-Title";
                                echo "<p class=$Session_Title>" . $Sessions['poll_title'] . "</p>";
                                echo "</div>";
                                $Session_Redirection_Button = "Session-Redirection-Button";
                                echo "<form method='GET'>";
                                echo "<input type='hidden' name='Selected_Session' value='" .$Sessions['poll_key']. "'>";
                                echo "<input type='submit' value='See Session' class=$Session_Redirection_Button>";
                                echo "</form>";
                                echo "</div>";
                                echo "</li>";
                            }
                            
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        
                        if (isset($_GET['Selected_Session'])) {
                            $_SESSION['Selected_Session'] = $_GET['Selected_Session'];
                            header("Location: analytics.php");
                            exit;
                        } else {
                            // echo "<script> alert('There is no Session Selected') </script>";                     
                        }
                    ?>
                </ul>
            </div>
        </div>
        <div class="Poll-Creations">
            <button class="Create-Poll" onclick="window.location.href='createPoll.php'">Create Poll</button>
            <button class="Join-Poll" onclick="window.location.href='joinPoll.php'">Join Poll</button>
        </div>
    </div>
</body>
</html>