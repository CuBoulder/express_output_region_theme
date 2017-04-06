<?php
  $region = isset($_GET['region']) ? check_plain($_GET['region']) : NULL;

  if($region && !empty($page[$region])) {
    //print $region;
    print render($page[$region]);
  }
  else {
    $path = current_path();
    $query = drupal_get_query_parameters();
    $regions = system_region_list('expressregion', $show = REGIONS_ALL, $labels = TRUE);
    foreach ($regions as $key => $region) {
      $link_query = $query;
      $link_query['region'] = $key;
      $link = l($region, $path, array('query' => $link_query));
      $region_list[$key] = $link;
    }
    $output['#markup'] = '<p>The region you specified is either empty or does not exist.</p><p>Please specifiy a region to output:</p><p>' . join(', ', $region_list) . '</p>';

    print render($output);
  }
?>
