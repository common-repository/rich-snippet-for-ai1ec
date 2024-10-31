<?php

/*
Plugin Name: Rich snippet for All-in-One Event Calendar's
Plugin URI: https://wordpress.org/plugins/rich-snippet-for-ai1ec/
Description: Add schema.org's event markup to events in Ai1EC's (All-in-One Event Calendar's).
Version: 1.0.3
Author: OC2PS, Andry Gorokhovets, topmba
Author URI: https://profiles.wordpress.org/abwebgorohovets
Text Domain: rsai1ec
License: GPLv2 or later
*/

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-base-activator.php
 */
function activate_rsai1ec() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-rsai1ec-activator.php';
  RSAI1EC_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_rsai1ec' );

// Init RSAI1EC plugin
function rsai1ec_plugin_init() {
  // Check if All-in-One Event Calendar is disabled, if yes, disabled itself
  if ( !class_exists('Ai1ec_Base', false) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
  }
}
add_action( 'plugins_loaded', 'rsai1ec_plugin_init' );

// Add json to event calendar function
function add_json_to_event_calendar($content) {
  global $post;
  $output = $content;

  if ('ai1ec_event' === $post->post_type) {
    global $wpdb;
    $table_name = $wpdb->prefix . "ai1ec_events";

    $sql = "SELECT * FROM  `".$table_name."`
            WHERE  `post_id` =  '".$post->ID."'
            LIMIT 1";
    $event = $wpdb->get_results($sql);

    if( isset($event[0] )){
      $start         = $event[0]->start;
      $end           = $event[0]->end;
      $address       = $event[0]->address;
      $contact_name  = $event[0]->contact_name;
      $contact_phone = $event[0]->contact_phone;
      $contact_email = $event[0]->contact_email;
      $contact_url   = $event[0]->contact_url;
      $venue         = $event[0]->venue; // Place name
      $postal_code   = $event[0]->postal_code;
      $ticket_url    = $event[0]->ticket_url;
      $cost          = $event[0]->cost;

      // Start Json output
      $output .= '<script type="application/ld+json">{
      "@context": "http://schema.org/",
      "@type": "Event",';

      // Event Name
      $output .= '"name": "'.$post->post_title.'",';

      // Image thumbnail
      $post_thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
      $output .= ( isset($post_thumbnail_url[0]) ? ' "image": "'.$post_thumbnail_url[0].'",' : '' );

      // startDate
      $output .= ( !empty($start) ? ' "startDate": "'.date('c', $start).'",' : '' );

      // endDate
      $output .= ( !empty($end) ? ' "endDate": "'.date('c', $end).'",' : '' );

      // duration
      if(!empty($start) && !empty($end)) {
        $duration = $end - $start;
        $duration = time_to_iso8601_duration( $duration );
        $output .= ( !empty($end) ? ' "duration": "'.$duration.'",' : '' );
      }

      // Description
      $description = htmlspecialchars ( wp_strip_all_tags( $post->post_content ), ENT_QUOTES, 'utf-8', false );
      $output .= ( !empty( $description ) ? ' "description": "'.$description.'",' : '' );

      // location
      if( isset( $address ) || isset( $venue ) ) {
        $location_tmp_output = '';
        $location_tmp_output .= ' "location": { "@type": "Place",';
        // Place name
        $location_tmp_output .= ( !empty($venue) ? ' "name": "'.htmlspecialchars( $venue, ENT_QUOTES, 'utf-8', false ).'",' : '' );

        // PostalAddress
        if ( !empty( $address ) ) {
          // address
          $location_tmp_output .= ' "address": { "@type": "PostalAddress", "addressLocality": "'.$address.'",';

          // addressRegion
          if ( !empty($postal_code) ) {
            $location_tmp_output .= ' "addressRegion": "'.$postal_code.'"';
          }
          else {
            $location_tmp_output  = substr($location_tmp_output, 0, -1); // Delete last ','
          }

          // End PostalAddress section
          $location_tmp_output .= '}';
        }

        $output .= $location_tmp_output;
        // End Location section
        $output .= '},';
      }

      // Sponsor (Organization)
      if( isset( $contact_name ) || isset( $contact_phone ) || isset( $contact_email ) || isset( $contact_url ) ) {
        $sponsor_tmp_output  = '';
        $sponsor_tmp_output .= '"sponsor": { "@type": "Organization",';
        $sponsor_tmp_output .= ( !empty( $contact_name )  ? ' "name": "'.$contact_name.'",' : '' );
        $sponsor_tmp_output .= ( !empty( $contact_phone ) ? ' "telephone": "'.$contact_phone.'",' : '' );
        $sponsor_tmp_output .= ( !empty( $contact_email ) ? ' "email": "mailto:'.$contact_email.'",' : '' );
        $sponsor_tmp_output .= ( !empty( $contact_url )   ? ' "url": "'.$contact_url.'",' : '' );
        $sponsor_tmp_output  = substr( $sponsor_tmp_output, 0, -1 ); // Delete last ','
        $sponsor_tmp_output .= '},';
        $output             .= $sponsor_tmp_output;
      }

      // Offer
      // Prepare price
      $cost = ( maybe_unserialize( $cost ) );
      $price = $cost['cost'];
      $price = ( 1 == $cost['is_free'] ) ? ' 0 ' : $price;

      if( isset ( $ticket_url ) || isset ( $price ) ) {
        $offer_tmp_output  = '';
        $offer_tmp_output .= '"offers": [ { "@type": "Offer",';
        $offer_tmp_output .= ( !empty( $ticket_url ) ? ' "url": "'.$ticket_url.'",' : '' );
        $offer_tmp_output  = substr( $offer_tmp_output, 0, -1 ); // Delete last ','
        $offer_tmp_output .= '}],';
        $output           .= $offer_tmp_output;
      }

      // URL
      $output .= '"url": "'.get_permalink().'"';

      // End Json output
      $output .= '}</script>';
    }
  }
  return $output;
}
add_action( 'the_content', 'add_json_to_event_calendar', 10, 2 );

// Helper function to convert unix time to iso8601 duration format
function time_to_iso8601_duration( $time ) {
  $units = array(
    "Y" => 365*24*3600,
    "D" =>     24*3600,
    "H" =>        3600,
    "M" =>          60,
    "S" =>           1,
  );

  $str = "P";
  $istime = false;

  foreach ( $units as $unitName => &$unit ) {
    $quot  = intval( $time / $unit );
    $time -= $quot * $unit;
    $unit  = $quot;
    if ($unit > 0) {
      if ( !$istime && in_array( $unitName, array( "H", "M", "S" ) ) ) {
        $str .= "T";
        $istime = true;
      }
      $str .= strval($unit) . $unitName;
    }
  }

  return $str;
}