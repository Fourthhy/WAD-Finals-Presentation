<?php
require "config.php";
try {
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    $sql = "SELECT poll_title FROM poll_session WHERE poll_key = :pollKey";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pollKey', $_SESSION['Selected_Session'], PDO::PARAM_STR);
    $stmt->execute();

    $pollTitleRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $pollTitle = $pollTitleRow['poll_title'];

} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pollTitle . " Analytics";?></title>
    <link rel="stylesheet" href="Styles/Styles.css">
    <link rel="stylesheet" href="Styles/2ndStyles.css">
</head>
<body>
    <div class="ParentDiv">
        <h2 class="Page-Title"> <?php echo $pollTitle . " Analytics; Join Code " . $_SESSION['Selected_Session']; ?> </h2>
        <div class="ChildDiv">
            <div class="GrandchildDiv">
                <div class="A-Option-Container">
                    <?php
                        try {
                            $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                            $sql = "SELECT poll_id FROM poll_session WHERE poll_key = :pollKey";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':pollKey', $_SESSION['Selected_Session'], PDO::PARAM_STR);
                            $stmt->execute();
                            $poll_idRow = $stmt->fetch(PDO::FETCH_ASSOC);

                            $poll_id = $poll_idRow['poll_id'];

                            $sql = "SELECT poll_session_options_id FROM analytic_records WHERE poll_id = :pollId";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':pollId', $poll_id, PDO::PARAM_STR);
                            $stmt->execute();
                            $analytic_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            $unformatted = array_column($analytic_records, 'poll_session_options_id');
                            $sorted = array_unique($unformatted);

                            
                            
                            foreach($sorted as $s) {
                                $sql = "SELECT poll_option FROM poll_session_options WHERE poll_session_options_id = :PSOID";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':PSOID', $s, PDO::PARAM_STR);
                                $stmt->execute();
                                $option_name = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($option_name) {
                                    echo "<details class='Details'>";
                                    echo "<summary class='Summary'><span style='padding-left: 100px;'>" . $option_name['poll_option'] . "</span></summary>";
                                    echo "<div class='Voters'>";
                                    echo "<h4 class='Text'> Voters </h4>";

                                    $sql = "SELECT users_id FROM analytic_records WHERE poll_session_options_id = :PSOID";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':PSOID', $s, PDO::PARAM_STR);
                                    $stmt->execute();
                                    $user_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $output = array_column($user_id, 'users_id');
                                    
                                    foreach($output as $o) {
                                        $sql = "SELECT username FROM registered_users WHERE users_id = :usersID";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->bindParam(':usersID', $o, PDO::PARAM_STR);
                                        $stmt->execute();
                                        $username = $stmt->fetch(PDO::FETCH_ASSOC);
                                        if ($username) {
                                            echo "<p class='Text'>" . $username['username'] . " </p>";
                                        }
                                    }
                                    echo "</div>";
                                    echo "</details>";
                                }
                            }
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    ?>
                    <!-- <details class="Details">
                        <summary class="Summary"> Option name <span style="padding-left: 100px;"></span></summary>
                            
                        <div class="Voters">
                            <h4 class="Text"> Voters</h4>
                            <p class="Text"> sample 1</p>
                            <p class="Text"> sample 1</p>
                        </div>
                    </details> -->
                </div>
            </div>
        </div>
        <div class="Poll-Creations">
            <button class="Join-Poll" onclick="window.location.href='index.php'">Back to Dashboard</button>
            <form method="POST">
                <?php
                    echo "<input type='hidden' name='DeletePoll' value=' " . $_SESSION['Selected_Session']. "'>";
                ?>
                <div class="Join-Poll">
                    <input type='submit' value='Delete Poll' style="border: 1px solid black; border-radius: 5px; display: flex; font-family: var(--Font); padding: 7px 12px; margin-top: 20px; margin-right: 40px;">
                </div>
                
            </form>
            <?php
            
            if (isset($_POST['DeletePoll'])) {
                echo $_POST['DeletePoll'];
                echo $poll_id;
                try {
                    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                    $sql = "DELETE FROM analytic_records WHERE poll_id = :pollId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':pollId', $poll_id, PDO::PARAM_STR);
                    $stmt->execute();
                    
                    $sql = "DELETE FROM poll_session_options WHERE poll_id = :pollId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':pollId', $poll_id, PDO::PARAM_STR);
                    $stmt->execute();

                    $sql = "DELETE FROM poll_session WHERE poll_id = :pollId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':pollId', $poll_id, PDO::PARAM_STR);
                    $stmt->execute();

                    echo 
                    "<script>
                            alert('Session Deleted');
                            window.location.href = 'index.php';
                    </script>";

                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
            ?>
        </div>
    </div>
</body>
</html>