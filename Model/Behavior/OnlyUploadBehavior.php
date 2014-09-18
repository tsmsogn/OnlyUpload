<?php

/**
 * Class OnlyUploadBehavior
 */
class OnlyUploadBehavior extends ModelBehavior {

	protected $_imageMimetypes = array(
		'image/bmp',
		'image/gif',
		'image/jpeg',
		'image/pjpeg',
		'image/png',
		'image/vnd.microsoft.icon',
		'image/x-icon',
		'image/x-png',
	);

	protected $_mediaMimetypes = array(
		'application/pdf',
		'application/postscript',
	);

	private $__defaults = array(
		'dir' => TMP
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
	 * @return bool|string
	 * @throws Exception
	 */
	public function onlyUpload(Model $model, $field) {
		$request = Router::getRequest();

		if (!isset($this->settings[$model->alias]) || !array_key_exists($field, $this->settings[$model->alias])) {
			return false;
		}

		$options = $this->settings[$model->alias][$field];

		$fileName = $request->data[$model->alias][$field]['tmp_name'];

		$extension = pathinfo($request->data[$model->alias][$field]['tmp_name'], PATHINFO_EXTENSION);
		$uploadFileName = isset($options['fileName']) ? $options['fileName'] : String::uuid() . '.' . $extension;
		$uploadDir = isset($options['dir']) ? $options['dir'] : $this->__defaults['dir'];
		$destination = $uploadDir . DS . $uploadFileName;

		if ($this->__handleUploadFile($model, $fileName, $destination)) {
			return $destination;
		} else {
			throw new Exception("Unable to uplaod $field file");
		}
	}

	/**
	 * @param Model $model
	 * @return array
	 * @throws Exception
	 */
	public function onlyUploads(Model $model) {
		$request = Router::getRequest();

		$paths = array();

		foreach ($request->data[$model->alias] as $field => $value) {
			try {
				$paths[$field] = $this->onlyUpload($model, $field);
			} catch (Exception $e) {
				throw new Exception("Unable to uplaod $field file");
			}
		}

		return $paths;
	}

	/**
	 * @param Model $model
	 * @param $fileName
	 * @param $destination
	 * @return bool
	 */
	private function __handleUploadFile(Model $model, $fileName, $destination) {
		if (is_uploaded_file($fileName)) {
			return move_uploaded_file($fileName, $destination);
		}
	}

	/**
	 * @param $mimetype
	 * @return bool
	 */
	protected function _isImage($mimetype) {
		return in_array($mimetype, $this->_imageMimetypes);
	}

	/**
	 * @param $mimetype
	 * @return bool
	 */
	protected function _isMedia($mimetype) {
		return in_array($mimetype, $this->_mediaMimetypes);
	}

}
