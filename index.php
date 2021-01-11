<!doctype html>
<html lang="en">


<?php

session_start();

$server = "localhost";
$username = "root";
$pass = "root";
$db = "url_shortener";

$conn = mysqli_connect($server, $username, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    //echo bin2hex(random_bytes(3));

    $sql = "SELECT * FROM url";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $table = "<table>";
        while ($row = mysqli_fetch_assoc($result)) {
            $table .= "<tr>";
            $table .= "<th> <a href='" . $row["shortUrl"] . " " . "'> " . $row["shortUrl"] . " </a> </th>";
            $table .= "<th> <a href= '" . $row["longUrl"] . "'> " . $row["longUrl"] . "</a> </th>";
            $table .= "</tr>";
        }
        $table .= "</table>";
    } else {
        echo "0 results";
    }
}

//echo $_SERVER['HTTP_HOST'] . "/" . explode("/", $_SERVER['REQUEST_URI'])[1] . " ";

function alert($msg)
{
    echo "<script type='text/javascript'>
    window.location='index.php';
    alert('$msg');
    </script> ";
}

//generate short URL

function getShortUrl($conn, $length = 3)
{

    do {
        $key = bin2hex(random_bytes($length));
        $url = $key;
        $sql = "SELECT shortUrl FROM url WHERE shortUrl = '${url}' ";
        $request = mysqli_query($conn, $sql);
    } while (mysqli_num_rows($request) != 0);
    return $url;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urlInput'])) {
    $longUrl = trim($_POST["urlInput"]);
    alert($longUrl[-1] = "/");
    if ($longUrl[-1] = "/") {
        $longUrl = substr($longUrl, 0, -1);
    }
    if (filter_var($longUrl, FILTER_VALIDATE_URL) && strlen($longUrl) <= 100) {
        $server = "localhost";
        $username = "root";
        $pass = "root";
        $db = "url_shortener";

        $conn = mysqli_connect($server, $username, $pass, $db);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            //echo "Connected successfully ";


            $shortUrl = getShortUrl($conn);

            $sql = "INSERT INTO url (shortUrl, longUrl, urlCounter) VALUES ('$shortUrl', '$longUrl', 0)";
            //echo "<br>"; 
            //echo "Long url: " . $longUrl . "<br>";
            //echo "Short url: " . $shortUrl . "<br>";
            //echo "Query: " . $sql . "<br>" ;

            $request = mysqli_query($conn, $sql);


            if ($request) {
                $_SESSION["redir"] = "Redirect created! Visit " . $_SERVER['HTTP_HOST'] . "/" . explode("/", $_SERVER['PHP_SELF'])[1] . "/" . $shortUrl;
                // output data of each row
                //while($row = mysqli_fetch_assoc($result)) {
                //  echo "id: " . $row["id"]. " - shortUrl: " . $row["shortUrl"]. " - longUrl: " . $row["longUrl"]. " - urlCounter: ". $row["urlCounter"] ."<br>";
                //}
            } else {
                $sql = "SELECT shortUrl FROM url WHERE longUrl = '$longUrl' ";
                $request = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($request);
                $_SESSION["redir"] = "URL already shortened! Try visiting " . $_SERVER['HTTP_HOST'] . "/" . explode("/", $_SERVER['PHP_SELF'])[1] . "/" . $row["shortUrl"];
            }
        }
    } else {
        $_SESSION["redir"] = "$longUrl is not a valid URL! Be sure to include the URL protocol! (http, https)";
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}


?>

<head>
    <?php include("head.html"); ?>
</head>

<body>


    <form action="index.php" method="post">
        <h1>Insert URL</h1>
        <label>
            <p>
                <input type="text" name="urlInput" placeholder="http://www.example.com" required autofocus></input>
            </p>
        </label>
        <input type="submit" value="Submit">


        <div>
            <br>
            <?php

            if (isset($_SESSION["redir"])) {
                echo $_SESSION["redir"] . "<br><br>";
                unset($_SESSION["redir"]);
            }

            if (isset($table)) {
                echo $table;
            }

            ?>


        </div>
    </form>
</body>


</html>