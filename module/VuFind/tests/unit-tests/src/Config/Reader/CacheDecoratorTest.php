<?php

/**
 * Config CacheDecorator test class file.
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2010.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind2
 * @package  Tests
 * @author   David Maus <maus@hab.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/unit_tests Wiki
 */

namespace VuFindTest\Config\Reader;

use VuFind\Config\Reader\CacheDecorator;

/**
 * Config CacheDecorator test class.
 *
 * @category VuFind2
 * @package  Tests
 * @author   David Maus <maus@hab.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/unit_tests Wiki
 */
class CacheDecoratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Read config from while, new file.
     *
     * @return void
     */
    public function testFromFile ()
    {
        $cache = $this->getMockForAbstractClass('Zend\Cache\Storage\StorageInterface', array('setItem', 'hasItem'));
        $cache->expects($this->once())
            ->method('setItem');
        $cache->expects($this->once())
            ->method('hasItem')
            ->will($this->returnValue(false));
        $reader = $this->getMockForAbstractClass('Zend\Config\Reader\ReaderInterface', array('fromFile'));
        $reader->expects($this->once())
            ->method('fromFile')
            ->will($this->returnValue(array()));
        $deco = new CacheDecorator($reader, $cache);
        $deco->fromFile('ignore');
    }

    /**
     * Read config from while, cached file.
     *
     * @return void
     */
    public function testFromFileCached ()
    {
        $cache = $this->getMockForAbstractClass('Zend\Cache\Storage\StorageInterface', array('setItem', 'hasItem', 'getItem'));
        $cache->expects($this->never())
            ->method('setItem');
        $cache->expects($this->once())
            ->method('hasItem')
            ->will($this->returnValue(true));
        $cache->expects($this->once())
            ->method('getItem')
            ->will($this->returnValue(array()));
        $reader = $this->getMockForAbstractClass('Zend\Config\Reader\ReaderInterface', array('fromFile'));
        $deco = new CacheDecorator($reader, $cache);
        $deco->fromFile('ignore');
    }
}