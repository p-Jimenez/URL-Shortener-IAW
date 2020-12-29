<?php
        if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['shortUrl'])){
           
            $server = "localhost";
            $username = "root";
            $pass = "root";
            $db = "url_shortener";
           
            $conn = mysqli_connect($server, $username, $pass, $db);

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                
                $shortUrl = $_GET['shortUrl'];

                $sql = "SELECT * FROM url WHERE shortUrl = '$shortUrl'";

                $result = mysqli_query($conn, $sql);
        
                if ($result) {

                    $row = mysqli_fetch_assoc($result);
                    $url = $row["longUrl"];

                    $sql = "UPDATE url SET urlCounter = urlCounter + 1 WHERE '$shortUrl' = shortUrl";
                    mysqli_query($conn, $sql);

                    header("Location: $url");
                    //echo "id: " . $row["id"] . " - shortUrl: " . $row["shortUrl"] . " - longUrl: " . $row["longUrl"] . " - urlCounter: " . $row["urlCounter"] . "<br>";

                } else {
                    echo "Unknown page!";
                }
            }

        } else {
            
        }
    ?>

<html>

<head>
</head>

<body>
    <h1>Hola </h1>
    <p>
    <?php
        echo "Parametro shortUrl: " . $_GET['shortUrl'];
    ?></p>

</body>

</html>