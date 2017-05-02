<?php

namespace Drupal\session_views\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("session_views_field")
 */
class SessionViewsField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    //$entityManager = \Drupal::entityManager();
    //kint($entityManager->getFieldDefinitions('node', 'position'));
        
    //kint(\Drupal::entityTypeManager()->getDefinition('node')->getBundleLabel());
    kint(\Drupal::entityManager()->getAllBundleInfo()['node']);
    
    //TODO: REVISE UPDATE found at https://drupal.stackexchange.com/questions/167001/field-info-field-deprecated-whats-the-equivalent
    
    //kint(\Drupal\Core\Entity\EntityTypeBundleInfoInterface::getAllBundleInfo());
        
//    $field_map = \Drupal::entityManager()->getFieldMap();
//    $node_field_map = $field_map['node'];
//    
//    kint($node_field_map);
//    
//    $fields = array();
//    
//    foreach (array_keys($node_field_map) as $field_key)
//    {
//      if ($node_field_map[$field_key]['type'] == 'entity_reference')
//      {
//        $fields[] = $field_key;
//      }
//    }
//    
//    kint($fields);
    
    //kint($field_map);
    
    //$node_fields = array_keys($node_field_map['node']);
    
    parent::buildOptionsForm($form, $form_state);
    
    //kint($this);
    //kint($form);
    //kint($form_state);
    
//  $field_map = \Drupal::entityManager()->getFieldMap();
//  $node_field_map = $field_map['node'];
//  $node_fields = array_keys($node_field_map['node']);
//  
//  kint("Hello World");
  
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    //kint($values);
    kint($values->nid);
    return $values->nid;
  }

}