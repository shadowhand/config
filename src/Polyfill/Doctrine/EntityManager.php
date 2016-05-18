<?php

namespace Sinergi\Config\Polyfill\Doctrine;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\DefaultQuoteStrategy;
use Doctrine\ORM\Tools\Setup;
use Interop\Config\ConfigurationTrait;
use Interop\Config\RequiresMandatoryOptions;
use Interop\Config\RequiresConfigId;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

class EntityManager implements RequiresMandatoryOptions, RequiresConfigId
{
    use ConfigurationTrait;

    public function __invoke(ContainerInterface $container)
    {
        $options = $this->options($container->get('config'));
        $driverClass = $options['driverClass'];
        $metadataDriverClass = $options['metadataDriverClass'];
        $cacheDriverClass = isset($options['cacheDriverClass']) ? $options['cacheDriverClass'] : null;
        $sqlLoggerDriverClass = $options['sqlLoggerDriverClass'];

        $doctrineConfig = Setup::createConfiguration($options['is_dev_mode']);

        $doctrineConfig->setMetadataDriverImpl(
            new $metadataDriverClass(new AnnotationReader(), $options['paths'])
        );

        $doctrineConfig->setQuoteStrategy(new DefaultQuoteStrategy());

        if ($cacheDriverClass) {
            $cache = new $cacheDriverClass;
            $doctrineConfig->setQueryCacheImpl($cache);
            $doctrineConfig->setMetadataCacheImpl($cache);
            $doctrineConfig->setHydrationCacheImpl($cache);
            $doctrineConfig->setResultCacheImpl($cache);
        }

        if (isset($options['proxy_dir'])) {
            $doctrineConfig->setProxyDir($options['proxy_dir']);
            if (isset($connectionConfig['proxy_namespace'])) {
                $doctrineConfig->setProxyNamespace($options['proxy_namespace']);
            } else {
                $doctrineConfig->setProxyNamespace('Proxies');
            }
        }

        if (!isset($options['params']['charset'])) {
            $options['params']['charset'] = 'utf8';
        }

        if (!isset($options['params']['driver'])) {
            $options['params']['driver'] = (new $driverClass())->getName();
        }

        $entityManager = DoctrineEntityManager::create($options['params'], $doctrineConfig);

        $connection = $entityManager->getConnection();

        if ($driverClass === PDOMySqlDriver::class) {
            $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        }

        if (isset($sqlLoggerDriverClass)) {
            $connection->getConfiguration()->setSQLLogger(new $sqlLoggerDriverClass);
        }

        return $entityManager;
    }

    /**
     * @return string
     */
    public function vendorName()
    {
        return 'doctrine';
    }

    /**
     * @return string
     */
    public function packageName()
    {
        return 'connection';
    }

    /**
     * @return string
     */
    public function containerId()
    {
        return 'default';
    }

    /**
     * @return string[] List with mandatory options
     */
    public function mandatoryOptions()
    {
        return [
            'driverClass',
            'params',
        ];
    }

    /**
     * @return string[] List with optional options
     */
    public function optionalOptions()
    {
        return [
            'metadataDriverClass',
            'is_dev_mode',
            'proxy_dir',
            'proxy_namespace',
            'paths',
            'cacheDriverClass',
            'sqlLoggerDriverClass',
        ];
    }

    public function dimensions() {
        return [];
    }
}
