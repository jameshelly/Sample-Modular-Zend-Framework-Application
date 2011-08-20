# Sample Zend Modular Application

## Zend Framework 1.11.9 + Doctrine 2.1 integration

This is a sample application to allow you use Doctrine 2 at the top of Zend Framework 1.

The following Libraries and extensions have been used:
 - Zend 1.11.9
 - ZF1 Classmap Autoloaders
 - Doctrine 2.1
 - Gedmo Doctrine Extensions

### Installing

#### Zend 1.11.9
git://github.com/Enrise/Zend.git
http://devzone.zend.com/article/16343-Zend-Framework-1.11.9-Released
http://framework.zend.com/download/latest

#### Classmap Autoloader
git://github.com/weierophinney/zf-examples.git
http://weierophinney.net/matthew/archives/262-Backported-ZF2-Autoloaders.html
https://github.com/weierophinney/zf-examples/blob/feature%2Fzf1-classmap/zf1-classmap/README.md

#### Doctrine 2.1
git://github.com/doctrine/doctrine2.git
http://www.doctrine-project.org/
http://www.doctrine-project.org/docs/orm/2.1/en/reference/introduction.html

### Configuring

#### Configuring Classmaps
private/bin/$ php classmap_generator.php -l ../application/
private/bin/$ php classmap_generator.php -l ../library/

#### Configuring Doctrine
private/bin/$ php d2.php orm:validate
private/bin/$ php d2.php orm:schema-tool:create
private/bin/$ php d2.php orm:generate-proxies

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
