<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    redirect_migration
 * @subpackage redirect_migration/includes
 */

/**
 * The model class
 *
 * This class interfaces with the database
 *
 * @since      1.0.0
 * @package    redirect_migration
 * @subpackage redirect_migration/includes
 * @author     Your Name <email@example.com>
 */
class Redirect_Migration_Map {

	private function __construct($data) {
		$url_to = !empty($data['url_to']) ? $data['url_to'] : NULL;
		$status = !empty($data['status']) ? $data['status'] : NULL;

		$this->to = $url_to;
		$this->status = $status;
	}

	private static function normalizeUrl($url) {
		$parsed = parse_url($url);

		$path = $parsed['path']; // Remove trailing slash
		$path = preg_replace("#\/$#", "", $path);

		return $path;
	}

	private function mappedTo() {
		$query_string = $_SERVER["QUERY_STRING"];
		$parsed = parse_url($this->to);
		$parsed['query'] = $query_string;

		$url = http_build_url('', $parsed);
		return $url;
	}

	public function render() {
		if (!function_exists('wp_redirect')) return;
		wp_redirect($this->mappedTo(), $this->status);
		exit;
	}

	public function __toString() {
		return $this->mappedTo();
	}

  private static function tablename() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirect_map';

    return $table_name;

  }

  public static function addEntry( $url, $maps_to = '/', $status = 301 ) {
    global $wpdb;

    $wpdb->insert(
      self::tablename(),
      array(
      	'time' => current_time( 'mysql' ),
      	'url_from' => self::normalizeUrl($url),
      	'url_to' => $maps_to,
        'status' => $status,
        'active' => 1
      )
    );
  }

  public static function mapFor( $url ) {
    global $wpdb;

		$url = self::normalizeUrl($url);

    $results = $wpdb->get_results( self::getURLEntry($url) , ARRAY_A ); // Associative array

		if (count($results) > 0) {
			$result = $results[0];

			return new self($result);
		} else {
			return false;
		}

  }

  private static function getURLEntry($url) {
    global $wpdb;

    $table_name = self::tablename();

    return $wpdb->prepare(
      "
      	SELECT url_to, status FROM $table_name
      	WHERE active = %d
        AND url_from = %s
				ORDER BY time DESC
      ",
      1,
      $url
    );

  }

}
