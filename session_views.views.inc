<?php

/**
 * @file
 * Contains session_views\session_views.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Render\Markup;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\system\ActionConfigEntityInterface;

/**
* Implements hook_views_data().
*/
function session_views_views_data() {
  $data['views']['session_views_flag_field'] = array(
      'title' => t('Session selection flag'),
      'group' => t('Content'),
      'help' => t('Returns "session-views-selected" if the respective entity is part of the taxonomy that was selected by the user.'),
      'field' => array(
          'id' => 'session_views_flag_field',
      ),
  );
  
//  $data['views']['session_views_filter_field'] = array(
//    'title' => t('Session selection filter'),
//    'group' => t('Content'),
//    'help' => t('Returns only entities that are part of the taxonomy that was selected by the user.'),
//    'filter' => array(
//      'id' => 'session_views_filter_field'
//    ),
//  );

  return $data;
}
