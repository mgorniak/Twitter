<?php

require_once '../src/lib.php';
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';

session_start();
$user = loggedUser($conn);

if (!isset($_SESSION['view'])) {
    $_SESSION['view'] = "all";
}

//Obsługa formularza dodającego tweeta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['text']) && isset($user)) {
        $text = $_POST['text'];
        $userId = $user->getId();
        $creationDate = date("Y-m-d H:i:s");

        $tweet = new Tweet;

        $tweet->setText($text);
        $tweet->setCreationDate($creationDate);
        $tweet->setUserId($userId);

        $tweet->saveToDb($conn);
    }
}

if ($_SESSION['view'] === "all") {
    $allTweets = Tweet::loadAllTweets($conn);
} elseif ($_SESSION['view'] === "user") {
    $allTweets = Tweet::loadAllTweetsByUserId($conn, $user->getId());
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Index</title>
</head>
<body>

<?php
//Potwierdzenie poprawnego dodania nowego użytkownika
    if(isset($_SESSION['registered'])){
        echo $_SESSION['registered'];
        unset ($_SESSION['registered']);
    }
//Potwierdzenie poprawnego wylogowania
    if(isset($_SESSION['logout'])){
        echo $_SESSION['logout'];
        unset ($_SESSION['logout']);
    }

    if ($user) { ?>
            You are logged in as: <?php echo $user->getUsername() ?>
            <a href='logout.php'>Logout</a><br>
        </p>
<!--Formularz nadawania tweeta-->
        <p>Write down your tweet <?php echo $user->getUsername();?>
            <form action = "#" method = "POST">
        <p><label>Text: <input name ="text" type = "text"></label>
            <input type = "submit" value = "Tweet"></p>
        </form>
<!--Zmiana widoku tweetów. Wszystkie albo zalogowanego użytkownika-->
        <form action="allOrUserTweets.php" method="POST">
            <select name="allOrUserTweets">
                <option value="all">All tweets</option>
                <option value="user">Only my tweets</option>
            </select>
            <input type="submit" value="Change view">
        </form>
    <?php } else { ?>
        <p>
            <a href="loginForm.php">Login</a><br>
            <a href="registerForm.php">Register</a><br><br>
        </p>
    <?php } ?>
        <p>All tweets: </p>
        <table border="1" rules="all">
            <tr>
                <td>Id</td>
                <td>Id tweeta</td>
                <td>Treść</td>
                <td>Autor</td>
                <td>Data</td>
            </tr>
            <?php
            $id = 0;
                foreach ($allTweets as $tweet){
                    $id++;
                    $user = User::loadUserById($conn, $tweet['userId']);
                    echo "
                        <tr><td>" . $id . "</td>
                            <td>" . $tweet['id'] . "</td>
                            <td>" . $tweet['text'] . "</td>
                            <td>" . $user->getUsername() . "</td>
                            <td>" . $tweet['creationDate'] . "</td></tr>";
                }
            ?>
        </table>
</body>
</html>
