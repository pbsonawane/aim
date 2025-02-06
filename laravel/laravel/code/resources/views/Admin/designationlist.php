<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="srno">Sr.No.</th>
                <th>Designation</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $designations = $dbdata;
                if (is_array($designations) && count($designations) > 0)
                {
                    foreach ($designations as $i => $designation)
                    {
                        $id = $designation['designation_id'];
                        $delete = '';
                        $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="designationedit"><i class="fa fa-edit mr10 fa-lg"></i></span>';
                        $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="designationdelete"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                        ?>
            <tr>
                <td class="srno"><?php echo $i + $offset + 1 ?></td>
                <td><?php echo $designation['designation_name']; ?></td>
                <td><?php echo $edit.' '.$delete; ?></td>
            </tr>
            <?php
                    }
                }
                else
                {
                    echo '<tr><td colspan="5" align="center"> No Records</td></tr>';
                }
            ?>
        </tbody>
    </table>
</div>
