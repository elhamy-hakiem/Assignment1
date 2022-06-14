<?php require "dbConnect.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/blog.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Start Edit Design     -->
    <div class="container">
            <!-- Start Validation Data  -->
            <?php
            $formErrors =array();
            $successMsg ="";
            //Check if Get Request Is Numeric &Get value Of It
            $blogId = isset($_GET['blogid']) && is_numeric($_GET['blogid']) ? intval($_GET['blogid']) :0;
            // Fetch Row From Database Where Id Equal BLog Id 
            $sql = "select * from blogs where id = $blogId";

            $resultObj = mysqli_query($con, $sql);
            if ( $resultObj -> num_rows > 0) 
            {
                $blog = mysqli_fetch_assoc($resultObj);
            }
            else
            {
                header("location:displayBlogs.php") ;
                exit();
            }

            function cleanInput($input)
            {
                $input = trim($input);
                $input = stripslashes($input);
                $input = strip_tags($input);
                return $input;
            }
            if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editBlog']))
            {
                $title      = cleanInput($_POST['title']);
                $content    = cleanInput($_POST['content']);

                // Image Data 
                $blogImage      = $_FILES['blogImage'];
                $blogImageName  = $_FILES['blogImage']['name'];
                $blogImageType  = $_FILES['blogImage']['type'];
                $blogImageTmp   = $_FILES['blogImage']['tmp_name'];
                $blogImageError = $_FILES['blogImage']['error'];
                $blogImageSize  = $_FILES['blogImage']['size'];
                $newImageName   = "";

                #Image Extension
                $imageExtensionArray = explode("/",$blogImageType);
                $imageExtension      = strtolower(end($imageExtensionArray));

                # List Of Allowed File Type To Upload 
                $imageAllowedExtension = array("jpeg","jpg","png","gif");

                #validate Title . . .
                if(empty($title))
                {
                    $formErrors['title'] = "Title is Required";
                }
                elseif(!is_string($title))
                {
                    $formErrors['title'] = "Title Must Be String";
                }
                elseif(!ctype_alpha(str_replace(' ','',$title)))
                {
                    $formErrors['title'] = "Title Must Be Letter";
                }

                #validate Content . . .
                if(empty($content))
                {
                    $formErrors['content'] = "Content is Required";
                }
                elseif(!is_string($content))
                {
                    $formErrors['content'] = "Content Must Be String";
                }
                elseif(strlen($content) < 50)
                {
                    $formErrors['content'] = "Content Must Be Greater Than 50 Character";
                }

                #validate Image . . . 
                if($blogImageError == 4)
                {
                    $newImageName = $blog['image'];
                }
                else
                {
                    if(!in_array($imageExtension,$imageAllowedExtension))
                    {
                        $formErrors['Image'] =  "This Extension is Not Allowed";
                    }
                    else
                    {
                        if($blogImageError == 1)
                        {
                            $formErrors['Image'] = "The file size exceeds the value specified";
                        }
                        if($blogImageError == 2)
                        {
                            $formErrors['Image'] = "The file size exceeds the value of the directive";
                        }
                        if($blogImageError == 3)
                        {
                            $formErrors['Image'] = "The file is not completely uploaded";
                        }
                        if($blogImageError == 6)
                        {
                            $formErrors['Image'] = "The temporary directory does not exist";
                        }
                        if($blogImageSize > 512000)
                        {
                            $formErrors['Image'] = "Image Can't Be Larger Than <strong>500 kB</strong>";
                        }
                    }
                }

                #Save Data In File ....
                if(empty($formErrors))
                {
                    if(empty($newImageName))
                    {
                        #Create New Name For Blog Image . . . 
                        $newImageName = md5(rand(0,10000000000)).'_'.$blogImageName;
                    }
                    #Start Upload Image . . . 
                    if(is_uploaded_file($blogImageTmp))
                    {
                        move_uploaded_file($blogImageTmp,"./uploads/".$newImageName);
                    }

                    $sql = "UPDATE `blogs` SET `title`='$title',`content`='$content',`image`='$newImageName' WHERE `id` = $blogId";

                    $op =  mysqli_query($con, $sql);
            
                    if ($op) {
                        $successMsg = "Blog Edit Success";
                    } else {
                        $formErrors['Database_Error'] = "Failed , " . mysqli_error($con);
                    }
                }

            }
        ?>
        <!-- End Validation Data  -->

        <div class="card my-4">
            <!-- Start Show Error  -->
            <?php if(!empty($formErrors)) {?>  
                <div class="alert alert-danger" role="alert">
                <?php 
                        foreach($formErrors as $key => $error )
                        {
                            echo "<strong>".$key.": </strong>".$error.". ";
                        }
                ?>
                </div> 
            <?php }
            else
            {
                if(!empty($successMsg))
                {
                    echo'<div class="alert alert-success text-center" role="alert">';
                            echo $successMsg;
                    echo "</div>"; 
                    header("refresh:1;url=displayBlogs.php");
                    exit ();
                }
            }
            ?>
            <!-- End Show Error  -->
            <div class="card-body pb-0">
                <h1 class="edit-header text-center py-3">Edit Blog</h1><hr>
                <!-- Start Edit Form  -->
                <img style="width: 22rem; float:left;" class="card-img-top" src="./uploads/<?php echo $blog['image']; ?>" alt="Card image cap">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?blogid='.$blog['id']; ?>"  style="width: 40rem; float:right;" class="p-3"  method="POST" enctype="multipart/form-data">
                    <!-- Start Title Field  -->
                    <div class="form-group row  mb-2">
                        <label class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $blog['title'];?>" name="title" autocomplete="off"  placeholder="Blog Title" >
                        </div>
                    </div>
                    <!-- End Title Field  -->

                    <!-- Start Image Field  -->
                    <div class="form-group row mb-2">
                        <label class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                            <!-- Start Upload item Image  -->
                            <div class='custom-upload'>
                                <span>Choose Photo</span>
                                <input type="file"  name="blogImage">
                            </div>
                            <!-- End Upload item Image  -->
                        </div>
                    </div>
                    <!-- End Image Field  -->

                    <!-- Start Content Field  -->
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Content</label>
                        <div class="col-sm-10">
                            <textarea class="form-control"  name="content"  cols="30" rows="8"  placeholder="Content Your Blog...."><?php echo $blog['content'];?></textarea>
                        </div>
                    </div>
                    <!-- End Content Field  -->

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" name="editBlog" class="btn btn-success btn-sm px-3 py-2 "><i class='fa fa-edit'></i> Save</button>
                        </div>
                    </div>
                </form>
                <!-- End Edit Form  -->

            </div>
        </div>
    </div>
    <!-- End Edit Design     -->


</body>

</html>