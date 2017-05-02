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
      
    $session = new \Symfony\Component\HttpFoundation\Session\Session();
    
    //drupal_set_message(t('Hello World'), 'error');

    if (empty($config))
    {
        drupal_set_message(t('This module has not been properly configured.'), 'error');
    }
    else
    {
        $terms = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term')->loadTree($vocabulary);
        
        $selectedTerms = $session->get('selected_terms');

        $vocabularyTerms = array();

        foreach($terms as $term)
        {
            $vocabularyTerms[$term->tid] = $term->name;
        }
        
        $form['#title'] = $this->t($config->get('session_views.page_title'));
        
        $form['text'] = array(
        '#markup' => '<div>' . $config->get('session_views.source_text') . '</div>'
        );

        $form['selected_terms'] = array(
          '#type' => 'checkboxes',
          '#multiple' => true,
          '#options' => $vocabularyTerms,
          '#default_value' => $selectedTerms,
        );

        // Submit
        $form['submit'] = array(
          '#type' => 'submit',
          '#value' => $this->t('Continue to Site'),
        );
        
        // Ensure cache is disabled for this page.
        $form['#cache'] = ['max-age' => 0];
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
      
      
      //$tempstore = \Drupal::service('user.private_tempstore')->get('session_views');
      $session = new \Symfony\Component\HttpFoundation\Session\Session();
      
      $values = array();
      
      foreach ($form_state->getValue('selected_terms') as $field)
      {
          $field != 0 ? $values[] = $field : '';
      }
      
      $session->set('selected_terms', $values);
      
      $this->logSessionValues();
      
      //kint($tempstore);
//    $config = $this->config('session_views.settings');
//    $config->set('session_views.source_text', $form_state->getValue('source_text'));
//    $config->set('session_views.page_title', $form_state->getValue('page_title'));
//    $config->set('session_views.selected_vocabulary', $form_state->getValue('selected_vocabulary'));
//    $config->save();
    //return parent::submitForm($form, $form_state);
  }
  
  /**
   * Logs the session's current selection if the module is
   * configured to do so.
   */
  public function logSessionValues() {
    $config = \Drupal::config('session_views.settings');
    
    if ($config->get('session_views.log_selected_terms'))
      {
      //drupal_set_message(t('This has been logged!'), 'info');
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $selectedTerms = $session->get('selected_terms');
        
        $firstItem = true;
        $logMessage = "";

        $logMessage .= "The following TIDs were selected for a session: ";

        foreach ($selectedTerms as $term)
        {
            if (!$firstItem)
            {
                $logMessage .= ", ";
            }

            $logMessage .= $term;
            $firstItem = false;
        }

        \Drupal::logger('session_views')->info($logMessage);
    }
  }
  
}