<?php
#require Database file connection . . .
require "./includes/dbConnection.php";
#require Header File . . . 
require "./includes/header.php";
?>
<!-- Start Add Design     -->
<div class="container">
    <!-- Start Show Header  -->
    <div class="page-header">
        <h1 class="text-center">MY Data</h1>
    </div>
    <!-- End Show Header  -->

    <!-- Start Show All Blogs  -->
    <div class='container pt-2'>
        <div class='public-bg'>
            <table id="table_id" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Article</th>
                        <th>Granularity</th>
                        <th>Timestamp</th>
                        <th>Access</th>
                        <th>Agent</th>
                        <th>Views</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                echo "<tbody>";
                    $sql = "SELECT * FROM `items`";
                    $resultObj = mysqli_query($con,$sql);
                    if(mysqli_num_rows($resultObj) > 0)
                    {
                        while($item = mysqli_fetch_assoc($resultObj))
                        {
                            echo "<tr>";
                                echo "<td>".$item['id']."</td>";
                                echo "<td>".$item['project']."</td>";
                                echo "<td>".$item['article']."</td>";
                                echo "<td>".$item['granularity']."</td>";
                                echo "<td><i class='fas fa-clock'></i> ".date("Y-m-d",$item['timestamp'])."</td>";
                                echo "<td>".$item['access']."</td>";
                                echo "<td>".$item['agent']."</td>";
                                echo "<td><i class='fa fa-eye'></i> ".$item['views']."</td>";
                                // Delete And Edit Buttons 
                                echo "<td>";
                                    #Edit Button . . . 
                                    echo "<a href='edit.php?itemId=".$item['id']."' 
                                            class='btn btn-success btn-sm mx-1'>
                                            <i class='fa fa-edit'></i> Edit
                                        </a>";
                                    #Delete Button . . .
                                    echo "<a href ='delete.php?itemId=".$item['id']."' 
                                            class='btn btn-danger btn-sm mx-1'>
                                            <i class='fas fa-times'></i> Delete
                                        </a>";

                                echo "</td>";
                            echo "</tr>";
                        }
                    }
                echo "</tbody>";
                ?>
            </table>
        </div>
    </div>  
<!-- End Show All Blogs  -->
    
</div>
<!-- End Add Design     -->

<?php
require "./includes/closeConnection.php";
require "./includes/footer.php";
?>