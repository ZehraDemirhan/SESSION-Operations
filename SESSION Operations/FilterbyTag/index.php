<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app1.css">
</head>
<?php 
session_start();
//session_destroy();

if(!isset($_SESSION['tagList']))
{

$_SESSION['tagList']=[];
//var_dump($_SESSION);
}
require "db.php";


if(!empty($_POST))
{

extract($_POST);
if(!in_array($searchTag,$_SESSION['tagList']))

array_push($_SESSION['tagList'],$searchTag);
//$_SESSION['tagList'][]=$searchTag;


}
if(isset($_GET['add']))
{
    extract($_GET);
    if(!in_array($add,$_SESSION['tagList']))
    array_push($_SESSION['tagList'],$add);

}
if(isset($_GET['del']))
{
    extract($_GET);
    //echo "here";
    if(($index=array_search($del,$_SESSION['tagList']))!==false)
    {
        //echo $index;
        unset($_SESSION['tagList'][$index]);

    }

}
if(isset($_GET['heartClick']))
{
    extract($_GET);
    $stmt1=$db->prepare("select *from projects where team=?");
    $stmt1->execute([$heartClick]);
    $array=$stmt1->fetch(PDO::FETCH_ASSOC);


    $stmt2=$db->prepare("UPDATE projects SET likeit=1-{$array['likeit']} where team=?");
    $stmt2->execute([$heartClick]);
}
//var_dump($_SESSION);
try {
    $rs = $db->query("select * from projects") ;
    $projects = $rs->fetchAll(PDO::FETCH_ASSOC) ;
   // var_dump($games) ; 
  } catch(PDOException $ex) {
    die("select query problem") ; 
  }
  

?>
<body>

<table>
    <form action="?" method="post">
        <div class="center" id='inner' > 
        <input type="text" name="searchTag" id='search' placeholder='search tag...' >
   
        </div>
        <div class="center" id='inner'>
        <?php foreach($_SESSION['tagList'] as $tag){
      echo  "<a id='tags' class='tag' href='?del=$tag'>$tag  &#10005; </a>";




    }?>
    </div>
   <hr>
</form>
    <tr><th>Team</th>
    <th>Project Name</th>
    <th>Supervisor</th>
    <th>Tags</th>
    <th>Like</th></tr>
    
    <?php 
    
    foreach($projects as $project){
        $tags=[];
       // var_dump(getTag( $project['team']));
        foreach(getTag( $project['team']) as $tag){
            $tags[]=$tag['tagName'];

        }
        //echo isset($_GET['add']);
        //echo empty($_POST);
        
    if(count($_SESSION['tagList'])==0)
    {
        
        echo "<tr>";
        echo "<td>{$project['team']}</td>";
         echo "<td>{$project['name']}</td>";
         echo "<td>{$project['supervisor']}</td>";
         echo "<td>";
         //var_dump(getTag( $project['team']));
 
       foreach($tags as $tag){
             echo "<a  href='?add={$tag}'class='tag'>{$tag}</a>";
         
         }
         
        echo"</td>";
        echo "<td>";
        echo "<a class='heart' href='?heartClick={$project['team']}' >";
         echo $project['likeit']==0? '&#129293; ': '&#128153; ';
        echo "</a>";
        echo "</td>";
     echo "</tr>";
    }
else {

    foreach(($_SESSION['tagList'])as $addedTag)
        {
            
            if (in_array($addedTag,$tags))
            {
                
                echo "<tr>";
                echo "<td>{$project['team']}</td>";
                 echo "<td>{$project['name']}</td>";
                 echo "<td>{$project['supervisor']}</td>";
                 echo "<td>";
                 //var_dump(getTag( $project['team']));
         
               foreach($tags as $tag){
                     echo "<a  href='?add={$tag}'class='tag'>{$tag}</a>";
                 
                 }
                 
                echo"</td>";
                echo "<td>";
                echo "<a class='heart' href='?heartClick={$project['team']}' >";
                    echo $project['likeit']==0? '&#129293; ': '&#128153; ';
                echo "</a>";
                echo "</td>";
             echo "</tr>";
             break;
            }
        }
    }

    }
?>
</table>
    
</body>
</html>