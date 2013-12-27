

<legend>Subjects</legend>

<table class="table table-hover">
    <thead>
        <th>ID</th>
        <th>Subject Name</th>
        <th>Subject Name Short</th>
        <th>Edit</th>
    </thead>
<?


$subjects = $subject->getSubjects();
if($subjects){
    $subject_id = 2;
    $subject_name = 0;
    $subject_name_short = 1;

    for($i=0; $i<count($subjects); $i++){

        echo '
        <tr>
            <td>'.$subjects[$i][$subject_id].'</td>
            <td>'.$subjects[$i][$subject_name].'</td>
            <td>'.$subjects[$i][$subject_name_short].'</td>
            <td><a href="/subject_edit/'.$subjects[$i][$subject_id].'"><span class="glyphicon glyphicon-edit"></span></a></td>
        </tr>';
    }
}else{
    echo '<div class="alert alert-danger">No subjects found.</div>';
}


?>
</table>
