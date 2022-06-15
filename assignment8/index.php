<?php
#require Database file connection . . .
require "./includes/dbConnection.php";
#require Header File . . . 
require "./includes/header.php";
#Function Clean Input Data
function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = strip_tags($input);
    return $input;
}
#link of Api ......
$api_url = 'https://wikimedia.org/api/rest_v1/metrics/pageviews/per-article/en.wikipedia/all-access/all-agents/Tiger_King/daily/20210901/20210930';

// Read JSON file
$json_data = file_get_contents($api_url);

// Decode JSON data into PHP array
$api_data = json_decode($json_data,true);

#Fetch Error In Array
$inputErrors = [];
// check Count Of data  

foreach($api_data['items'] as $items)
{
    $project       = cleanInput($items['project']);
    $article       = cleanInput($items['article']);
    $granularity   = cleanInput($items['granularity']);
    $timestamp     = cleanInput($items['timestamp']);
    $access        = cleanInput($items['access']);
    $agent         = cleanInput($items['agent']);
    $views         = cleanInput($items['views']);
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
    #Validate views Input . . .
    if(empty($views))
    {
        $inputErrors['views']="views Required";
    }
    elseif(!is_numeric($views))
    {
        $inputErrors['views']="views must be Number";
    }
    if(!empty($inputErrors))
    {
        foreach($inputErrors as $key => $error)
        {
            echo '<div class="alert alert-success" role="alert">';
                    echo $key." : ".$error;
            echo '</div>';
        }
    }
    else
    {
        $sql = "INSERT INTO `items`(`project`, `article`, `granularity`, `timestamp`, `access`, `agent`, `views`) 
        VALUES ('$project', ' $article', '$granularity', $timestamp, '$access', '$agent', $views)";
        $op  = mysqli_query($con,$sql);
        if($op)
        {
            echo '<div class="alert alert-success" role="alert">';
                echo "Data Added Success";
            echo '</div>';
            echo "<hr>";
        }
    }
}

require "./includes/closeConnection.php";
require "./includes/footer.php";
?>