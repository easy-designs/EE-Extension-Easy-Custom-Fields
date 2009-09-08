<?php

if ( !defined( 'EXT' ) ) exit('Invalid file request');

/*
=====================================================
 Easy Custom Fields - by Easy! Designs, LLC
-----------------------------------------------------
 http://www.easy-designs.net/
=====================================================
 This extension was created by Aaron Gustafson
 - aaron@easy-designs.net
=====================================================
 File: ext.easy_custom_fields.php
-----------------------------------------------------
 Purpose: exposes custom fields via $SESS
=====================================================
*/

class Easy_custom_fields
{
  var $settings       = array();

  var $name           = 'Easy Custom Fields';
  var $version        = '1.0';
  var $description    = 'exposes custom fields via $SESS';
  var $settings_exist = 'n';
  var $docs_url       = '';
      
 	// -------------------------------
  // Constructor
  // -------------------------------
  function Easy_custom_fields($settings='')
  {
    $this->settings = $settings;
	}
  
  // --------------------------------
  //  init
  // -------------------------------- 
  function initialize( $obj )
  {
    global $DB;
    $obj->cache['custom_fields'] = array();
    $query = $DB->query(
      "SELECT `field_id`,
              `field_name`
       FROM `exp_weblog_fields`"
    );
    foreach ( $query->result as $row )
    {
      $obj->cache['custom_fields'][$row['field_name']] = 'field_id_' . $row['field_id'];
    }
  }

  // --------------------------------
  //  Activate Extension
  // --------------------------------
  function activate_extension()
  {
    global $DB, $PREFS;
    $DB->query(
      $DB->insert_string(
        $PREFS->ini('db_prefix') . '_extensions',
        array(
          'extension_id' => '',
          'class'        => __CLASS__,
          'method'       => 'initialize',
          'hook'         => 'sessions_start',
          'settings'     => '',
          'priority'     => 10,
          'version'      => $this->version,
          'enabled'      => 'y'
        )
      )
    );
  }
   
  // --------------------------------
  //  Update Extension
  // --------------------------------  
  function update_extension($current=''){
    global $DB, $PREFS;
    
    if ($current == '' OR
        $current == $this->version) return FALSE;
    
    if ($current <= '1.0')
    {
      // Update to next version: 1.1
      // Just a setting update...
    }
    
    $DB->query(
      "UPDATE `" . $PREFS->ini('db_prefix') . "_extensions` 
       SET    `version` = '" . $DB->escape_str($this->version) . "'
       WHERE  `class` = '" . __CLASS__ . "'"
    );
  }

  // --------------------------------
  //  Disable Extension
  // --------------------------------
  function disable_extension(){
    global $DB, $PREFS;
    $DB->query(
      "DELETE FROM `" . $PREFS->ini('db_prefix')."_extensions`
       WHERE  `class` = '" . __CLASS__ . "'"
    );
  }
   
}

?>