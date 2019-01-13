
    <?php
    class ShowDBController
    {
        function index(){
    $users = array();

    $link = mysqli_connect("localhost", "root", "770770", "dbUsers");

        // Check connection
    if ($link === false)
    {
    die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    // Attempt select query execution
    $sql = "SELECT * FROM dbSite.tblLogin ORDER by `name`";
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($users, new User($row['userID'],$row['name'], $row['email'], $row['phone'], $row['dateOfBirth']
                    ,$row['termsAccepted'],$row['termsAcceptedTime'],$row['termsAcceptedIp']));
            }
            mysqli_free_result($result);
            echo json_encode($users);
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
    }
    }