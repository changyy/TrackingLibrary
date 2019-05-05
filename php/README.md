# Using PHP Codeigniter-3.1.10

Use unzip:

```
$ wget https://github.com/bcit-ci/CodeIgniter/archive/3.1.10.zip
$ unzip 3.1.10.zip
...
$ rm 3.1.10.zip
$ rsync -av CodeIgniter-3.1.10/ web/ --exclude "user_guide" --exclude "license.txt" --exclude "readme.rst" --exclude "contributing.md" --exclude "composer.json" --exclude ".editorconfig" --exclude ".gitignore"
$ rsync -av CI-3/ web/
```

Use bsdtar:

```
$ wget https://github.com/bcit-ci/CodeIgniter/archive/3.1.10.zip
$ bsdtar --strip-components=1 -xvf 3.1.10.zip -C web --exclude "*/user_guide/*" --exclude "*/license.txt" --exclude "*/readme.rst" --exclude "*/contributing.md" --exclude "*/composer.json" --exclude "*/.editorconfig" --exclude "*/.gitignore"
$ rsync -av CI-3/ web/
```
# Update API info

```
$ head -n 10 Codeigniter-3.1.9/application/controllers/Dashboard.php 
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->client_id = 'YOUR-Project.apps.googleusercontent.com';
		$this->ga_profile_id = 'ga:your-ga-view';
	}
```

# Run

```
$ cd web/
$ php composer.phar install
$ php -S localhost:8000 ../tools/ci-routing.php 
```

# ref
## CI Changelog

https://www.codeigniter.com/userguide3/changelog.html
