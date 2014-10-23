AnkaaCMS Community Edition
==========================

Content Management System with flexible moduling and extensions for multiple purpose usage. (e.g. mailingsystem, CRM, website, weblog, ERP, etc.)
This is the Community Edition of AnkaaCMS which will contain the base of this system which may be extended with commercial / community modules, themes and extensions
You are free to use this script and help with the development. When you use this script, please notice the License.
This CMS is still in Alpha and should not yet be used in a production environment.


External libraries
------------------

We use the following freely available libraries within AnkaaCMS:

* PHPMailer - https://github.com/PHPMailer/PHPMailer
* Smarty Template Engine v3 - http://www.smarty.net/
* HTML Purifier v4.6.0 - http://htmlpurifier.org/

Copyright
---------
DienstKoning - 2014



Installation instructions
-------------------------
There is no installer yet.

* Place the cms-data folder outside the public_html (the script will look for the location automaticaly as long as it is not in another subfolder.
* Run the SQL-script and add the website data in the sys_site and sys_siteprofile, etc. (the sql-script is located in the install folder)
* Look for the auth extension to create a user and password to login.
* Alter the config.ini in cms-data with the right configuration settings.
* Your version should now be ready to go.