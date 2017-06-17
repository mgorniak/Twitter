<?php

require_once '../src/lib.php';
require_once '../src/connection.php';
require_once '../src/Message.php';


session_start();
$user = loggedUser($conn);

//informacja o poprawny wysłaniu wiadomości do nadawcy
if(isset ($_SESSION['sendMessage'])){
    echo $_SESSION['sendMessage'];
    unset   ($_SESSION['sendMessage']);
}

if (!$_SESSION['box']) {
    $_SESSION['box'] = "inbox";
}

if ( $_SESSION['box'] === "inbox" ) {
    $allMessages = Message::loadAllMessagesByReceiverId($conn, $user->getId());
} elseif ( $_SESSION['box'] === "outbox" ) {
    $allMessages = Message::loadAllMessagesBySenderId($conn, $user->getId());
}

if ( $user ) {?>
    <div>
        <table border="1" rules="all">
            <tr>
                <th>Mailbox</th>
                <th>List of messages</th>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li><a href="inbox.php">Inbox</a></li>
                        <li><a href="outbox.php">Outbox</a></li>
                    </ul>
                    <div>
                        <a href="newMessage.php">Send new message</a>
                    </div>
                </td>

                <td>
                    <ol>
                        <?php
                            if($allMessages->num_rows === 0) {
                                echo "There is no message.";
                            } else {
                                foreach ($allMessages as $message) {
                                    var_dump($message['readed']);
                                    if ($message['readed'] == 0) {
                                        $readed = "Unread";
                                    } elseif ($message['readed'] == 1) {
                                        $readed = "Readed";
                                    }
                                    $receiver = User::loadUserById($conn, $message['receiverId']);
                                    echo "<li>" . $message['text'] . "<br>- " . $receiver->getUsername() . " " . $readed . "<a href='checkMessage.php'>Przeczytano</a></li>";
                                }
                            }
                        ?>
                    </ol>
                </td>
            </tr>
        </table>
    </div>
    <div><a href="index.php">Return to Mainpage</a></div><?php
} else {
    header ("location: index.php");
}

