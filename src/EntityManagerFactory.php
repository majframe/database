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


use DI\Annotation\Inject;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;


/**
 * Class EntityManagerFactory
 * @package Majframe\Database
 */
class EntityManagerFactory
{
    /**
     * @var Configuration $configuration
     */
    private Configuration $configuration;

    /**
     * EntityManagerBuilder constructor.
     * @param array $application
     * @Inject({"application"})
     */
    public function __construct(array $application)
    {
        $ormCacheDir = $application['cacheDir'] . '/doctrine/orm';
        $cache = $application['devMode'] ? new ArrayCache : new PhpFileCache($ormCacheDir . '/Query');
        $this->configuration = Setup::createAnnotationMetadataConfiguration(
            [$application['appDir'] . '/Entity'], $application['devMode'], null, $cache, false
        );
    }


    /**
     * @param array $connection
     * @return EntityManager
     * @throws ORMException
     */
    public function create(array $connection): EntityManager
    {
        $evm = new EventManager;
        $tablePrefix = new TablePrefix($connection['prefix']);
        $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);
        return EntityManager::create($connection, $this->configuration, $evm);
    }
}