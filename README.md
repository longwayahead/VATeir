# VATeir Website and Training System

In order to get this website to work on your own server you must see to the following:

## Website -> Database
* Rename core/initdemo.php to core/init.php.
* Insert relevant details to your setup.
* Example schematic is provided in the root directory under "vateir_website.sql".

## Website -> VATSIM SSO -> Website
* Rename login/configdemo.php to login/config.php.
* This will allow you to use the DEMO SSO logins (see the VATSIM forums for these) to login.

## PHPBB 3 Forum Integration
* You will need to modify the default PHPBB database schema to include a column called `vatsim_id` in the phpbb_users table.

## CRON Job
* Add update_controllers.php to your crontab.
* This script updates members' records if there has been an update to their CERT, it registers new users, and it does a cleanup of some database things.

## Log VATSIM Statistics
* The statistics folder is completely separate to the rest of the system with its own database and logic.
* DB Schematic provided at "vateir_statistics.sql".
* Just add "update.php" to your CRON to run at a conservative interval in order to update controllers online.
* We don't need to download the VATSIM data file every two minutes, that'd be silly.
* Add "pilots".php to your CRON too. This doesn't download the datafile, but should be run at the same interval as "update.php". 
* Make sure to modify the files to your needs including the regex in "update.php" and "pilots.php".
