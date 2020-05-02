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


/**
 * Class Connection
 * @package Majframe\Database
 */
class Connection
{
    /**
     * @var string $host
     */
    private string $host;
    /**
     * @var string $user
     */
    private string $user;
    /**
     * @var string $password
     */
    private string $password;
    /**
     * @var string $dbname
     */
    private string $dbname;
    /**
     * @var string $prefix
     */
    public string $prefix;

    /**
     * Connection constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $dbname
     * @param string $prefix
     */
    public function __construct(string $host, string $user, string $password, string $dbname, string $prefix = '')
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->prefix = $prefix;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'driver' => 'pdo_mysql',
            'host' => $this->host,
            'user' => $this->user,
            'password' => $this->password,
            'dbname' => $this->dbname
        ];
    }
}