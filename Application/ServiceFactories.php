<?php

namespace Neuron;

use Doctrine;
use Nella\Panels\Doctrine2Panel;
use Nella\NetteDoctrineCache;
use Nette\Environment;
use Nette\Security\Permission;
use Neuron\Model\Symfony\ValidatorCache;
use Neuron\Model\Doctrine\CacheSubscriber;
use Neuron\Model\Doctrine\ValidationSubscriber;
use Symfony\Component\Validator;

/**
 * Default service factories
 *
 * @author Jan Marek
 */
class ServiceFactories
{
	public static function createValidator()
	{
		$loader = new Validator\Mapping\Loader\AnnotationLoader;
		$cache = Environment::isProduction() ? new ValidatorCache(Environment::getCache("SymfonyValidator")) : null;
		$metadataFactory = new Validator\Mapping\ClassMetadataFactory($loader, $cache);
		$validatorFactory = new Validator\ConstraintValidatorFactory;
		$validator = new Validator\Validator($metadataFactory, $validatorFactory);

		return $validator;
	}


	
	public static function createEntityManager($database)
	{
		$config = new Doctrine\ORM\Configuration;

		// annotations
		$annotationDriver = $config->newDefaultAnnotationDriver(array(
			APP_DIR . '/Model',
			NEURON_DIR . '/Model'
		));
		$config->setMetadataDriverImpl($annotationDriver);

		// proxy
		$config->setProxyNamespace('Neuron\Proxy');
		$config->setProxyDir(TEMP_DIR);

		// cache
		$cache = Environment::isProduction() ? new NetteDoctrineCache : new Doctrine\Common\Cache\ArrayCache;
		$config->setMetadataCacheImpl($cache);
		$config->setQueryCacheImpl($cache);

		// debugbar
		$config->setSQLLogger(Doctrine2Panel::getAndRegister());

		// entity manager
		$em = Doctrine\ORM\EntityManager::create((array) $database, $config);
		$evm = $em->getEventManager();
		$evm->addEventSubscriber(new Doctrine\DBAL\Event\Listeners\MysqlSessionInit("utf8", "utf8_czech_ci"));
		$evm->addEventSubscriber(new ValidationSubscriber);
		$evm->addEventSubscriber(new CacheSubscriber);

		return $em;
	}



	public static function createAuthorizator()
	{
		$perm = new Permission;
		$perm->addRole("guest");
		$perm->addRole("user", "guest");
		$perm->addRole("admin", "user");
		$perm->deny();
		$perm->allow("admin");
		return $perm;
	}

}