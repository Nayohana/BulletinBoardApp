<?php

use function PHPSTORM_META\sql_injection_subst;

include_once("./app/database/connect.php");

$error_message = array();


if (isset($_POST["submitBotton"])){


    //validation check
    if(empty($_POST["username"])){
        $error_message["username"]="名前を入力してください";
    } else {
    // Avoiding XSS
        $escaped["username"] = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    }
    if (empty($_POST["body"])) {
        $error_message["body"] = "コメントを入力してください";
    } else {
        $escaped["body"] = htmlspecialchars($_POST["body"], ENT_QUOTES, 'UTF-8');
    }
    
    if (empty($error_message)){
        $post_date = date("Y-m-d H:i:s");

        $sql= "INSERT INTO `comment` (`username`, `body`, `post_date`) VALUES (:username, :body, :post_date);";
        $statement=$pdo->prepare($sql);
    
    //値をセット
        $statement->bindParam(":username",$escaped["username"] , PDO::PARAM_STR);
        $statement->bindParam(":body", $escaped["body"], PDO::PARAM_STR);
        $statement->bindParam(":post_date", $post_date, PDO::PARAM_STR);
    
        $statement->execute();        
    }
}



$comment_array=array();

//get commentdata from table
$sql = "SELECT * FROM comment";

$statment = $pdo->prepare($sql);
$statment->execute();

$comment_array = $statment;

//var_dump($comment_array->fetchAll());

?>
<div class="threadwrapper">
        <div class="childwrapper">
            <div class="threadTitle">
                <span>【タイトル】</span>
                <h1>2ちゃんねる掲示板を作ってみた</h1>
            </div>

            <?php include("app/database/parts/commentsection.php"); ?>
            <?php include("app/database/parts/commentform.php"); ?>

        </div>
    </div>


    