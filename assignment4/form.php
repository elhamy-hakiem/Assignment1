<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container w-50 p-3">

        <!-- Start Validation Data  -->
        <?php
            function cleanInput($input)
            {
                $input = trim($input);
                $input = stripslashes($input);
                $input = strip_tags($input);
                return $input;
            }

            if($_SERVER['REQUEST_METHOD'] == "POST")
            {
                $formErrors =array();
                $successMessage ='';

                $name        = cleanInput($_POST['name']);
                $email       = cleanInput($_POST['email']);
                $password    = cleanInput($_POST['password']);
                $address     = cleanInput($_POST['address']);
                $linkedinUrl = cleanInput($_POST['linkedinUrl']);
                $gender      = isset($_POST['gender']) ? $_POST['gender'] : 0;

                $cv          = $_FILES['cv'];
                $cvName      = $_FILES['cv']['name'];
                $cvType      = $_FILES['cv']['type'];
                $cvTmp       = $_FILES['cv']['tmp_name'];
                $cvSize      = $_FILES['cv']['size'];
                $cvError     = $_FILES['cv']['error'];
                $cvFinalName = '';
                 

                # validate name . . .
                if(empty($name))
                {
                    $formErrors["name"] = "Name is Required ";
                }
                elseif(!ctype_alpha(str_replace(' ','',$name)))
                {
                    $formErrors["name"] = "Name Must Be Letter ";
                }

                #Validate Email . . .
                if(empty($email))
                {
                    $formErrors["email"] = "Email is Required ";
                }
                else
                {
                    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
                    {
                        $SuggestedEmail = filter_var($email,FILTER_SANITIZE_EMAIL);
                        if(filter_var($SuggestedEmail,FILTER_VALIDATE_EMAIL))
                        {
                            $formErrors["email"] = "Suggested Email To Use : ".$SuggestedEmail;
                        }
                        else
                        {
                            $formErrors["email"] ="Please Insert Valid Email ";
                        }
                    }
                }

                #Validate Password . . .
                if(empty($password))
                {
                    $formErrors["password"] = "Password is Required ";
                }
                elseif(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password))
                {
                    $formErrors["password"] ="Password Must Be have at least one number and 
                                                at least 
                                              one letter and there have to be 8-12 characters  ";
                }

                #Validate Address . . .
                if(empty($address))
                {
                    $formErrors["address"] = "Address is Required ";
                }
                else
                {
                    if(strlen($address) < 10)
                    {
                        $formErrors["address"] = "Address Must Be 10 Character at Least";
                    }
                }

                #Validate Linkedin URL . . .
                if(empty($linkedinUrl))
                {
                    $formErrors["URL"] = "Linkedin URL  Is Required ";
                }
                else
                {
                    if(!filter_var($linkedinUrl,FILTER_VALIDATE_URL))
                    {
                        $formErrors["URL"] = "URL  Is Not Valid ";
                    }
                    else
                    {
                        if(stripos($linkedinUrl,"https://www.linkedin.com/in/") != 0)
                        {
                            $formErrors["URL"] = "Linkedin URL  Is Not Valid ";
                        }
                    }
                }

                #Validate Gender . . .
                if(empty($gender))
                {
                    $formErrors["gender"] = "Gender is Required ";
                }
                else
                {
                    $genderType = ["male","female"];
                    if(!in_array($gender,$genderType))
                    {
                        $formErrors["gender"] = "Please Choose Valid Gender";
                    }
                }

                #validate CV . . . 
                if($cvError == 4)
                {
                    $formErrors["cv"] = "CV IS  REQUIRED";
                }
                else
                {
                    #get Extension . . . 
                    $extensionArray = explode('/', $cvType);
                    $extension =  strtolower( end($extensionArray));
                    // List Of Allowed File Type To Upload 
                    $cvAllowedExtension = array("pdf");
                    
                    if(!in_array($extension,$cvAllowedExtension))
                    {
                        $formErrors["cv"] = "This Extension is Not Allowed";
                    }
                    if($cvError == 1)
                    {
                        $formErrors["cv"] ='The file size exceeds the value specified.';
                    }
                    if($cvError == 2)
                    {
                        $formErrors["cv"] ='The file size exceeds the value of the directive.';
                    }
                    if($cvError == 3)
                    {
                        $formErrors["cv"] ='The file is not completely uploaded.';
                    }
                    if($cvError == 6)
                    {
                        $formErrors["cv"] ='The temporary directory does not exist.';
                    }
                    if($cvSize >  5000000)
                    {
                        $formErrors["cv"] ="CV Can't Be Larger Than <strong>5000 kB</strong>";
                    }
                    else
                    {
                        $cvFinalName = md5(rand(0,10000000000)).'_'.$cvName;
                    }
                }
                
                if(empty($formErrors))
                {
                    if(is_uploaded_file($cvTmp))
                    {
                        move_uploaded_file($cvTmp,"uploads/".$cvFinalName);
                        $successMessage ="Submit Success";
                    }
                }


            }
        ?>
        <!-- End Validation Data  -->

        <!-- Start Show Error  -->
        <?php if(!empty($formErrors)) {?>  
            <div class="alert alert-danger" role="alert">
               <?php 
                    foreach($formErrors as $key => $error )
                    {
                        echo "<strong>".$key.": </strong>".$error;
                    }
               ?>
            </div> 
        <?php }
            else{
                if(isset($successMessage))
                {
                    echo '<div class="form-error alert alert-success text-center">';  
                        echo '<div>'.$successMessage.'</div>'; 
                    echo ' </div>';
                } 
            }
        ?>
        <!-- End Show Error  -->

        <!-- Start Header  -->
         <h2 class="text-center py-3 bg-light ">Register</h2>
        <!-- End Header  -->

        <!-- Start Form Data  -->
        <form method="POST" <?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> enctype="multipart/form-data" >
            <!-- Start Insert Name  -->
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name">
                </div>
            </div>
            <!-- End Insert Name  -->

            <!-- Start Insert Email  -->
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="email">
                </div>
            </div>
            <!-- End Insert Email  -->

             <!-- Start Insert Password  -->
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <!-- End Insert Password  -->

            <!-- Start Insert Address  -->
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="address">
                </div>
            </div>
            <!-- End Insert Address  -->

            <!-- Start Upload CV  -->
            <div class="row mb-3">
                <label for="formFile" class="col-sm-2 col-form-label">Upload CV</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="cv">
                </div>
            </div>
            <!-- End Upload CV  -->

            <!-- Start Insert Linkedin URL  -->
            <div class="row mb-3">
                <label for="basic-url" class="col-sm-2 col-form-label">Linkedin URL</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control"  name="linkedinUrl">
                </div>
            </div>
            <!-- End Insert Linkedin URL  -->

            <!--Start Choose Your Gender  -->
            <fieldset class="row mb-3">
                <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender"  value="male" checked>
                    <label class="form-check-label" for="gridRadios1">
                        Male
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender"  value="female">
                    <label class="form-check-label" for="gridRadios2">
                        Female
                    </label>
                </div>

                </div>
            </fieldset>
            <!--End Choose Your Gender  -->

            <button type="submit" class="btn btn-primary px-4">Sign in</button>
        </form>
        <!-- End Form Data  -->
    </div>
</body>

</html>