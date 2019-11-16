<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Query;

use WP_Query;
use Yikes\LevelPlayingField\Model\MetaLinks;

/**
 * Class BaseQuery
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class BaseQueryBuilder {

	/**
	 * Query args.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $args = [
		'meta_query'             => [],
		'post_status'            => [ 'any' ],
		'tax_query'              => [],
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

	/**
	 * BaseQuery constructor.
	 */
	public function __construct() {
		$this->args['post_type'] = $this->get_post_type();
	}

	/**
	 * Get the arguments of the query.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_args() {
		return $this->args;
	}

	/**
	 * Get the results
	 *
	 * @since 1.0.0
	 * @return WP_Query
	 */
	public function get_query() {
		return new WP_Query( $this->args );
	}

	/**
	 * Filter the fields returned by the query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $fields The fields to return.
	 *
	 * @return $this
	 */
	public function fields( $fields ) {
		$this->args['fields'] = $fields;

		return $this;
	}

	/**
	 * Declare that the query is to obtain a count.
	 *
	 * This is a shortcut for setting 1 post per page, and obtaining IDs.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function for_count() {
		return $this->posts_per_page( 1 )->fields( 'ids' );
	}

	/**
	 * Filter the query by a particular year/date.
	 *
	 * @since 1.0.0
	 *
	 * @param int $m The year/date value. E.g. 201908 for August 2019.
	 *
	 * @return $this
	 */
	public function m( $m ) {
		$this->args['m'] = $m;

		return $this;
	}

	/**
	 * Filter the query with a meta query.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key     The meta key.
	 * @param string $value   The meta value.
	 * @param string $compare The comparison mode.
	 * @param string $type    The field type.
	 *
	 * @return $this
	 */
	public function meta_query( $key, $value, $compare = '=', $type = 'CHAR' ) {
		$this->args['meta_query'][] = [
			'key'     => $key,
			'value'   => $value,
			'compare' => $compare,
			'type'    => $type,
		];

		return $this;
	}

	/**
	 * Filter the query by a meta value not existing.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The key to ensure does not exist.
	 *
	 * @return $this
	 */
	public function meta_not_exists( $key ) {
		$this->args['meta_query'][] = [
			'key'     => $key,
			'compare' => 'NOT EXISTS',
		];

		return $this;
	}

	/**
	 * Define a meta query as an OR comparison.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function meta_or_relation() {
		$this->args['meta_query']['relation'] = 'OR';

		return $this;
	}

	/**
	 * Change what field is used to order the results.
	 *
	 * @since 1.0.0
	 *
	 * @param string $orderby The field to order the results.
	 *
	 * @return $this
	 */
	public function orderby( $orderby ) {
		$this->args['orderby'] = $orderby;

		return $this;
	}

	/**
	 * Return the results in ascending order.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function order_ascending() {
		$this->args['order'] = 'ASC';

		return $this;
	}

	/**
	 * Exclude posts by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $not_in Post IDs to exclude. A string should be comma-separated.
	 *
	 * @return $this
	 */
	public function post__not_in( $not_in ) {
		$not_in                     = is_array( $not_in ) ? $not_in : array_map( 'trim', explode( ',', $not_in ) );
		$this->args['post__not_in'] = $not_in;

		return $this;
	}

	/**
	 * Filter the posts per page.
	 *
	 * @since 1.0.0
	 *
	 * @param int $number The number of posts per page.
	 *
	 * @return $this
	 */
	public function posts_per_page( $number ) {
		$this->args['posts_per_page'] = intval( $number );

		return $this;
	}

	/**
	 * Filter the post status for this query.
	 *
	 * @since 1.0.0
	 *
	 * @param array $status The array of valid statuses.
	 *
	 * @return $this
	 */
	public function post_status( $status ) {
		$this->args['post_status'] = (array) $status;

		return $this;
	}

	/**
	 * Filter the results by a searc string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $s The string to search for.
	 *
	 * @return $this
	 */
	public function s( $s ) {
		$this->args['s'] = $s;

		return $this;
	}

	/**
	 * Filter the query by taxonomy terms.
	 *
	 * @since 1.0.0
	 *
	 * @param string           $taxonomy         The taxonomy to use.
	 * @param int|string|array $terms            Terms to filter by.
	 * @param string           $field            The term field to target.
	 * @param string           $operator         The operator to use when comparing terms.
	 * @param bool             $include_children Whether to include term children.
	 *
	 * @return $this
	 */
	public function tax_query( $taxonomy, $terms, $field = 'term_id', $operator = 'IN', $include_children = true ) {
		$this->args['tax_query'][] = [
			'taxonomy'         => $taxonomy,
			'terms'            => $terms,
			'field'            => $field,
			'operator'         => $operator,
			'include_children' => $include_children,
		];

		return $this;
	}

	/**
	 * Allow the post meta cache to be updated.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function update_post_meta_cache() {
		$this->args['update_post_meta_cache'] = true;

		return $this;
	}

	/**
	 * Allow the post term cache to be updated.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function update_post_term_cache() {
		$this->args['update_post_term_cache'] = true;

		return $this;
	}

	/**
	 * Filter the query by Application ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The application ID.
	 *
	 * @return $this
	 */
	public function where_application_id( $id ) {
		return $this->meta_query( MetaLinks::APPLICATION, $id );
	}

	/**
	 * Get the post type to use with this query.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract protected function get_post_type();
}
