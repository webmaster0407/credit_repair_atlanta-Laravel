<?php
header('Content-Type: text/html; charset=UTF-8');
$id = @trim(strip_tags($_GET['id'])); 
echo '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>Verification: '.$id.'</body>
</html>';
