# Sample Zend Modular Application

- Zend Framework 1.11.9 + Doctrine 2.1 integration
This is a sample application to allow you use Doctrine 2 at the top of Zend Framework 1.

The following Libraries and extensions have been used:

 +  Zend 1.11.9
 +  ZF1 Classmap Autoloaders
 +  Doctrine 2.1
 +  Gedmo Doctrine Extensions
 
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

# Configuring
Add libraries as submodules, next step is to autoload things.
git submodule add git://github.com/jameshelly/ZendFrameWork1.git private/library

# Adding other Libraries
I also grab Doctrine & Gedmo repositories.

---- Repositories

	git clone git://github.com/doctrine/doctrine2.git 
	git clone git://github.com/l3pp4rd/DoctrineExtensions.git

---- Alias folders to Library folder.

	ln -s doctrine2/lib/Doctrine/ORM  /sitelocation/private/library/Doctrine/ORM
	ln -s doctrine2/lib/vendor/doctrine-common/lib/Doctrine/Common  /sitelocation/private/library/Doctrine/Common
	ln -s doctrine2/lib/vendor/doctrine-dbal/lib/Doctrine/DBAL  /sitelocation/private/library/Doctrine/DBAL
	ln -s doctrine2/lib/vendor/Symfony  /sitelocation/private/library/Doctrine/Symfony
	ln -s DoctrineExtensions/lib/Gedmo  /sitelocation/private/library/Gedmo

# Configuring Classmaps
private/bin/$ php classmap_generator.php -l ../application/
private/bin/$ php classmap_generator.php -l ../library/

#### Configuring Doctrine
There is a d2.default.php example, you should copy the file and rename it d2.php set it up for a connection.

---- Code Example

	private/bin/$ php d2.php orm:validate
	private/bin/$ php d2.php orm:schema-tool:create
	private/bin/$ php d2.php orm:generate-proxies

---- Configuration Example

	resources.doctrine.charset = UTF8
	resources.doctrine.compiled = false
	resources.doctrine.orm.manager.connection     							= default
	resources.doctrine.orm.manager.proxy.autoGenerateClasses 				= false
	resources.doctrine.orm.manager.proxy.namespace           				= "Application\Entities\Proxy"
	resources.doctrine.orm.manager.proxy.dir                 				= APPLICATION_PATH "/../data/generated"
	resources.doctrine.orm.manager.metadataDrivers.0.adapterClass          	= "Doctrine\ORM\Mapping\Driver\AnnotationDriver"
	resources.doctrine.orm.manager.metadataDrivers.0.annotationReaderClass 	= "Doctrine\Common\Annotations\AnnotationReader"
	resources.doctrine.orm.manager.metadataDrivers.0.annotationReaderCache 	= default

	resources.doctrine.dbal.default.driver = pdo_mysql
	resources.doctrine.dbal.default.unix_socket = '/etc/mysql.sock'
	resources.doctrine.dbal.default.host = localhost
	resources.doctrine.dbal.default.port = 3306
	resources.doctrine.dbal.default.user = root
	resources.doctrine.dbal.default.password = 
	resources.doctrine.dbal.default.dbname = zf11d2_alpha
	resources.doctrine.cacheClass = "Doctrine\Common\Cache\ArrayCache"

### Using
Its a pretty raw application, with a default & blog modules.

### Useful commandline functions.
	php private/bin/d2.php orm:validate
	php private/bin/d2.php orm:info
	php private/bin/d2.php orm:schema-tool:create
	php private/bin/d2.php orm:schema-tool:drop --force --full-database
	php private/bin/d2.php orm:schema-tool:create
	php private/bin/d2.php orm:generate-proxies
	php private/bin/d2.php dbal:import private/data/install/wedcms.minimal.sql
