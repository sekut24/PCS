<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halo Abang</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>Nama</td>
            <td>Harga</td>
        </tr>
        <?php
        foreach($produk as $row){
            echo 
            "
            <tr>
                <td>".$row->nama."</td>
                <td>".$row->harga."</td>
            </tr>
            ";
        } 
    ?>
    </table>
    
</body>
</html>