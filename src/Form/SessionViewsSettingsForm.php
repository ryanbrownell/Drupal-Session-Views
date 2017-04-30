<?php

/**
 * @file
 * Contains \Drupal\session_views\Form\SessionViewsSettingsForm.
 */

namespace Drupal\session_views\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SessionViewsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'session_views_settings_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor
    $form = parent::buildForm($form, $form_state);
    // Default settings
    $config = $this->config('session_views.settings');
    // Page title field
    $form['page_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Lorem ipsum generator page title:'),
      '#default_value' => $config->get('session_views.page_title'),
      '#description' => $this->t('Give your lorem ipsum generator page a title.'),
    );
    // Source text field
    $form['source_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Source text for lorem ipsum generation:'),
      '#default_value' => $config->get('session_views.source_text'),
      '#description' => $this->t('Write one sentence per line. Those sentences will be used to generate random text.'),
    );
          
    $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();      
    $vocabs = array();
      
    foreach($vocabularies as $vocab)
    {
        $vocabs[$vocab->get("vid")] = $vocab->get("name");
    }
      
    $form['selected_vocabulary'] = array(
      '#type' => 'select',
      '#title' => $this->t('Vocabulary'),
      '#multiple' => false,
      '#options' => $vocabs,
      '#default_value' => $config->get('session_views.selected_vocabulary'),
      '#description' => $this->t('Select one vocabulary whose terms will be used.'),
    );
      
    $form['log_selected_terms'] = array(
        '#type' => 'checkboxes',
        '#title' => $this->t('Selection logging'),
        '#options' => array('LOGGING' => $this->t('Enable logging')),
        '#default_value' => $config->get('session_views.log_selected_terms'),
      '#description' => $this->t('Enables logging of selected terms. <strong>Not recommended for sites with high traffic volumes.</strong>'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('session_views.settings');
    $config->set('session_views.source_text', $form_state->getValue('source_text'));
    $config->set('session_views.page_title', $form_state->getValue('page_title'));
    $config->set('session_views.selected_vocabulary', $form_state->getValue('selected_vocabulary'));
    $config->set('session_views.log_selected_terms', $form_state->getValue('log_selected_terms'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}.
   */
  protected function getEditableConfigNames() {
    return [
      'session_views.settings',
    ];
  }

}