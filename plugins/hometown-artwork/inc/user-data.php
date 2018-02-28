<?php

add_action( 'wp_ajax_hometown_save_imprint_artwork', 'hometown_save_imprint_artwork' );
/**
 * Save the imprint artwork url and color to the database
 *
 * return @void
 */
function hometown_save_imprint_artwork() {

	$uniqueIdentifier = $_POST['variation_id'];

	$frontURL    = ( isset( $_POST['frontURL'] ) ) ? 'FrontURL=' . $_POST['frontURL'] : '';
	$frontColor  = ( isset( $_POST['frontColor'] ) ) ? 'FrontColor=' . $_POST['frontColor'] : '';
  $frontID  = ( isset( $_POST['frontArtworkID'] ) ) ? 'FrontID=' . $_POST['frontArtworkID'] : '';
	$backURL     = ( isset( $_POST['backURL'] ) ) ? 'BackURL=' . $_POST['backURL'] : '';
	$backColor   = ( isset( $_POST['backColor'] ) ) ? 'BackColor=' . $_POST['backColor'] : '';
  $backID  = ( isset( $_POST['backArtworkID'] ) ) ? 'BackID=' . $_POST['backArtworkID'] : '';
	$sleeveURL   = ( isset( $_POST['sleeveURL'] ) ) ? 'SleeveURL=' . $_POST['sleeveURL'] : '';
	$sleeveColor = ( isset( $_POST['sleeveColor'] ) ) ? 'SleeveColor=' . $_POST['sleeveColor'] : '';
  $sleeveID  = ( isset( $_POST['sleeveArtworkID'] ) ) ? 'SleeveID=' . $_POST['sleeveArtworkID'] : '';

	$meta_key   = 'imprint_artwork-' . $uniqueIdentifier;
	$imprintCSV = $frontURL . ',' . $frontColor . ',' . $frontID . ',' . $backURL . ',' . $backColor . ',' . $backID . ',' . $sleeveURL . ',' . $sleeveColor . ',' . $sleeveID;

	$prev_value = get_user_meta( get_current_user_id(), $meta_key, true );
	$newID = update_user_meta( get_current_user_id(), $meta_key, $imprintCSV, $prev_value );

	wp_send_json( array(
		'action' => 'hometown_save_imprint_artwork',
		'result' => ( $newID ),
		'newID'  => $newID
	) );
	wp_die();

}


function hometown_get_imprint_artwork($variationID) {

  $uniqueIdentifier = $variationID;
  $meta_key   = 'imprint_artwork-' . $uniqueIdentifier;

  $imprintArtworkData = explode(',', get_user_meta( get_current_user_id(), $meta_key, true ));

  if ($imprintArtworkData[0] !== '') {

    $imprintDataArray = array();

    foreach ($imprintArtworkData as $data) {

      $keyValuePair = explode('=', $data);

      $key = $keyValuePair[0];
      $value = $keyValuePair[1];

      if (strpos($key, 'Front') !== false) {
        if (strpos($key, 'Color') !== false) {
          $imprintDataArray['Front']['color'] = $value;
        } else if (strpos($key, 'URL') !== false) {
          $imprintDataArray['Front']['url'] = $value;
        } else {
          $imprintDataArray['Front']['id'] = $value;
        }
      } else if (strpos($key, 'Back') !== false) {
        if (strpos($key, 'Color') !== false) {
          $imprintDataArray['Back']['color'] = $value;
        } else if (strpos($key, 'URL') !== false) {
          $imprintDataArray['Back']['url'] = $value;
        } else {
          $imprintDataArray['Back']['id'] = $value;
        }
      } else if (strpos($key, 'Sleeve') !== false) {
        if (strpos($key, 'Color') !== false) {
          $imprintDataArray['Sleeve']['color'] = $value;
        } else if (strpos($key, 'URL') !== false) {
          $imprintDataArray['Sleeve']['url'] = $value;
        } else {
          $imprintDataArray['Sleeve']['id'] = $value;
        }
      }

    }

    return $imprintDataArray;

  } else {
    return false;
  }



}


function hometown_get_artwork_price($artworkPostID) {

  global $wpdb;

  $query = "SELECT meta_value FROM `wp_postmeta` where post_id = %d and meta_key = 'hamf_artwork_price'";
  $sql = $wpdb->prepare($query, array($artworkPostID));
  $priceResults = $wpdb->get_results($sql);
  foreach ($priceResults as $priceResult) {
    return $priceResult->meta_value;
  }

}