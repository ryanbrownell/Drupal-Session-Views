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
 * @ViewsField("session_views_flag_field")
 */
class SessionViewsFlagField extends FieldPluginBase {

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
    $options['session_views_field'] = array('default' => '');
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    
    //@todo: Clean this code up.
    //$entityManager = \Drupal::entityManager();
    //kint($entityManager->getFieldDefinitions('node', 'position'));
        
    //@todo REVISE UPDATE found at https://drupal.stackexchange.com/questions/167001/field-info-field-deprecated-whats-the-equivalent
    //kint(\Drupal::entityTypeManager()->getDefinition('node')->getBundleLabel());
    //kint(\Drupal::entityManager()->getFieldMap()['node']);
    //kint(\Drupal::entityManager()->getAllBundleInfo()['node']);
    //kint(\Drupal\field\Entity\FieldConfig::loadByName('node', 'position', 'field_experience_type'));
    //kint(\Drupal\field\Entity\FieldConfig::loadByName('node', 'position', 'field_experience_type')->getSetting('handler'));
    //kint(\Drupal\field\Entity\FieldConfig::loadByName('node', 'position', 'field_experience_type')->getSetting('handler_settings'));
    //kint(\Drupal\field\Entity\FieldConfig::loadByName('node', 'position', 'field_experience_type')->getSetting('handler_settings')['target_bundles']);
    
    // @todo: REVISE UPDATE found at https://drupal.stackexchange.com/questions/167001/field-info-field-deprecated-whats-the-equivalent
    
    //kint($field_map);
    
    //$node_fields = array_keys($node_field_map['node']);
    
    $list = $this->getEligibleFields();
    
    $form['session_views_field'] = array(
      '#title' => $this->t('Flag trigger field'),
      '#type' => 'select',
      '#options' => $list,
      '#description' => 'Select the field that will be used to trigger the flag.',
    );
    
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

    $session_views_field = $this->options['session_views_field'];
    
    $fieldTerms = array();
    $rawFieldTerms = entity_load('node', $values->nid)->get($session_views_field)->getValue();
    
    foreach ($rawFieldTerms as $term)
    {
      $fieldTerms[] = $term['target_id'];
    }
        
    $session = new \Symfony\Component\HttpFoundation\Session\Session();
    $sessionSelectedTerms = $session->get('selected_terms');

    if (empty($sessionSelectedTerms))
    {
      return "session-views-selected";
    }
    
    foreach ($sessionSelectedTerms as $term)
    {
      if (in_array($term, $fieldTerms))
      {
        return "session-views-selected";
      }
    }

    return null;
  }
    
  // @todo Consider moving the following out into the module class.
  
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
