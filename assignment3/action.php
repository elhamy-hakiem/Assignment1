<?php
if($_SERVER['REQUEST_METHOD']=='POST')
{
    $errors = [];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $linkedUrl = $_POST['linkedUrl'];
    if(empty($name))
    {
        $errors[] = "name is required";
    }
   else
   {
       if(is_string($name))
       {
           
            if(strlen($name)<3 || strlen($name) > 20)
            {
                $errors[]="Name Limit Must Be Between 3 and 20 ";
            }
            else
            {
                if(!ctype_alpha($name))
                {
                    $errors[]="Name Must Be letters only";
                }
            }
       }
       else
       {
           $errors[]="Name Must Be String";
       }
   }
   if(empty($email))
   {
    $errors[] = "Email Adress is required";
   }
   if(empty($linkedUrl))
   {
    $errors[] = "Linkedin Url is required";
   }
   if(count($errors) != 0)
   {
       echo "You Have Some Errors : <br>";
       foreach($errors as $error)
       {
           echo $error."<br>";
       }
   }
   else
   {
       echo "Form Validate";
   }
  
}

?>