<?php
#team ratings
$alpha = rand(200,900);
$beta = rand(200,900);
$feta = rand(200,900);
# ratings
if ($alpha > $beta or $alpha > $feta)
    {
        $alpha = rand(300,900);
    } else if ($beta > ($alpha or $feta))
    {
        $beta = rand(300,900);
    } else if ($feta > ($beta or $alpha))
    {
        $feta = rand(300,900);
    }
    # add match resulats and a leaderboard like this if $alpha_points > ($beta_points or $feta poits){
    #echo "1:team alpha ($alpha_points)" and so on :)
     #}
?>
<!DOCTYPE html>
<html lang="se">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>