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
    $item = mysqli_fetch_assoc($resultObj);
}
#Function Clean Input Data
function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = strip_tags($input);
    return $input;
}
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editItem']))
{
    $project       = cleanInput($_POST['project']);
    $article       = cleanInput($_POST['article']);
    $granularity   = cleanInput($_POST['granularity']);
    $timestamp     = cleanInput($_POST['timeStamp']);
    $access        = cleanInput($_POST['access']);
    $agent         = cleanInput($_POST['agent']);

    #Validate Project Input . . .
    if(empty($project))
    {
        $inputErrors['project']="Project Required";
    }
    elseif(!is_string($project))
    {
        $inputErrors['project']="Project must be String";
    }
    #Validate Article Input . . .
    if(empty($article))
    {
        $inputErrors['article']="article Required";
    }
    elseif(!is_string($article))
    {
        $inputErrors['article']="article must be String";
    }
    #Validate granularity Input . . .
    if(empty($granularity))
    {
        $inputErrors['granularity']="granularity Required";
    }
    elseif(!is_string($granularity))
    {
        $inputErrors['granularity']="granularity must be String";
    }
    #Validate timestamp Input . . .
    if(empty($timestamp))
    {
        $inputErrors['timestamp']="timestamp Required";
    }
    elseif(!is_numeric($timestamp))
    {
        $inputErrors['timestamp']="timestamp must be Number";
    }
    #Validate access Input . . .
    if(empty($access))
    {
        $inputErrors['access']="access Required";
    }
    elseif(!is_string($access))
    {
        $inputErrors['access']="access must be String";
    }
    #Validate agent Input . . .
    if(empty($agent))
    {
        $inputErrors['agent']="agent Required";
    }
    elseif(!is_string($agent))
    {
        $inputErrors['agent']="agent must be String";
    }
    if(!empty($inputErrors))
    {
        echo '<div class="alert alert-danger" role="alert">';
            foreach($inputErrors as $key => $error)
            {
                echo "<strong>".$key."</strong> : ".$error."<br>";
            }
        echo '</div>';
    }
    else
    {
        $sql = "UPDATE 
                    `items` 
                SET 
                    `project`='$project',`article`='$article',`granularity`='$granularity',`timestamp`= $timestamp,`access`='$access',`agent`='$agent' 
                WHERE 
                    `id` = $itemId";

        $op  = mysqli_query($con,$sql);
        if($op)
        {
            echo '<div class="alert alert-success" role="alert">';
                echo "Data Edite Success";
            echo '</div>';
            echo "<hr>";
            header("refresh:1;url=displayData.php");
        }
    }
}

?>
<!-- Start Show All Blogs  -->
<div class='container pt-2'>
    <div class='public-bg'>
        <div class="container">
            <!--Section: Contact v.2-->
            <section class="mb-4">

                <!--Section heading-->
                <h2 class="h1-responsive font-weight-bold text-center my-4">Edit Items</h2>

                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-9 mb-md-0 mb-5">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?itemId='.$item['id']; ?>" method="POST">

                            <!--Project Grid row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="md-form mb-0">
                                        <label for="project" class="">Project</label>
                                        <input type="text" id="project" name="project" class="form-control" value="<?php echo $item['project']; ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Article Grid row-->
                            <div class="row">

                                <!--Grid column-->
                                <div class="col-md-12">

                                    <div class="md-form">
                                        <label for="article">Article</label>
                                        <textarea type="text" id="article" name="article" rows="2" class="form-control md-textarea"><?php echo $item['article']; ?></textarea>
                                    </div>

                                </div>
                            </div>

                            <!--Granularity Grid row-->
                            <div class="row">
                                <!--Grid column-->
                                <div class="col-md-12">

                                    <div class="md-form">
                                        <label for="granularity">Granularity</label>
                                        <input type="text" id="granularity" name="granularity"  class="form-control" value="<?php echo $item['granularity']; ?>">
                                    </div>

                                </div>
                            </div>

                            <!--Access Grid row-->
                            <div class="row">
                                <!--Grid column-->
                                <div class="col-md-12">

                                    <div class="md-form">
                                        <label for="access">Access</label>
                                        <input type="text" id="access" name="access" class="form-control" value="<?php echo $item['access']; ?>">
                                    </div>

                                </div>
                            </div>

                            <!-- Agent Grid row-->
                            <div class="row">
                                <!--Grid column-->
                                <div class="col-md-12">

                                    <div class="md-form">
                                        <label for="message">Agent</label>
                                        <input type="text" id="agent" name="agent" class="form-control" value="<?php echo $item['agent']; ?>">
                                    </div>

                                </div>
                            </div>
                            <!-- #timeStamp Input  -->
                            <input type="hidden" name="timeStamp" value="<?php echo time();?>">
                            <!--Grid row-->
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" name="editItem" class="btn btn-success btn-sm px-3 py-2  mt-2"><i class='fa fa-edit'></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-3 text-center">
                        <ul class="list-unstyled mb-0">
                            <li><i class='fas fa-clock fa-2x'></i>
                                <p><?php echo date("Y-m-d",$item['timestamp']) ; ?></p>
                            </li>

                            <li><i class='fa fa-eye mt-4 fa-2x'></i>
                                <p><?php echo $item['views']; ?></p>
                            </li>
                        </ul>
                    </div>
                    <!--Grid column-->

                </div>

            </section>
            <!--Section: Contact v.2-->
        </div>
    </div>
</div>

<?php
require "./includes/closeConnection.php";
require "./includes/footer.php";
?>