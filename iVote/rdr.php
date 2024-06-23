<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rdr</title>
    <link rel="stylesheet" href="Styles/Styles.css">
</head>
<body>
    <div class="ParentDiv">
        <h2 class="Page-Title">Redirection Successful</h2>
        <div class="ChildDiv">
            <div class="GrandchildDiv">
                <h2><?php require "config.php"; 
                echo $_SESSION['Selected_Session'] . "<br>";
                echo $_SESSION['Voted'] . " Voted Option <br>"; 
                echo $_SESSION['voter'];
                ?></h2>
            </div>
        </div>
    </div>
</body>
</html>