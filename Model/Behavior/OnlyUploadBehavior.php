<?php

/**
 * Class OnlyUploadBehavior
 */
class OnlyUploadBehavior extends ModelBehavior {

	private $__defaults = array(
		'dir' => TMP,
		'permission' => 0755,
		'overwrite' => true
	);

/**
 * @param Model $model
 * @param array $settings
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->alias] = $settings;
	}

/**
 * @param Model $model
 * @param $field
 * @param $data
 * @return bool|File
 * @throws Exception
 */
	public function onlyUpload(Model $model, $field, $data) {
		if (!isset($this->settings[$model->alias]) || !array_key_exists($field, $this->settings[$model->alias])) {
			return false;
		}

		$options = $this->settings[$model->alias][$field];

		$filename = $data[$model->alias][$field]['tmp_name'];
		$permission = (isset($options['permission'])) ? $options['permission'] : $this->__defaults['permission'];
		$overwrite = (isset($options['overwrite'])) ? $options['overwrite'] : $this->__defaults['overwrite'];
		$uploadDir = (isset($options['dir'])) ? $options['dir'] : $this->__defaults['dir'];
		$newFileName = (isset($options['name'])) ? $options['name'] : $data[$model->alias][$field]['name'];
		$destination = $uploadDir . $newFileName;

		$oldFile = new File($destination);

		if (!$overwrite && $oldFile->exists()) {
			return;
		}

		$result = $this->_copyFileFromTemp($model, $filename, $destination, $permission);

		if ($result === true) {
			return new File($destination);
		} else {
			throw new Exception("Unable to uplaod $field file");
		}
	}

/**
 * @param Model $model
 * @param $data
 * @return array
 */
	public function onlyUploads(Model $model, $data) {
		$files = array();
		foreach ($data[$model->alias] as $field => $value) {
			$files[$field] = $this->onlyUpload($model, $field, $data);
		}
		return $files;
	}

/**
 * Copies file from temporary directory to final destination
 *
 * @param Model $model
 * @param string $tmpName full path to temporary file
 * @param string $saveAs full path to move the file to
 * @param integer $filePermission octal value of created file permission
 * @return mixed true is successful, error message if not
 * @access protected
 */
	protected function _copyFileFromTemp(Model $model, $tmpName, $saveAs, $filePermission) {
		$results = true;

		$file = new File($tmpName);
		$temp = new File($saveAs, true, $filePermission);
		if (!$temp->write($file->read())) {
			$results = __d('OnlyUpload', 'Problems in the copy of the file.');
		}
		$file->close();
		$temp->close();
		return $results;
	}

}
