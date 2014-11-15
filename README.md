# OnlyUpload

[![Build Status](https://travis-ci.org/tsmsogn/OnlyUpload.svg)](https://travis-ci.org/tsmsogn/OnlyUpload)

File upload plugin for CakePHP. Inspired cakephp-upload(https://github.com/josegonzalez/cakephp-upload)

## Installation

Put your app plugin directory as `OnlyUpload`.

### Enable plugin

In 2.0 you need to enable the plugin your app/Config/bootstrap.php file:

```php
<?php
CakePlugin::load('OnlyUpload');
?>
```

If you are already using CakePlugin::loadAll();, then this is not necessary.

### Usage

In model:

```php
<?php
class Foo extends AppModel {
	public $actsAs = array(
		'OnlyUpload.OnlyUpload' => array(
			'file_field' => array(
				'name' => 'new_file_name', // Default uploaded file name
				'dir' => 'upload_dir', // Default TMP
				'permission' => 0644 // Default 0755
			)
		)
	);
}
```

In view:

```php
<?php echo $this->Form->create('Foo', array('type' => 'file')); ?>
<?php echo $this->Form->file('file_field'); ?>
<?php echo $this->Form->end(__('Submit')); ?>
```

In controller:

```php
try {
	$file = $this->Foo->onlyUpload('file_field', $this->request->data);
}
```

or

```php
try {
	$files = $this->Foo->onlyUploads($this->request->data);
}
```

## License

The MIT License (MIT)

Copyright (c) 2014 Toshimasa Oguni

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
