<?php

function generateRandomToken() {
    $keyspace=str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $tokenLen=20;
    $token=[];
    $max=mb_strlen($keyspace, '8bit')-1;
    for($i=0; $i<$tokenLen; $i++)
        $token[]=$keyspace[random_int(0, $max)];
    return implode('', $token);
}


function insertUser($name, $email, $password)   {

    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    /*Encrypting password*/
    // $password=encryptValue($password, $conn);

    /*Inserting the user into the database.*/
    $q="INSERT INTO User (Name, Email, Password) VALUES ('$name', '$email', '$password');";
    $result=FALSE;
    if(mysqli_query($conn, $q)) $result=TRUE;

    mysqli_close($conn);
    return $result;
}

function isUserAlreadyLoggedIn($sessionID, $token) {
    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    /*Decrypting token*/
    // $token=decryptValue($token, $conn);

    $q="SELECT userID, isNewUser FROM User WHERE sessionID LIKE '$sessionID' AND token LIKE '$token';";
    $row=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $row=mysqli_fetch_assoc($r);
        // print_r($row);
    }

    mysqli_close($conn);
    return $row;
}

function getUserID($email)   {
    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $q="SELECT userID, isNewUser, password FROM User ".
            "WHERE email LIKE '$email' AND isAccountDeleted=FALSE;";
    $row=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0)
        $row=mysqli_fetch_assoc($r);

    mysqli_close($conn);
    return $row;
}

function updateSessionIDAndToken($userID, $sessionID, $token)  {
    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    /*Encrypting password*/
    // $password=encryptValue($password, $conn);

    /*Inserting the user into the database.*/
    $q="UPDATE User SET sessionID='$sessionID', token='$token' WHERE userID=$userID;";
    $result=FALSE;
    if(mysqli_query($conn, $q)) $result=TRUE;

    mysqli_close($conn);
    return $result;
}

function accountExists($email)  {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT userID, name FROM User WHERE email LIKE '$email';";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $results=mysqli_fetch_assoc($r);
    }
    mysqli_close($conn);
    return $results;
}

function changeUserFromNewToExisting($userID)   {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="UPDATE User SET isNewUser=0 WHERE userID=$userID;";
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function getAllUsers()   {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    /* Getting all the users from the database.*/
    $q="SELECT U.userID, email, registrationDate, COUNT(R.rating) as countOfRatings ".
            "FROM User U LEFT JOIN Ratings R ON U.userID=R.userID WHERE isAccountDeleted ".
            "IS FALSE GROUP BY U.userID ORDER BY registrationDate DESC;";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}

function getUserDetails($userID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT userID, name, email, password, photo FROM User WHERE userID=$userID;";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $results=mysqli_fetch_assoc($r);
    }
    mysqli_close($conn);
    return $results;
}

function updateUserDetails($userID, $name, $email, $password, $photo) {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    if($photo)
        $q="UPDATE User SET name='$name', email='$email', password='$password', photo=$photo ".
                "WHERE userID=$userID;";
    else
        $q="UPDATE User SET name='$name', email='$email', password='$password' WHERE userID=$userID;";
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function deleteUser($userID) {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="UPDATE User SET name='', email='', password='', photo=0, isAccountDeleted=1, ".
            "sessionID='', token='' WHERE userID=$userID;";
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function updatePassword($userID, $password) {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="UPDATE User SET password='$password' WHERE userID=$userID;";
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

?>