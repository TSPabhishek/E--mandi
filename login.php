 <?php
//     session_start();

//     $user = dataFilter($_POST['uname']);
//     $pass = $_POST['pass'];
//     $category = dataFilter($_POST['category']);

//     require '../db.php';

// if($category == 1)
// {
//     $sql = "SELECT * FROM farmer WHERE fusername='$user'";
//     $result = mysqli_query($conn, $sql);
//     $num_rows = mysqli_num_rows($result);

//     if($num_rows == 0)
//     {
//         $_SESSION['message'] = "Invalid User Credentialss!";
//         header("location: error.php");
//     }

//     else
//     {
//         $User = $result->fetch_assoc();

//         if (password_verify($_POST['pass'], $User['fpassword']))
//         {
//             $_SESSION['id'] = $User['fid'];
//             $_SESSION['Hash'] = $User['fhash'];
//             $_SESSION['Password'] = $User['fpassword'];
//             $_SESSION['Email'] = $User['femail'];
//             $_SESSION['Name'] = $User['fname'];
//             $_SESSION['Username'] = $User['fusername'];
//             $_SESSION['Mobile'] = $User['fmobile'];
//             $_SESSION['Addr'] = $User['faddress'];
//             $_SESSION['Active'] = $User['factive'];
//             $_SESSION['picStatus'] = $User['picStatus'];
//             $_SESSION['picExt'] = $User['picExt'];
//             $_SESSION['logged_in'] = true;
//             $_SESSION['Category'] = 1;
//             $_SESSION['Rating'] = 0;

//             if($_SESSION['picStatus'] == 0)
//             {
//                 $_SESSION['picId'] = 0;
//                 $_SESSION['picName'] = "profile0.png";
//             }
//             else
//             {
//                 $_SESSION['picId'] = $_SESSION['id'];
//                 $_SESSION['picName'] = "profile".$_SESSION['picId'].".".$_SESSION['picExt'];
//             }
//             //echo $_SESSION['Email']."  ".$_SESSION['Name'];

//             header("location: profile.php");
//         }
//         else
//         {
//             //echo mysqli_error($conn);
//             $_SESSION['message'] = "Invalid User Credentials!";
//             header("location: error.php");
//         }
//     }
// }
// else
// {
//     $sql = "SELECT * FROM buyer WHERE busername='$user'";
//     $result = mysqli_query($conn, $sql);
//     $num_rows = mysqli_num_rows($result);

//     if($num_rows == 0)
//     {
//         $_SESSION['message'] = "Invalid User Credentialss!";
//         header("location: error.php");
//     }

//     else
//     {
//         $User = $result->fetch_assoc();

//         if (password_verify($_POST['pass'], $User['bpassword']))
//         {
//             $_SESSION['id'] = $User['bid'];
//             $_SESSION['Hash'] = $User['bhash'];
//             $_SESSION['Password'] = $User['bpassword'];
//             $_SESSION['Email'] = $User['bemail'];
//             $_SESSION['Name'] = $User['bname'];
//             $_SESSION['Username'] = $User['busername'];
//             $_SESSION['Mobile'] = $User['bmobile'];
//             $_SESSION['Addr'] = $User['baddress'];
//             $_SESSION['Active'] = $User['bactive'];
//             $_SESSION['logged_in'] = true;
//             $_SESSION['Category'] = 0;

//             //echo $_SESSION['Email']."  ".$_SESSION['Name'];

//             header("location: profile.php");
//         }
//         else
//         {
//             //echo mysqli_error($conn);
//             $_SESSION['message'] = "Invalid User Credentials!";
//             header("location: error.php");
//         }
//     }
// }

//     function dataFilter($data)
//     {
//     	$data = trim($data);
//      	$data = stripslashes($data);
//     	$data = htmlspecialchars($data);
//       	return $data;
//     }















session_start();

// Function to filter input data
function dataFilter($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Filter and retrieve input data
    $user = dataFilter($_POST['uname']);
    $pass = dataFilter($_POST['pass']);
    $category = dataFilter($_POST['category']);

    // Include the database connection file
    require '../db.php';

    // Check the category
    if ($category == 1) {
        // Query to select farmer by username
        $sql = "SELECT * FROM farmer WHERE fusername=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        // Query to select buyer by username
        $sql = "SELECT * FROM buyer WHERE busername=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }

    // Check if user exists
    if (mysqli_num_rows($result) == 1) {
        $User = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($pass, $User['fpassword'])) {
            // Set session variables
            $_SESSION['id'] = $User['fid'];
            $_SESSION['Hash'] = $User['fhash'];
            $_SESSION['Password'] = $User['fpassword'];
            $_SESSION['Email'] = $User['femail'];
            $_SESSION['Name'] = $User['fname'];
            $_SESSION['Username'] = $User['fusername'];
            $_SESSION['Mobile'] = $User['fmobile'];
            $_SESSION['Addr'] = $User['faddress'];
            $_SESSION['Active'] = $User['factive'];
            $_SESSION['logged_in'] = true;
            $_SESSION['Category'] = ($category == 1) ? 1 : 0;

            // Redirect to profile page
            header("location: profile.php");
            exit();
        } else {
            // Invalid password
            $_SESSION['message'] = "Invalid Password!";
        }
    } else {
        // User not found
        $_SESSION['message'] = "User not found!";
    }

    // Redirect to error page
    header("location: error.php");
    exit();
}



?> 
