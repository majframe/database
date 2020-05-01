<?php
/**
 * 2020-2020 Majframe
 *
 *  NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @copyright 2020-2020 Majframe
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License (GPL 3.0)
 */

namespace Majframe\Database;


use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;


/**
 * Class EntityManagerFactory
 * @package Majframe\Database
 */
class EntityManagerFactory
{
    /**
     * @var array $connection
     */
    private array $connection;
    /**
     * @var Configuration $configuration
     */
    private Configuration $configuration;

    /**
     * EntityManagerBuilder constructor.
     * @param array $database
     * @param bool $devMode
     * @param string $appDir
     * @param string $cacheDir
     */
    public function __construct(array $database, bool $devMode, string $appDir, string $cacheDir)
    {
        $this->initConnection($database);
        $this->initConfiguration($devMode, $appDir, $cacheDir);
    }

    /**
     * @param array $database
     */
    private function initConnection(array $database): void
    {
        $this->connection = [
            'driver' => 'pdo_mysql',
            'host' => $database['host'],
            'user' => $database['user'],
            'password' => $database['password'],
            'dbname' => $database['dbname'],
        ];
    }

    /**
     * @param bool $devMode
     * @param string $appDir
     * @param string $cacheDir
     */
    private function initConfiguration(bool $devMode, string $appDir, string $cacheDir): void
    {
        $ormCacheDir = $cacheDir.'/doctrine/orm';
        $cache = $devMode ? new ArrayCache : new PhpFileCache($ormCacheDir.'/Query');
        $this->configuration = new Configuration;
        $this->configuration->setMetadataDriverImpl($this->configuration->newDefaultAnnotationDriver($appDir.'/Entity', false));
        $this->configuration->setMetadataCacheImpl($cache);
        $this->configuration->setQueryCacheImpl($cache);
        $this->configuration->setProxyDir($ormCacheDir.'/Proxy');
        $this->configuration->setProxyNamespace('App\Proxy');
        $this->configuration->setAutoGenerateProxyClasses($devMode);
    }


    /**
     * @param string $prefix
     * @return EntityManager
     * @throws ORMException
     */
    public function create(string $prefix = ''): EntityManager
    {
        $evm = new EventManager;
        $tablePrefix = new TablePrefix($prefix);
        $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);
        return EntityManager::create($this->connection, $this->configuration, $evm);
    }
}