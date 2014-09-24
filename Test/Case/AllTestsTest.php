<?php
class AllTestsTest extends PHPUnit_Framework_TestSuite {

/**
 * suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$suite = new CakeTestSuite('All OnlyUpload tests');
		$suite->addTestDirectoryRecursive(CakePlugin::path('OnlyUpload') . 'Test' . DS . 'Case' . DS);
		return $suite;
	}

}
