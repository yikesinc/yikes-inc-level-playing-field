<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Tests\PhpUnit;

use PHPUnit\Framework\TestCase;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantRepository;

/**
 * Class ApplicantTest
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantTest extends TestCase {

	/** @var Applicant */
	private $applicant;

	/** @var ApplicantRepository */
	private static $applicant_repo;

	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass() {
		self::$applicant_repo = new ApplicantRepository();
	}

	/**
	 * Sets up the fixture, for example, open a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->applicant = self::$applicant_repo->create();
	}


	public function test_set_email() {
		$this->applicant->set_email( 'foo@example.com' );
		$this->assertEquals( 'foo@example.com', $this->applicant->get_email() );
	}
}
