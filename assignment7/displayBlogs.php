<?php require "dbConnect.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Blogs</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- fonts style  -->
    <link href="https://fonts.googleapis.com/css2?
    family=Archivo+Narrow:ital,wght@1,500&family=Chicle&family=Oswald:wght@600&display=swap" rel="stylesheet">
    <!-- bootstrap links style  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/blog.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Start Add Design     -->
    <div class="container">
        <!-- Start Show Header  -->
        <div class="page-header">
            <h1 class="text-center">MY BLOGS</h1>
        </div>
        <!-- End Show Header  -->

        <!-- Start Show All Blogs  -->
        <div class='container pt-2'>
            <div class='public-bg'>
                <div class='row'>
                    <?php 
                    // Start Delete Blog 
                    $action = isset($_GET['action']) ? $_GET['action'] : '';
                    if($action == "delete")
                    {
                         //Check if Get Request Is Numeric &Get value Of It
                        $blogId = isset($_GET['blogid']) && is_numeric($_GET['blogid']) ? intval($_GET['blogid']) :0;
                        $sql = "select `id`,`image` from blogs where id = $blogId";

                        $resultObj = mysqli_query($con, $sql);
                        if ( $resultObj -> num_rows > 0) 
                        {
                            $blog = mysqli_fetch_assoc($resultObj);
                            if (file_exists("./uploads/".$blog['image'])) 
                            {
                                unlink("./uploads/".$blog['image']);
                                $sql = "delete from blogs where id = $blogId"; 
                                $op = mysqli_query($con, $sql);
                                if($op)
                                {
    
                                    header("refresh:.5;url=displayBlogs.php");
                                }
                            } 
                            else 
                            {
                                echo '<div class="alert alert-danger" role="alert">';
                                        echo "Image Not Deleted";
                                echo '</div>'; 
                            }
                        }
                    }
                    // End Delete Blog 

                    // Start Get Data From Database 
                    $sql = "select * from blogs";

                    $resultObj = mysqli_query($con, $sql);
                    if ( $resultObj -> num_rows > 0) 
                    {
                        while( $blog = mysqli_fetch_assoc($resultObj))
                        {
                            // Start Show Blog  
                            echo '<div class="col-lg-3 col-md-4 col-sm-6">';
                                echo '<div class="card blog-box mt-2">';
                                    echo "<img class='img-thumbnail' src='./uploads/".$blog['image']."' alt ='Blog Image'/>"; 
                                    echo '<div class="card-body pt-2">';
                                        echo '<h3 class="card-title my-1">'.$blog['title'].'</h3>';
                                        echo '<p class="card-text">'.$blog['content'].'</p>';
                                    echo '</div>';
                                    #Edit Button . . . . 
                                    echo "<a href='edit.php?blogid=".$blog['id']."' 
                                            class='btn btn-success btn-sm fs-5 fw-bold mx-2 mb-2'><i class='fa fa-edit'></i> Edit
                                        </a>";
                                    #Delete Button . . . . 
                                    echo '<a href ="displayBlogs.php?action=delete&blogid='.$blog['id'].'" 
                                            class="btn btn-danger btn-sm fs-5 fw-bold mx-2 mb-2">
                                            <i class="fas fa-times"></i>Delete
                                            </a>';
                                echo '</div>';
                            echo ' </div>';
                            // End Show Blog 
                        }
                    }
                    else
                    {
                        echo "<div class='alert alert-danger text-center font-weight-bold mt-3'>
                                <span class='badge badge-pill badge-danger'>Sorry</span> No Blogs Found !
                            </div>";
                    }
                ?>
                </div>
                
            </div>
        </div>  
    <!-- End Show All Blogs  -->

    </div>
    <!-- End Add Design     -->


</body>

</html>