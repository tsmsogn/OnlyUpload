<?php
App::uses('OnlyUploadBehavior', 'OnlyUpload.Model/Behavior');
App::uses('File', 'Utility');

class TestModel extends CakeTestModel {

	public $useTable = false;

}

/**
 * OnlyUploadBehavior Test Case
 *
 */
class OnlyUploadBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// Create original file
		$this->original = new File(TMP . 'tests' . DS . String::uuid(), true, 0755);
		$this->original->write('bar');

		$this->TestModel = ClassRegistry::init('TestModel');

		$this->TestModel->Behaviors->attach('OnlyUpload.OnlyUpload', array(
			'default' => array(
				'name' => '0',
				'dir' => dirname($this->original->pwd()) . DS
			),
			'not_overwrite_field' => array(
				'overwrite' => false,
				'dir' => dirname($this->original->pwd()) . DS
			),
		));

		$this->data['test_ok'] = array(
			'TestModel' => array(
				'default' => array(
					'name' => $this->original->name(),
					'tmp_name' => $this->original->pwd()
				),
				'not_overwrite_field' => array(
					'name' => $this->original->name(),
					'tmp_name' => $this->original->pwd(),
					'overwrite' => false
				)
			)
		);

		$this->data['test_not_overwrite'] = array(
			'TestModel' => array(
				'not_overwrite_field' => array(
					'name' => $this->original->name(),
					'tmp_name' => $this->original->pwd(),
					'overwrite' => false
				)
			)
		);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		ClassRegistry::flush();
		unset($this->TestModel);

		// Delete test file
		$this->original->delete();
	}

/**
 * testOnlyUploads method
 *
 * @return void
 */
	public function testOnlyUploads() {
		$this->TestModel->onlyUploads($this->data['test_ok']);
		$file = new File(dirname($this->original->pwd()) . DS . '0');
		$this->assertTrue($file->exists());
		$file->delete();
	}

/**
 * testNameOption method
 *
 * @return void
 */
	public function testNameOption() {
		$this->TestModel->onlyUpload('default', $this->data['test_ok']);
		$file = new File(dirname($this->original->pwd()) . DS . '0');
		$this->assertTrue($file->exists());
		$file->delete();
	}

/**
 * testNotOverrideOption method
 *
 * @return void
 */
	public function testNotOverrideOption() {
		$lastAccess = $this->original->lastAccess();
		sleep(1);
		$this->TestModel->onlyUpload('not_overwrite_field', $this->data['test_ok']);
		$this->assertEqual($this->original->lastAccess(), $lastAccess);
	}

}
