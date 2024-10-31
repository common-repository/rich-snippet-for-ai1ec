<?php

// Fired during plugin activation
class RSAI1EC_Activator {

  public function __construct() {
    register_activation_hook( __FILE__, array( $this , 'activate' ) );
  }

  static function activate() {
    // Stop activation if all-in-one-event-calendar in not active
    if( !is_plugin_active( 'all-in-one-event-calendar/all-in-one-event-calendar.php' ) ) {
      deactivate_plugins( plugin_basename( __FILE__ ) );
      exit(sprintf("<a href='https://wordpress.org/plugins/all-in-one-event-calendar/' target='_blank'>All-in-one Event Calendar</a> must be installed and activated first."));
    }
  }

}