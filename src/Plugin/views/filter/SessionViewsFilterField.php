<?php

namespace Drupal\session_views\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\ViewExecutable;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("session_views_filter_field")
 */

class SessionViewsFilterField extends InOperator {
  
  //@todo Review verf\src\Plugin\views\filter\EntityReference.php
  
  //@todo Include buildOptionsForm method
  
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    return $options;
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
//    parent::buildOptionsForm($form, $form_state);
//    if ($this->targetEntityType->hasKey('bundle')) {
//      $options = [];
//      foreach ($this->entityTypeBundleInfo->getBundleInfo($this->targetEntityType->id()) as $bundle_id => $bundle_info) {
//        $options[$bundle_id] = $bundle_info['label'];
//      }
//      $form['verf_target_bundles'] = [
//        '#type' => 'checkboxes',
//        '#title' => $this->t('Target entity bundles to filter by'),
//        '#options' => $options,
//        '#default_value' => array_filter($this->options['verf_target_bundles']),
//      ];
//    }
//
//    //return $form;  
    parent::buildOptionsForm($form, $form_state);
    
    unset($form['expose_button']);
    unset($form['operator']);
    unset($form['value']);
    
    kint($form);
        
    $list = $this->getEligibleFields();
    
    $form['session_views_filter'] = array(
      '#title' => $this->t('Flag trigger field'),
      '#type' => 'select',
      '#options' => $list,
      '#description' => 'Select the field that will be used to trigger the flag.',
    );

    return $form;
 
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    
      kint(parent::query());
    
//    if (!empty($this->value)) {
//      parent::query();
//    }
    
    
//    //Get the current domain.  
//    $domain = domain_get_domain();
//
//
//    $configuration = [
//      'table' => 'node_access',
//      'field' => 'nid',
//      'left_table' => 'node_field_data',
//      'left_field' => 'nid',
//      'operator' => '='
//    ];
//    $join = Views::pluginManager('join')->createInstance('standard', $configuration);
//
//
//    $this->query->addRelationship('node_access', $join, 'node_field_data');
//    $this->query->addWhere('AND', 'node_access.gid', $domain->getDomainId());
  }
  
  
  /**
   * Gets an array of fields configured to work with Session Views.
   */
  private function getEligibleFields() {
    //kint($this->targetEntityType);
    
    $ENTITY_TYPE = 'node';
    $fields = $this->getEntityReferenceFields($ENTITY_TYPE);

    $list = array();
    
    foreach (array_keys($fields) as $field)
    { 
      $label = "";
      $first = true;
      
      foreach($fields[$field] as $bundle)
      {
        if (\Drupal\field\Entity\FieldConfig::loadByName($ENTITY_TYPE, $bundle, $field)->getSetting('handler') == 'default:taxonomy_term')
        {       
          if(in_array($this->getTargetVocabulary(), \Drupal\field\Entity\FieldConfig::loadByName($ENTITY_TYPE, $bundle, $field)->getSetting('handler_settings')['target_bundles']))
          {        
            if ($first)
            {
              $label = \Drupal\field\Entity\FieldConfig::loadByName($ENTITY_TYPE, $bundle, $field)->getLabel();
              $label .= ' - Appears in: ';
              $label .= $bundle;
              $first = false;
            }
            else
            {
              $label .= ', ';
              $label .= $bundle;
            }
          }
        }
      }
      
      if (!empty($label))
      {
        $list[$field] = $label;
      }
    }
    
    return $list;
  }
  
  /**
  * Gets an array of all Entity Reference fields for the specified entity type.
  * @param string $type A string with the machine name of the entity type.
  * @return array An array of user created Entity Reference fields.
  */
  private function getEntityReferenceFields($type) {
    $node_field_map = \Drupal::entityManager()->getFieldMap()[$type];
    
    $fields = array();
    
    foreach (array_keys($node_field_map) as $field_key)
    {
      if (strpos($field_key, 'field_') !== false)
      {
        if ($node_field_map[$field_key]['type'] == 'entity_reference')
        {
          $fields[$field_key] = $node_field_map[$field_key]['bundles'];
        }
      }
    }
    
    return $fields;
  }
  
  /**
   * Gets the key of the currently selected vocabulary in the module's settings.
   * @return string A string containing the key of the selected vocabulary.
   */
  private function getTargetVocabulary() {
    $config = \Drupal::config('session_views.settings');

    if ($config->get('session_views.selected_vocabulary'))
    {
      return $config->get('session_views.selected_vocabulary');
    }
    
    return null;
  }
  
}
