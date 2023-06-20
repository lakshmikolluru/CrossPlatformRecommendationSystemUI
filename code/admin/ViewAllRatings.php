<?php
session_start();

include "../database/AdminQueries.php";
include "../database/RatingsQueries.php";

$adminID=-1;
/*Check if the cookie token is present.*/
if(isset($_COOKIE['admin_cprs_token']))    {
    $arr=isAdminAlreadyLoggedIn(session_id(), $_COOKIE['admin_cprs_token']);
    //echo sizeof($arr);
    if(sizeof($arr)>0 && $arr['adminID']!="") {
        $_SESSION["admin_id"]=$adminID=$arr['adminID'];
    }
    else   {
        header("Location: AdminLogin.php");
    }
}else   {
    header("Location: AdminLogin.php");
}

if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_POST['delete']))  {
        $arr=explode(", ", $_POST['delete']);
        $userID     =   $arr[0];
        $contentID  =   $arr[1];
        removeRating($userID, $contentID);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>View All Ratings</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="../Window.css" />
        <link rel="stylesheet" href="../Elements.css" />
        <link rel="stylesheet" href="../templates/Navigation.css" />
        <link rel="stylesheet" href="../templates/AdminTable.css" />
        <script src="../templates/CloneTemplate.js"></script>
        <style>
        p {
            text-align: center;
            font-size: 32px;
        }
        full-page-content {
            justify-content: center;
        }
        </style>
    </head>
    <body>
        <!-- Navigation Bar, Background -->
        <?php
        include "../templates/AdminNavigation.php";
        include "../templates/Background.php";
        ?>
        <script>
            show("admin_navigation_with_buttons_template");
            show("background_template");
        </script>

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="main-2 col-12"> 
            <div class="full-page-content col-12">
                <div class="page-title">View All Ratings</div>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                        style="padding-top: 30px">
                    <table id="table_style">
                        <tr>
                            <th>User ID</th>
                            <th>Content ID</th>
                            <th>Rating</th>
                            <th>Rated At</th>
                            <th></th>
                        </tr>
                            
                    <?php
                    $results=getAllRatings();
                    foreach($results as $i => $row) {
                        /* output data of each row */
                        $userID = $row["userID"];
                        $contentID = $row["contentID"];
                        $rating = $row["rating"];
                        $ratedAt = $row["ratedAt"];

                        /* Displaying all the Ratings */
                        echo '
                        <tr>
                            <td>'.$userID.'</td>
                            <td>'.$contentID.'</td>
                            <td>'.$rating.'</td>
                            <td>'.$ratedAt.'</td>
                            <td>
                                <button type="submit" name="delete" value="'.$userID.', '.$contentID.'" ><span class="material-symbols-outlined">delete</span></button>
                            </td>
                        </tr>
                            ';
                    } /* end of while */
                    ?>
                    </table>
                </form>
            </div>
        </main>
    </body>
</html>