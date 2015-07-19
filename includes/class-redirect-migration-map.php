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
		$status = !empty($data['status']) ? $data['status'] : 301;
		$active = !empty($data['active']) ? $data['active'] : true;
		$url_from = !empty($data['url_from']) ? $data['url_from'] : NULL;
		$id = !empty($data['id']) ? $data['id'] : NULL;

		$this->to = $url_to;
		$this->status = $status;
		$this->ID = $id;
		$this->active = $active;
		$this->from = $url_from;
	}

	private static function create($data) {
		return new self($data);
	}

	public static function init($url_from, $url_to, $status = 301) {

		return new self(array(
			'url_to' => $url_to,
			'url_from' => $url_from,
			'status' => $status
		));
	}

	public function from() {
		return $this->from;
	}

	public function active() {
		return $this->active == true;
	}

	public function ID() {
		return $this->ID;
	}

	public function to() {
		return $this->to;
	}

	public function status() {
		return $this->status;
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

  public function save() {
  	$id = self::addEntry( $this->from, $this->to, $this->status );

  	$this->active = 1;
  	$this->ID = $id;
  	$this->from = self::normalizeUrl($this->from);
  }

  public static function addEntry( $url, $maps_to = '/', $status = 301 ) {
    global $wpdb;
    $wpdb->show_errors();

    $data = array(
      	'time' => current_time( 'mysql' ),
      	'url_from' => self::normalizeUrl($url),
      	'url_to' => $maps_to,
        'status' => $status,
        'active' => 1
      );

    $wpdb->insert(
      self::tablename(),
      $data
    );
    $id = $wpdb->insert_id;

    return $id;

  }

	public static function all() {
		global $wpdb;
		$table_name = self::tablename();

		$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY time DESC", ARRAY_A );
		return array_map(array(self, 'create'), $results);
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

	public static function batch( $array ) {
		global $wpdb;

		$table_name = self::tablename();

		$sql = "

			INSERT INTO $table_name
			(time, url_from, url_to, active, status)
			VALUES
		";

		$values = array();

		foreach($array as $mapping) {
			if (!($mapping instanceof self)) continue;
			$values[] = $wpdb->prepare( "(%s, %s, %s, %d, %d )",
				current_time( 'mysql' ),
				self::normalizeUrl($mapping->from),
				$mapping->to,
				1,
				$mapping->status );
		}

		$sql .= implode(",\n", $values);

		$wpdb->query($sql);


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

	public static function defaultRedirect($request_uri) {

		$data = array(
			'id' => 0,
			'url_to' => 'https://www.ebayinc.com/stories/news' . self::normalizeUrl($request_uri),
			'status' => 302,
			'active' => true,
			'url_from' => '*'
		);

		return new self($data);

	}

}
