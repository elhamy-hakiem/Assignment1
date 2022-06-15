<?php
#require Database file connection . . .
require "./includes/dbConnection.php";
#require Header File . . . 
require "./includes/header.php";

//Check if Get Request Is Numeric &Get value Of It
$itemId = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) :0;

$sql = "SELECT * FROM `items` WHERE `id` = $itemId";

$resultObj = mysqli_query($con,$sql);

if ( $resultObj -> num_rows > 0) 
{
    
    $dSql = "DELETE FROM `items` WHERE `id` = $itemId";
    $operation = mysqli_query($con,$dSql);
    if($operation)
    {
        echo '<div class="alert alert-success" role="alert">';
                echo "Data Delete Success";
            echo '</div>';
            echo "<hr>";
            header("refresh:1;url=displayData.php");
    }
    else
    {
        echo '<div class="alert alert-danger" role="alert">';
            echo "Data Not Deleted";
        echo '</div>';
        header("refresh:1;url=displayData.php");
    }
}




require "./includes/closeConnection.php";
require "./includes/footer.php";
?>