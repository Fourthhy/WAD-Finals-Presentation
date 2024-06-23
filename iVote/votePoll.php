<?php
require "config.php";
try {
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    $sql = "SELECT poll_title FROM poll_session WHERE poll_key = :pollKey";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pollKey', $_SESSION['poll_key'], PDO::PARAM_STR);
    $stmt->execute();

    $pollTitle = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pollTitle['poll_title'] . " vote";?></title>
    <link rel="stylesheet" href="Styles/Styles.css">
</head>
<body>
    <div class="ParentDiv">
        <h2 class="Page-Title"><?php echo $pollTitle['poll_title'] . " vote"; ?></h2>
        <div class="ChildDiv">
            <div class="GrandchildDiv">
                <ul class="UL">
                    <?php
                    try {
                        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                        $sql = "SELECT * FROM poll_session ps INNER JOIN poll_session_options pso ON ps.poll_id = pso.poll_id WHERE ps.poll_key = :pollKey;";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':pollKey', $_SESSION['poll_key'], PDO::PARAM_STR);
                        $stmt->execute();

                        while($Options = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $Vote_Poll_LI = "Vote-Poll-LI";
                            echo "<li class=$Vote_Poll_LI>";
                            $Vote_Poll_Container = "Vote-Poll-Container";
                            echo "<div class=$Vote_Poll_Container>";
                            $Options_Div = "Options-Div";
                            echo "<div class=$Options_Div>";
                            $Option_Title = "Option-Title";
                            echo "<p class=$Option_Title>" . $Options['poll_option'] . "</p>";
                            echo "</div>";
                            echo "<form method='POST'>";
                            $Vote_Button = "Vote-Button";
                            echo "<input type='hidden' name='Voted' value='".$Options['poll_session_options_id']."'>";
                            echo "<input type='submit' value='Vote' class=$Vote_Button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</li>";
                        }

                        
                        
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }

                    if (isset($_POST['Voted'])) {
                        $_SESSION['Voted'] = $_POST['Voted']; //option_id
                        $optionId = $_SESSION['Voted'];
                        // poll_key, username, option_id
                        try {
                            $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                            $sql1 = "SELECT poll_id FROM poll_session WHERE poll_key = :pollKey";
                            $stmt1 = $pdo->prepare($sql1);
                            $stmt1->bindParam(':pollKey', $_SESSION['Selected_Session'], PDO::PARAM_STR);
                            $stmt1->execute();
                            $pollIdRow = $stmt1->fetch(PDO::FETCH_ASSOC);
                            $pollId = $pollIdRow['poll_id'];

                            $sql2 = "SELECT users_id FROM registered_users WHERE username = :username"; // bind parameter;
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->bindParam(':username', $_SESSION['voter'], PDO::PARAM_STR);
                            $stmt2->execute();
                            $usersIdRow = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $usersId = $usersIdRow['users_id'];

                            $sql3 = "INSERT INTO analytic_records (poll_id, poll_session_options_id, users_id) VALUES (:pollId, :optionId, :usersId)";
                            $stmt3 = $pdo->prepare($sql3);
                            $stmt3->bindParam(':pollId', $pollId, PDO::PARAM_STR);
                            $stmt3->bindParam(':optionId', $optionId, PDO::PARAM_STR);
                            $stmt3->bindParam(':usersId', $usersId, PDO::PARAM_STR);
                            $stmt3->execute();
                            header("Location: thanksForVoting.php");
                            exit;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        
                    } 
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

<!-- <li class='Vote-Poll-LI'>
    <div class='Vote-Poll-Container'>
        <div class='Options-Div'>
            <p class='Option-Title'>Sample Title</p>
        </div>
        <form action='POST'>
            <input type='hidden'>
            <input type='Submit' value='vote' class='Vote-Button'>
        </form>
    </div>
</li> -->