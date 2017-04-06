<?php

/**
 * Override theme_menu_link().
 *
 * Need absolute links instead of relative.
 */
function expressregion_menu_link($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], array_merge($element['#localized_options'], array('absolute' => TRUE)));
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Override theme_link().
 *
 * Need absolute links.
 */
function expressregion_link($variables) {
  $variables['options']['absolute'] = TRUE;
  return '<a href="' . check_plain(url($variables['path'], $variables['options'])) . '"' . drupal_attributes($variables['options']['attributes']) . '>' . ($variables['options']['html'] ? $variables['text'] : check_plain($variables['text'])) . '</a>';
}

/**
 * Implements hook_preprocess_block).
 */
function expressregion_preprocess_block(&$vars) {

  // Add class for block titles
  $vars['title_attributes_array']['class'][] = 'block-title';
  $vars['classes_array'][] = !empty($vars['block']->subject) ? 'has-block-title'
  : '';
  // If the block is a bean, add bundle as a class
  if ($vars['block']->module == 'bean') {
    // Get the bean elements.
    if (isset($vars['elements']['bean'])) {
      $beans = $vars['elements']['bean'];
      // There is only 1 bean per block.
      $children = element_children($beans);
      $bean = $beans[current(element_children($beans))];
      // Add bean type classes to the parent block.
      $vars['classes_array'][] = drupal_html_class('block-bean-type-' . $bean['#bundle']);
    }
  }
  if(isset($vars['elements']['content']) && isset($vars['elements']['content']['bean'])) {
    $bean = $vars['elements']['content']['bean'];
    $children = array_intersect_key($bean, array_flip(element_children($bean)));
    $the_bean = current($children);
    $bean_type = $the_bean['#bundle'];
    $vars['classes_array'][] = 'bean-type-' . $bean_type;
    $vars['classes_array'][] = drupal_html_class('block-bean-type-' . $bean_type);
  }
}

/**
 * Override or insert variables into the block templates.
 */
function expressregion_process_block(&$vars) {
  // Drupal 7 should use a $title variable instead of $block->subject.
  $vars['title'] = isset($vars['block']->subject) ? $vars['block']->subject : '';
}
