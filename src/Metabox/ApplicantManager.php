<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use WP_Screen;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;
use Yikes\LevelPlayingField\Assets\StyleAsset;

/**
 * Class ApplicantManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class ApplicantManager implements AssetsAware, Service {

	use AssetsAwareness;

	const CSS_HANDLE = 'lpf-admin-applicant-css';
	const CSS_URI    = 'assets/css/lpf-applicant-admin';

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'in_admin_header', function() {
			$this->set_screen_columns();
		} );

		add_action( 'edit_form_top', function() {
			$this->enqueue_assets();
			$this->do_applicant_content();
		} );

		add_action( "add_meta_boxes_{$this->get_post_type()}", function() {
			$this->meta_boxes();
		} );
	}

	/**
	 * Register our meta boxes, and remove some default boxes.
	 *
	 * @since %VERSION%
	 */
	private function meta_boxes() {
		// Remove some of the core boxes.
		remove_meta_box( 'submitdiv', $this->get_post_type(), 'side' );
		remove_meta_box( 'slugdiv', $this->get_post_type(), 'normal' );
		remove_meta_box( 'authordiv', $this->get_post_type(), 'normal' );
	}

	/**
	 * Output the Applicant content.
	 *
	 * @since %VERSION%
	 */
	private function do_applicant_content() {
		$html = '<article id="single-applicant-view">';
		//$html .= 'Applicant content';
		$html .= '<img class="avatar" src="https://via.placeholder.com/350x350" alt="Placeholder" />';
		$html .= '<h5>Nickname 123</h5>';
		$html .= '<h5><span class="label">Job:</span>';
		$html .= 'Job Title</h5>';
		$html .= '<section id="basic-info">';
		$html .= '<h2>Basic Info</h2>';
		$html .= '<p class="location"><span class="label">Location:</span>';
		$html .= 'City, ';
		$html .= 'State</p>';
		$html .= '</section>';
		$html .= '<section id="education">';
		$html .= '<h2>Education</h2>';
		$html .= '';
		$html .= '</section>';
		$html .= '<section id="skills">';
		$html .= '</section>';
		$html .= '<section id="languages">';
		$html .= '</section>';
		$html .= '<section id="experience">';
		$html .= '</section>';
		$html .= '<section id="volunteer-work">';
		$html .= '</section>';
		$html .= '<section id="misc">';
		$html .= '</section>';
		$html .= '</article>';
		echo $html;

		// @todo: this isn't the proper way to display the taxonomy box; change it.
		$status = new ApplicantStatus();
		$status->meta_box_cb( get_post() );
	}

	/**
	 * Set the number of screen columns to 1.
	 *
	 * @since %VERSION%
	 */
	private function set_screen_columns() {
		$screen = get_current_screen();
		if ( $this->get_post_type() !== $screen->post_type ) {
			return;
		}

		$screen->add_option( 'layout_columns', [
			'default' => 1,
			'max'     => 1,
		] );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$applicant = new ScriptAsset( 'lpf-applicant-manager-js', 'assets/js/applicant-manager', [ 'jquery' ] );
		$applicant->add_localization( 'applicantManager', [
			'title' => _x( 'Applicants | Applicant ID', 'heading when viewing an applicant', 'yikes-level-playing-field' ),
		] );

		return [
			$applicant,
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	private function get_post_type() {
		return ApplicantCPT::SLUG;
	}
}
