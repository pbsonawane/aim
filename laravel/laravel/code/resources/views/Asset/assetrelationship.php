<?php
                          
      if(isset($asset_rel_data) && is_array($asset_rel_data) && count($asset_rel_data) > 0){

        $keys = array_keys($asset_rel_data);

        if(is_array($keys) && count($keys) >0){
          for($i=0;$i<count($keys);$i++){

            echo ('
            <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
            ');
            echo ('<th style="width: 80%;color:#3bafda">');
            echo trans('label.lbl_relationshiptype') . ': ' . $keys[$i];
            echo ('</th>');
            echo ('<th>');
            echo trans('label.lbl_action');
            echo ('</th>');
            echo ('
              </tr>
            </thead>
            ');

            echo '<tbody>';

            $vals = $asset_rel_data[$keys[$i]];
            if(is_array($vals) && count($vals) > 0)
            {

              foreach ($vals as $key => $value) 
              {
              
                echo '<tr>';
                echo '<td>';
                print_r($key);
                echo '</td>';
                echo '<td>';
                if(canuser('delete','assetrelationship')) { 
                echo '<span title="'. trans('messages.msg_clicktodelete') .'" type="button" id="delete_'.$value.'"  class="assetrelationship_del" child_asset="'.$key.'" rel_type="'.$keys[$i].'"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                }
                echo '</td>';
                echo '</tr>';
              }
            }

            echo '<tbody></table></br>';
          }
        }
      }else{
        echo trans('messages.msg_norecordfound');
      }
  ?>
