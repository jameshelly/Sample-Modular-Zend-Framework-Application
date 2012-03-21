# Sample Zend Modular Application

- Zend Framework 1.11.9 + Doctrine 2.2.1 integration
This is a sample application to allow you use Doctrine 2 at the top of Zend Framework 1.

The following Libraries and extensions have been used:

 +  Zend 1.11.9 
		-  ZF1 Classmap Autoloaders
 +  Doctrine 2.2.1
 +  Gedmo Doctrine Extensions 2.2.1
 + Assetic 1.1.0
I don't use the typical layout for a Zend Application, this is mainly due to the servers I typically use for sites. I place the application, bin, data & library folders into a private folder, I also rename public to content.

Zend Custom Structure:

 *  content
 *  private
	 +  application
	 +  bin
	 +  library

Compared to the Zend Standard Structure:

 *  application
 *  bin
 *  docs
 *  library
 *  public
 *  tests


# Installing

# Zend 1.11.9
git://github.com/Enrise/Zend.git
http://devzone.zend.com/article/16343-Zend-Framework-1.11.9-Released
http://framework.zend.com/download/latest

# Classmap Autoloader
git://github.com/weierophinney/zf-examples.git
http://weierophinney.net/matthew/archives/262-Backported-ZF2-Autoloaders.html
https://github.com/weierophinney/zf-examples/blob/feature%2Fzf1-classmap/zf1-classmap/README.md

# Doctrine 2.1
git://github.com/doctrine/doctrine2.git
http://www.doctrine-project.org/
http://www.doctrine-project.org/docs/orm/2.1/en/reference/introduction.html

# Gedmo 2.x
git://github.com/l3pp4rd/DoctrineExtensions.git
https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc
https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/annotations.md

### Using
Its a pretty raw application, with a default & blog modules.

# Configuring
I have now included relevant libraries (Doctrine & Gedmo repositories).
- Configuration Example
check the default.ini file for an example configuration
all entries starting with "resources.doctrine" 

# Using
Its a pretty raw application, with a default & blog modules.

# Configuring Classmaps
	site/root $ php private/bin/classmap_generator.php -l ../application/
	site/root $ php private/bin/classmap_generator.php -l ../library/

# Configuring Doctrine
There is a d2.default.php example, you should copy the file and rename it d2.php set it up for a connection.

- Code Example
	site/root $ php private/bin/d2.php orm:validate
	site/root $ php private/bin/d2.php orm:schema-tool:create
	site/root $ php private/bin/d2.php orm:generate-proxies

### Useful commandline functions.
	php private/bin/d2.php orm:validate
	php private/bin/d2.php orm:info
	php private/bin/d2.php orm:schema-tool:create
	php private/bin/d2.php orm:schema-tool:drop --force --full-database
	php private/bin/d2.php orm:schema-tool:create
	php private/bin/d2.php orm:generate-proxies
	php private/bin/d2.php dbal:import private/data/install/wedcms.minimal.sql
