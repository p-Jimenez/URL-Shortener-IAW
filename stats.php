
<?php

    var_dump($_GET['shortUrl']);

    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['shortUrl']) && strlen($_GET['shortUrl']) == 12){

        $server = "localhost";
        $username = "root";
        $pass = "root";
        $db = "url_shortener";
       
        $conn = mysqli_connect($server, $username, $pass, $db);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            
            $shortUrl = explode("/",$_GET['shortUrl'])[1];

            $sql = "SELECT * FROM url WHERE shortUrl = '$shortUrl'";

            $result = mysqli_query($conn, $sql);
    
            if ($result) {

                $row = mysqli_fetch_assoc($result);

                echo $row["urlCounter"];

                //echo "id: " . $row["id"] . " - shortUrl: " . $row["shortUrl"] . " - longUrl: " . $row["longUrl"] . " - urlCounter: " . $row["urlCounter"] . "<br>";

            } else {

            }
        }

    } else {
        echo "holap";
        $index = "..";
        header("Location: $index");
    }

?>
