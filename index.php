<!-- index.php -->
<?php

session_start();
if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM users WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    // insert note
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["content"])) {
        $content = $mysqli->real_escape_string(trim($_POST["content"]));
        $user_id = $_SESSION["user_id"];

        $sql = "INSERT INTO notes (user_id,content) VALUES ('$user_id','$content')";
        $mysqli->query($sql);

        header("Location: index.php");
        exit;
    }

    // delete note
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_note_id"])) {
        $note_id = (int) $_POST["delete_note_id"];
        $user_id = $_SESSION["user_id"];

        $sql_d = "DELETE FROM notes WHERE id = $note_id AND user_id =$user_id";
        $mysqli->query($sql_d);

        header("Location: index.php");
        exit;
    }
    $notes_sql = "SELECT * FROM notes WHERE user_id = {$_SESSION["user_id"]} ORDER BY created_at DESC";
    $notes_result = $mysqli->query($notes_sql);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        form {
            margin-bottom: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }

        button {
            padding: 10px 15px;
            background: #007BFF;
            color: #fff;
            border: none;
            /*  */
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .login-btn {
            padding: 10px 15px;
            background: #0063cdff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 15px;

        }

        .login-btn:hover {
            background: #033c79ff;
        }

        .logout-btn {
            display: inline-block;
            background-color: #bc5b5bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #a34848ff;
        }

        .delete-btn {
            display: inline-block;
            background-color: #bc5b5bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #a34848ff;
        }



        .note {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);


        }

        .note h3 {
            margin: 0;
            color: #007BFF;
        }

        .note p {
            margin: 5px 0;
        }

        .note small {
            color: #ffffffff;
        }

        .note {
            background: #3c5f75ff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(159, 159, 159, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;


        }

        .note:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

</head>

<body>
    <h1>Home</h1>
    <?php if (isset($user)): ?>
        <p>hello <?= htmlspecialchars($user["name"]) ?></p>

        <form method="post">
            <textarea name="content" id="note" placeholder="Write your note here..." rows="4"></textarea>
            <button type="submit">Add note</button>

        </form>
        <?php if (isset($notes_result)): ?>
            <?php while ($note = $notes_result->fetch_assoc()): ?>
                <div class="note">
                    <p><?= htmlspecialchars($note["content"]) ?></p>
                    <small>Created at <?= $note["created_at"] ?> </small>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_note_id" value="<?= $note["id"] ?>">
                        <button type="submit" class="delete-btn">
                            Delete
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>


        <p><a href="logout.php" class="logout-btn">log out</a> </p>

    <?php else: ?>
        <p><a href="login.php" class="login-btn">Log in</a> or <a href="Signup.html" class="login-btn">Sign up</a></p>

    <?php endif; ?>

</body>

</html>