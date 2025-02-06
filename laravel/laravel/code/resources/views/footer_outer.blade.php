<?php 
      $result = rebranding();
      //echo "<pre>"; print_r($result); echo "</pre>";
      if (isset($result[0]['cp_toggle']) && $result[0]['cp_toggle'] == "y") 
      {
        $copyright    = isset($result[0]['copyright']) ? $result[0]['copyright'] : "";
      }
      $product_name = isset($result[0]['product_name']) ? $result[0]['product_name'] : "";
      $img = getlogo();

  ?>
    <footer class="footer">
      <p><?php echo isset($copyright) ? str_replace("AIM",'<strong>'.trans('title.enlight360').'</strong>',$copyright) : ""; ?></p>
    </footer>    