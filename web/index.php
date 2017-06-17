<?php

require_once '../src/lib.php';
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';

session_start();
$user = loggedUser($conn);

if (!isset($_SESSION['view'])) {
    $_SESSION['view'] = "all";
}

//Obsługa formularza dodającego tweeta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tweetText']) && isset($user)) {
        $tweetText = $_POST['tweetText'];
        $userId = $user->getId();
        $creationDate = date("Y-m-d H:i:s");

        $tweet = new Tweet;

        $tweet->setText($tweetText);
        $tweet->setCreationDate($creationDate);
        $tweet->setUserId($userId);

        $tweet->saveToDb($conn);
    }
}

//Obsługa formularza dodającego komentarz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['commentText']) && isset($user) && isset($_POST['postId'])) {
        $commentText = $_POST['commentText'];
        $userId = $user->getId();
        $postId = $_POST['postId'];
        $creationDate = date("Y-m-d H:i:s");

        $comment = new Comment;

        $comment->setPostId($postId);
        $comment->setUserId($userId);
        $comment->setCreationDate($creationDate);
        $comment->setText($commentText);

        $comment->saveToDb($conn);
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
        <div>You are logged in as: <?php echo $user->getUsername() ?></div>
        <div><a href='mailBox.php'>Mailbox</a></div>
        <div><a href='logout.php'>Logout</a></div>
        </p>
<!--Formularz nadawania tweeta-->
        <div>
            <p>Write down your tweet <?php echo $user->getUsername();?>
                <form action = "#" method = "POST">
            <p><label>Text: <input name ="tweetText" type = "text"></label>
                <input type = "submit" value = "Tweet"></p>
            </form>
        </div>
<!--Zmiana widoku tweetów. Wszystkie albo zalogowanego użytkownika-->
        <div>
            <form action="allOrUserTweets.php" method="POST">
                <select name="allOrUserTweets">
                    <option value="all">All tweets</option>
                    <option value="user">Only my tweets</option>
                </select>
                <input type="submit" value="Change view">
            </form>
        </div>
    <?php } else { ?>
        <div>
            <p>
                <a href="loginForm.php">Login</a>
                <a href="registerForm.php">Register</a>
            </p>
        </div>
    <?php } ?>
        <div>
            <p>All tweets: </p>
            <table border="1" rules="all">
                <tr>
                    <th>Id</th>
                    <th>Id tweeta</th>
                    <th>Treść</th>
                    <th>Autor</th>
                    <th>Data</th>
                    <th>Komentarze</th>
                </tr>
                <!--Wyświetlanie listy tweetów. Wszystkich lub tylko zalogowanego użytkownika-->
                <?php
                $id = 0;
                foreach ($allTweets as $tweet){
                    $id++;
                    $postId = $tweet['id'];
                    $user = User::loadUserById($conn, $tweet['userId']);
                    $allComments = Comment::loadAllCommentsByPostId($conn, $postId);
                    echo "
                        <tr><td>" . $id . "</td>
                            <td>" . $postId . "</td>
                            <td>" . $tweet['text'] . "</td>
                            <td>" . $user->getUsername() . "</td>
                            <td>" . $tweet['creationDate'] . "</td>
<!--Wyświetlanie komentarzy-->
                            <td>
<!--Formularz komentarzy-->
                                <form action='#' method='POST'>
                                    <input type='text' name='commentText'>
                                    <input type='hidden' name='postId' value='$postId'>
                                    <input type='submit' value='Comment'>
                                </form>
                                
                                <ul type='square'>";
                    foreach($allComments as $comment) {
                        $userComment = User::loadUserById($conn, $comment['userId']);
                        echo "<li>" . $comment['text'] . " - " . $userComment->getUsername() . " - dodano " . $comment['creationDate'] . "</li>";
                    };
                    "</ul>
                            </td></tr>";
                }
                ?>
            </table>
        </div>
</body>
</html>
