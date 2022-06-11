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
                    <!-- Start Get Data From Text File -->
                    <?php 
                    #Read Data From Text File
                    $myBlogs = array();
                    $blogsData = array();
                    $myfile = fopen("myblogs.txt", "r") or die("Unable to open file!");
 
                    while (!feof($myfile)) {
                        $blogData = fgets($myfile);
                        $myBlogs[] =  explode("||",$blogData);
                    }
                    fclose($myfile);

                    
                    // Start Delete Blog 
                    $action = isset($_GET['action']) ? $_GET['action'] : '';
                    if($action == "delete")
                    {
                        $blogsInfo = array();
                         //Check if Get Request Is Numeric &Get value Of It
                        $blogId = isset($_GET['blogid']) && is_numeric($_GET['blogid']) ? intval($_GET['blogid']) :0;
                        foreach($myBlogs as $blog)
                        {
                            if(in_array($blogId,$blog))
                            {
                                continue;
                            }
                            else
                            {
                                $blogsInfo[] =  implode("||",$blog)."\n";
                            }
                            
                        }
                        // Full New Data IN Text File 
                        if(!empty($blogsInfo))
                        {
                            file_put_contents("myblogs.txt", "");
                            foreach($blogsInfo as $info)
                            {
                                $myfile = fopen("myblogs.txt", "a") or die("Unable to open file!");
                                fwrite($myfile, $info);
                                fclose($myfile);
                            }
                            header("refresh:1;url=displayBlogs.php");
                        }
                    }
                    // End Delete Blog 

                    if(!empty($myBlogs))
                    {
                        foreach($myBlogs as $key => $blog)
                        {
                            // var_dump();
                            if(!empty(trim($myBlogs[$key][0])))
                            {
                                $blogsData[]=
                                [
                                    "id"          => $blog[0],
                                    "title"       => $blog[1],
                                    "image"       => $blog[2],
                                    "content"     => $blog[3],
                                ];
                            }
                        }
                    }
                    else
                    {
                        echo "<div class='alert alert-danger text-center font-weight-bold mt-3'>
                            <strong >Sorry</strong> No Blogs Found !
                        </div>";
                    }
                    // End Get Data From Text File 
                    if(!empty($blogsData))
                    {
                        foreach($blogsData as $data)
                        {
                            if(!empty($data['id']))
                            {
                                // Start Show Blog  
                                echo '<div class="col-lg-3 col-md-4 col-sm-6">';
                                    echo '<div class="card blog-box mt-2">';
                                        echo "<img class='img-thumbnail' src='./uploads/".$data['image']."' alt ='Item Image'/>"; 
                                        echo '<div class="card-body pt-2">';
                                            echo '<h3 class="card-title my-1">'.$data['title'].'</h3>';
                                            echo '<p class="card-text">'.$data['content'].'</p>';
                                        echo '</div>';
                                        echo '<a href ="displayBlogs.php?action=delete&blogid='.$data['id'].'" class="btn btn-danger btn-sm fs-5 fw-bold mx-2 mb-2">Delete Blog</a>';
                                    echo '</div>';
                                echo ' </div>';
                                // End Show Blog 
                            }
                            else
                            {
                                echo "<div class='alert alert-danger text-center font-weight-bold mt-3'>
                                        <span class='badge badge-pill badge-danger'>Sorry</span> No Blogs Found !
                                    </div>";
                            }
                        }
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