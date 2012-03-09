# The Harvest Club

This is a contact management system designed for The Harvest Club by a group of students in IN4MATX 117 at UC, Irvine.
We have permission to open source our code, but we do not offer support so use at your own risk.


## Config
You will need to create a file called `include/config.inc.php` and define some constants in there. Here is an example:

```php
<?php
define('PAGE_TITLE', 'The Harvest Club');
define('PAGE_QUOTE', 'Share the Bounty');

define('MYSQL_SERVER', 'localhost');
define('MYSQL_USER', 'nyan');
define('MYSQL_PASS', 'd4x9S0Pyo');
define('MYSQL_DB', 'harvest');

define('MAIL_FROM', 'admin@example.com');
define('MAIL_TO', 'admin@example.com');
define('MAIL_REPLYTO', 'noreply@example.com');

define('SESSION_MAX_LENGTH', 3600); // logout after inactive for x seconds
?>
```

## Issues

Look at [issues](https://github.com/styfle/The-Harvest-Club/issues) to find out what you need to work on

## Getting started with git

    git clone git@github.com:styfle/The-Harvest-Club.git
    git add some-file.php
    git commit -m "Added some-file.php that is used for something."
    git push origin master

This will clone the repo. Then you add your file to the repo. Commit changes. Then you can push all your changes to github (origin) from your master branch.

