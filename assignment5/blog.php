<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/blog.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
     <!-- Start Add Design     -->
     <div class="container">
            <!-- Start Validation Data  -->
            <?php
            function cleanInput($input)
            {
                $input = trim($input);
                $input = stripslashes($input);
                $input = strip_tags($input);
                return $input;
            }
            if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addBlog']))
            {
                $formErrors =array();

                $title      = cleanInput($_POST['title']);
                $content    = cleanInput($_POST['content']);

                // Image Data 
                $blogImage      = $_FILES['blogImage'];
                $blogImageName  = $_FILES['blogImage']['name'];
                $blogImageType  = $_FILES['blogImage']['type'];
                $blogImageTmp   = $_FILES['blogImage']['tmp_name'];
                $blogImageError = $_FILES['blogImage']['error'];
                $blogImageSize  = $_FILES['blogImage']['size'];

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
                    $formErrors['Image'] = "Please Choose Image";
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
                    #Create New Name For Blog Image . . . 
                    $newImageName = md5(rand(0,10000000000)).'_'.$blogImageName;
                    #Start Upload Image . . . 
                    if(is_uploaded_file($blogImageTmp))
                    {
                        move_uploaded_file($blogImageTmp,"./uploads/".$newImageName);
                    }

                    #Save Data In Text File
                    $myfile = fopen("myblogs.txt", "a") or die("Unable to open file!");
                    $blogId = intval(rand(10,200));
                    $myBlog = $blogId."||".$title."||".$newImageName."||".$content."\n";

                    fwrite($myfile,$myBlog);

                    fclose($myfile);
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
            <?php }?>
            <!-- End Show Error  -->
            
            <div class="card-body pb-0">
                <h1 class="edit-header text-center py-3">Add New Blog</h1><hr>

                <!-- Start Add Form  -->
                <form class="p-3"  method="POST" enctype="multipart/form-data">
                    <!-- Start Title Field  -->
                    <div class="form-group row  mb-2">
                        <label class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" autocomplete="off"  placeholder="Blog Title" >
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
                            <textarea class="form-control" name="content"  cols="30" rows="8"  placeholder="Content Your Blog...."></textarea>
                        </div>
                    </div>
                    <!-- End Content Field  -->

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" name="addBlog" class="btn btn-danger btn-sm "><i class="fa fa-plus"></i> Add Blog</button>
                        </div>
                    </div>
                </form>
                <!-- End Add Form  -->

            </div>
        </div>
    </div>
    <!-- End Add Design     -->


</body>

</html>