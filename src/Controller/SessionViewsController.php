<?php

namespace Drupal\session_views\Controller;

class SessionViewsController {
    
    public function content()
    {
        $tempstore = \Drupal::service('user.private_tempstore')->get('session_views');
        $selectedTerm = $tempstore->get('selected_terms');
        
        $output = "";
        
        foreach ($selectedTerm as $term)
        {
            $output .= $term . " | ";
        }
        
        return array(
            '#title' => 'Hello World!',
            '#markup' => "This is a test page. Move along! Nothing to see here! Test Results: " . $output,
        );
    }    
}