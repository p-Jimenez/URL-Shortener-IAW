<html>

<head>
</head>

<body>


    <form action="index.php" method="post">
        <label>
            <p>
                Introduce URL: <input type="text" name="urlInput" placeholder="http://www.example.com"></input>
                <input type="submit" value="Submit">
            </p>
        </label>
    </form>


    <?php


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
            while ($row = mysqli_fetch_assoc($result)) {
                echo "id: " . $row["id"] . " - shortUrl: " . $row["shortUrl"] . " - longUrl: " . $row["longUrl"] . " - urlCounter: " . $row["urlCounter"] . "<br>";
            }
        } else {
            echo "0 results";
        }
    }

    //echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];



    //generate short URL

    function getShortUrl($conn, $length = 3) {

        do {
            $key = bin2hex(random_bytes($length));
            $url = $key;
            $sql = "SELECT shortUrl FROM url WHERE shortUrl = '${url}' ";
            $request = mysqli_query($conn, $sql);
        } while (mysqli_num_rows($request) != 0);
        return $url;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $longUrl = trim($_POST["urlInput"]);
        if (filter_var($longUrl, FILTER_VALIDATE_URL)) {
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
                    echo "Redirect created! Visit " . $_SERVER['HTTP_HOST'] . "/" . explode("/", $_SERVER['PHP_SELF'])[1] . "/" . $shortUrl;
                    // output data of each row
                    //while($row = mysqli_fetch_assoc($result)) {
                    //  echo "id: " . $row["id"]. " - shortUrl: " . $row["shortUrl"]. " - longUrl: " . $row["longUrl"]. " - urlCounter: ". $row["urlCounter"] ."<br>";
                    //}
                } else {
                    $sql = "SELECT shortUrl FROM url WHERE longUrl = '$longUrl' ";
                    $request = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($request);
                    echo "URL already shortened! Try visiting " . $_SERVER['HTTP_HOST'] . "/" . explode("/", $_SERVER['PHP_SELF'])[1] . "/" . $row["shortUrl"];
                }
            }
        } else {
            echo "$longUrl is not a valid URL!";
        }
    
    } 

    ?>

</body>


</html>