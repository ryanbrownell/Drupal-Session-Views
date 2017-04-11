<?php

/**
 * @file
 * Contains \Drupal\session_views\Form\SessionViewsSelectForm.
 */

namespace Drupal\session_views\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SessionViewsSelectForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'session_views_select_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('session_views.settings');
    $vocabulary = $config->get('session_views.selected_vocabulary');
      
    if (empty($config))
    {
        drupal_set_message(t('This module has not been properly configured.'), 'error');
    }
    else
    {
        $terms = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term')->loadTree($vocabulary);

        $selectedTerms = array();

        foreach($terms as $term)
        {
            $selectedTerms[$term->tid] = $term->name;
        }
        
        $form['#title'] = $this->t($config->get('session_views.page_title'));
        
        $form['text'] = array(
        '#markup' => '<div>' . $config->get('session_views.source_text') . '</div>'
        );

        $form['selected_terms'] = array(
          '#type' => 'checkboxes',
          //'#title' => $this->t('Vocabulary'),
          '#multiple' => true,
          '#options' => $selectedTerms,
          //'#default_value' => $config->get('session_views.selected_vocabulary'),
          //'#description' => $this->t('Select one vocabulary whose terms will be used.'),
        );

        // Submit
        $form['submit'] = array(
          '#type' => 'submit',
          '#value' => $this->t('Continue to Site'),
        );
    }
      
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
      $tempstore = \Drupal::service('user.private_tempstore')->get('session_views');
      
      $values = array();
      
      foreach ($form_state->getValue('selected_terms') as $field)
      {
          $field != 0 ? $values[] = $field : '';
      }
      
      $tempstore->set('selected_terms', $values);
      //kint($tempstore);
//    $config = $this->config('session_views.settings');
//    $config->set('session_views.source_text', $form_state->getValue('source_text'));
//    $config->set('session_views.page_title', $form_state->getValue('page_title'));
//    $config->set('session_views.selected_vocabulary', $form_state->getValue('selected_vocabulary'));
//    $config->save();
    //return parent::submitForm($form, $form_state);
  }
}