<?php

require_once '../src/lib.php';
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';

session_start();
$user = loggedUser($conn);

$allUsers = User::loadAllUsers($conn);

//Obsługa formularza dodającego komentarz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ( isset($user) && isset($_POST['senderId']) && isset($_POST['messageText']) && isset($_POST['receiverId']) ) {
        $messageText = $_POST['messageText'];
        $senderId = $_POST['senderId'];
        $receiverId = $_POST['receiverId'];

        $message = new Message();

        $message->setText($messageText);
        $message->setReceiverId($receiverId);
        $message->setSenderId($senderId);

        $message->saveMessageToDb($conn);

        $receiverName = User::loadUserById($conn, $receiverId);
        $receiverName = $receiverName->getUsername();

        $_SESSION['sendMessage'] = "Message to " . $receiverName . " was sent correctly.";
        header('location: mailBox.php');
    }
}

if ($user) {
?>
        <?php echo $user->getUsername();?>, wybierz odbiorcę i napisz wiadomość
            <form action="newMessage.php" method="POST">
<!--nadawca-->  <input type="hidden" name="senderId" value="<?php echo $user->getId();?>">
<!--odbiorca--> <select name="receiverId">
                    <?php
                    foreach ($allUsers as $user){
                        echo "<option value=" . $user['id'] . ">" . $user['username'] . "</option>";
                    }
                    ?>
                </select>
<!--treść-->    <input type="text" name="messageText">
<!--wyślij-->   <input type="submit" value="Send">
    </form>
    <?php
} else {
    header('location: mailBox.php');
}

