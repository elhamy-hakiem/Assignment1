<?php
//Solution Task 1
$char = 'z';
function nextChar($character)
{
    $nextChar = ++$character;
   if(strlen($character)>1)
   {
       $nextChar = $nextChar[0];
   }
    echo $nextChar;
}
nextChar($char);



// solution Task2
$myUrl = 'http://www.example.com/5478631';

echo substr($myUrl, strrpos($myUrl, '/' )+1)."\n";


?>