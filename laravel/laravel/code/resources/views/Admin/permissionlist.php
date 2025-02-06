<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="srno">Sr.No.</th>
                <th>Permission Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Module</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $permissions = $dbdata;
                if (is_array($permissions) && count($permissions) > 0)
                {
                    foreach ($permissions as $i => $permission)
                    {
                        $id = $permission['permission_id'];
                        $delete = '';
                        $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="permissionedit"><i class="fa fa-edit mr10 fa-lg"></i></span>';
                        $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="permissiondelete"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                        ?>
            <tr>
                <td class="srno"><?php echo $i + $offset + 1 ?></td>
                <td><?php echo $permission['permission_name']; ?></td>
                <td><?php echo $permission['perm_category_name']; ?></td>
                <td><?php echo ucfirst($permission['permission_type']); ?></td>
                <td><?php echo $permission['module_name']; ?></td>
                <td><?php echo $permission['permission_description']; ?></td>
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
