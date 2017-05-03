<?php

namespace Drupal\session_views\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("session_views_filter_field")
 */

class SessionViewsFilterField extends FilterPluginBase {
  
  //@todo Review verf\src\Plugin\views\filter\EntityReference.php
  
  //@todo Include buildOptionsForm method
  
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Node Domain filter');
  }

  public function query() {
    
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
  
}
