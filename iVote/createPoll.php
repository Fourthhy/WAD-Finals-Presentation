<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iVote Create Poll</title>
    <link rel="stylesheet" href="Styles/Styles.css">
</head>
<body>
    <div class="ParentDiv">
        <h2 class="Page-Title">iVote Create Poll</h2>
        <div class="ChildDiv">
            <div class="GrandchildDiv">
                <?php $randomNumber = rand(100000, 999999);?>
                <form method="POST">
                    <input type="text" name="Poll-Title" class="Poll-Title-Input" placeholder="Input poll title.."required>     
                    <input type="submit" value="Create Poll" class="Create-Poll-Submit">
                    <button class="Adding-Option-Button" id="Adding-Option-Button">Add Option..</button>               
                    <!-- <label for="randomCode" class="Poll-Code">Poll Code: <?php echo $randomNumber; ?></label> -->
                        <ul id="UL" class="Option-Container-List">
                            <!-- <li class="Create-Poll-LI">
                                <input type="text" name="option[]" placeholder="Add Option" required class="Input-Option-Field">
                            </li>
                            <li class="Create-Poll-LI">
                                <input type="text" name="option[]" placeholder="Add Option" required class="Input-Option-Field">
                            </li>
                            <li class="Create-Poll-LI">
                                <input type="text" name="option[]" placeholder="Add Option" required class="Input-Option-Field">
                            </li> -->
                        </ul>
                </form>
            </div>
        </div>
        <div class="Poll-Creations">
            <button class="Join-Poll" onclick="window.location.href='index.php'">Back to Dashboard</button>
        </div>
    </div>
    <?php
    require "config.php";
    try {
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        if (isset($_POST['Poll-Title'])) {
            $pollTitle = $_POST['Poll-Title'];
            $sql = "INSERT INTO poll_session (poll_title, poll_key) VALUES (:pollTitle, :pollKey)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':pollTitle', $pollTitle, PDO::PARAM_STR);
            $stmt->bindParam(':pollKey', $randomNumber, PDO::PARAM_STR);
            $stmt->execute();
        }

        if (isset($_POST['option'])) {
            $options = $_POST['option'];
            $sql = "SELECT poll_id FROM poll_session WHERE poll_title = :pollTitle";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':pollTitle', $pollTitle, PDO::PARAM_STR);
            $stmt->execute();

            $poll_id = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($poll_id) {
                $sql = "INSERT INTO poll_session_options (poll_id, poll_option) VALUES (:poll_id, :poll_option)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':poll_id', $poll_id['poll_id'], PDO::PARAM_STR);

                foreach($options as $o) {
                    $stmt->bindParam(':poll_option', $o, PDO::PARAM_STR);
                    $stmt->execute();
                }

                echo "<script> alert('Poll Session has been addded') </script>";
            } else {
                echo $pollTitle . " not found!";
            }
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    

    ?>
    <script>
        document.getElementById("Adding-Option-Button").addEventListener("click", (e)=> {
            e.preventDefault();
            const newLi = document.createElement('li');
            newLi.classList.add("Create-Poll-LI");
            const newInput = document.createElement('input');
            newInput.classList.add("Input-Option-Field");
            newInput.type = 'text';
            newInput.name = 'option[]';
            newInput.placeholder = "Add Option";
            newInput.setAttribute('required', '');

            newLi.appendChild(newInput);
            const UL = document.getElementById("UL");
            UL.appendChild(newLi);
        });
    </script>
</body>
</html>