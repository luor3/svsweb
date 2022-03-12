<?php
$myfile = fopen('C:\Users\raymond\Desktop\1\input.conf','r') or die("Unable to open file!");
$remove_comment_pattern = "/^\@/i";
$input_pattern2 = "/File/i";

$lineNum = 1;
while(! feof($myfile))
{
    $line = fgets($myfile);
    if(preg_match($remove_comment_pattern, $line))
    {
       
    }else{
        if(preg_match($input_pattern2, $line))
        {
            $l = explode(":",$line);
            echo(trim($l[0]));
            echo( $l[1]);
            $array = explode('.', $l[1]);
            $extension = end($array); 
            echo($extension);      
        }
    }
    $lineNum++;
}    
fclose($myfile);
?>