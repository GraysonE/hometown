<?
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

spl_autoload_register(function ($class_name) {
	/*if (file_exists(REST_PLUGIN_PATH . "models/" . $class_name . ".class.php"))
	{
		require_once(REST_PLUGIN_PATH . "models/" . $class_name . ".class.php");
	} else */if (file_exists(REST_PLUGIN_PATH . "models/" . str_replace("tf_", "", $class_name) . ".class.php")) {
		require_once(REST_PLUGIN_PATH . "models/" . str_replace("tf_", "", $class_name) . ".class.php");
	}
});

function getView($path, $echo = false)
{
	if (file_exists(REST_PLUGIN_PATH . "views/" . $path . ".php"))
	{
		if ($echo)
			require(REST_PLUGIN_PATH . "views/" . $path . ".php");
		else
			return file_get_contents(REST_PLUGIN_PATH . "views/" . $path . ".php");
	} else if (file_exists(REST_PLUGIN_PATH . "views/" . $path . ".html"))
	{
		if ($echo)
			require(REST_PLUGIN_PATH . "views/" . $path . ".html");
		else
			return file_get_contents(REST_PLUGIN_PATH . "views/" . $path . ".html");
	} else {
		return false;
	}
}

/**
 * Class casting
 *
 * @param string|object $destination
 * @param object $sourceObject
 * @return object
 */
function cast($destination, $sourceObject)
{
    if (is_string($destination)) {
        $destination = new $destination();
    }
    $sourceReflection = new ReflectionObject($sourceObject);
    $destinationReflection = new ReflectionObject($destination);
    $sourceProperties = $sourceReflection->getProperties();
    foreach ($sourceProperties as $sourceProperty) {
        $sourceProperty->setAccessible(true);
        $name = $sourceProperty->getName();
        $value = $sourceProperty->getValue($sourceObject);
        if ($destinationReflection->hasProperty($name)) {
            $propDest = $destinationReflection->getProperty($name);
            $propDest->setAccessible(true);
            $propDest->setValue($destination,$value);
        } else {
            $destination->$name = $value;
        }
    }
    return $destination;
}


function ha_load_scripts() {

  wp_enqueue_style('hometown-api', REST_PLUGIN_URL . 'assets/css/hometown.css');
  wp_enqueue_style('swiper', REST_PLUGIN_URL . 'assets/js/Swiper-3.4.2/dist/css/swiper.min.css');

  wp_register_script('swiper', REST_PLUGIN_URL . 'assets/js/Swiper-3.4.2/dist/js/swiper.jquery.min.js', array('jquery'), '1', false);
  wp_register_script('hometown', REST_PLUGIN_URL . 'assets/js/hometown.js', array('jquery'), '1', false);

  $data = array(
      'apply_coupon_nonce' => wp_create_nonce('apply-coupon'),
      'remove_coupon_nonce' => wp_create_nonce('remove-coupon'),
      'update_order_nonce' => wp_create_nonce('update-order-review'),
      'remove_order_item' => wp_create_nonce('order-item'),
      'update_total_price_nonce'	=> wp_create_nonce('update_total_price'),
      'update_shipping_method_nonce'	=> wp_create_nonce('update-shipping-method'),
      'update_order_review'	=> wp_create_nonce('update-order-review'),
      'process_checkout' => wp_create_nonce('woocommerce-process_checkout'),
      'search_products'  =>  wp_create_nonce('search-products'),
      'ajaxurl'           => admin_url( 'admin-ajax.php' )
  );

  wp_localize_script('hometown', 'ha_localized_config', $data);

  wp_enqueue_script('swiper');
  wp_enqueue_script('hometown');

}
add_action('wp_enqueue_scripts', 'ha_load_scripts');






/*
 * php delete function that deals with directories recursively
 */
function delete_files($target) {
  if(is_dir($target)){
    $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

    foreach( $files as $file )
    {
      delete_files( $file );
    }

    rmdir( $target );
  } elseif(is_file($target)) {
    unlink( $target );
  }
}



@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );