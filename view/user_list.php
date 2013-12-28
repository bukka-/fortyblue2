
<legend>Users</legend>

<table class="table table-hover">
    <thead>
        <th>ID</th>
        <th>User Name</th>
        <th>User Group</th>
        <th>Edit</th>
    </thead>
<?
$users = $user->getUsers();

if($users){
    $user_id = 0;
    $user_name = 1;
    $user_group = 2;

    for($i=0; $i<count($users); $i++){

        echo '
        <tr>
            <td>'.$users[$i][$user_id].'</td>
            <td>'.$users[$i][$user_name].'</td>
            <td>'.$users[$i][$user_group].'</td>
            <td><a href="/user_edit/'.$users[$i][$user_name].'"><span class="glyphicon glyphicon-edit"></span></a></td>
        </tr>';
    }
}else{
    echo '<div class="alert alert-danger">No users found.</div>';
}

?>
</table>
