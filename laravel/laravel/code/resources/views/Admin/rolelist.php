<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="srno">Sr.No.</th>
                <th>Role Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $roles = $dbdata;
                if (is_array($roles) && count($roles) > 0)
                {
                    foreach ($roles as $i => $role)
                    {
                        $id = $role['role_id'];
                        $delete = '';
                        $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="roleedit"><i class="fa fa-edit mr10 fa-lg"></i></span>';
                        $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="roledelete"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                        $assign = '<span title = "Click To Assign Permission" type="button" id="assign_'.$id.'" data-moduleid="'.$id.'" class="rolepermissions"><i class="fa fa-user mr10 fa-lg"></i></span>';
                        ?>
            <tr>
                <td class="srno"><?php echo $i + $offset + 1 ?></td>
                <td><?php echo $role['role_name']; ?></td>
                <td><?php echo $role['role_description']; ?></td>
                <td><?php echo ucfirst($role['role_type']); ?></td>
                <td><?php echo $edit.' '.$delete.' '.$assign; ?></td>
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
