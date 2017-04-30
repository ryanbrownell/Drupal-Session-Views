<?php

namespace Drupal\session_views\Controller;

class SessionViewsController {
    
    public function content()
    {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $selectedTerms = $session->get('selected_terms');
        
//        $tempstore = \Drupal::service('user.private_tempstore')->get('session_views');
//        $selectedTerms = $tempstore->get('selected_terms');
        
        $output = "";
        
        foreach ($selectedTerms as $term)
        {
            $output .= $term . " | ";
        }
        
        return array(
            '#title' => 'Hello World!',
            '#markup' => "This is a test page. Move along! Nothing to see here!<br />Test Results: " . $output,
            '#cache' => ['max-age' => 0,],
        );
    }    
}