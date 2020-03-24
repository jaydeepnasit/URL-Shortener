<?php

include_once 'Config/CUFunction.php';

$CUF_OBJ = New CUFunction();
$counter = 1;
$Select_URL = $CUF_OBJ->select('shorturl', 'id', 'Desc');

?>
<table class="table text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">New Link</th>
            <th scope="col">Oiginal Link</th>
            <th scope="col">Operation</th>
        </tr>
    </thead>
<tbody>
<?php if($Select_URL){ foreach($Select_URL as $Select_Data){ ?> 
<tr>
    <th scope="row"><?php echo $counter; $counter++; ?></th>
    <td><?php echo $Select_Data['s_new_link']; ?></td>
    <td><?php echo (strlen($Select_Data['s_original_link']) > 50) ? substr($Select_Data['s_original_link'],0,50).'....' : $Select_Data['s_original_link']; ?></td>
    <td class="exit-icon" id="Delete" data-deleteno="<?php echo $Select_Data['s_unique_no']; ?>">
        <div class="exit-icon-box">&#10060;</div>
    </td>
</tr>
<?php }}else{ echo "<tr><td colspan='4' class='text-center'><h3>Links Not Found</h3></td></tr>"; } ?>
</tbody>
</table>