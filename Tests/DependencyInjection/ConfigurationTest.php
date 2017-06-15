<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Configuration;
use Sonatra\Component\Mailer\Model\LayoutInterface;
use Sonatra\Component\Mailer\Model\MailInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * Tests for symfony extension configuration.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigurationTest extends TestCase
{
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(array()));

        $this->assertEquals(array_merge(array(), self::getBundleDefaultConfig()), $config);
    }

    public function testFilterConfig()
    {
        $valid = array(
            'filters' => array(
                'templates' => array(
                    'css_to_styles' => array(),
                ),
                'transports' => array(),
            ),
        );
        $config = array(
            'filters' => array(
                'templates' => array(
                    'css_to_styles' => array(),
                ),
            ),
        );

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array($config));

        $this->assertEquals(array_merge(self::getBundleDefaultConfig(), $valid), $config);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The sonatra_mailer.filters.templates config 42 must be either null or an array.
     */
    public function testInvalidFilterConfig()
    {
        $config = array(
            'filters' => array(
                'templates' => array(
                    'css_to_styles' => 42,
                ),
            ),
        );

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), array($config));
    }

    /**
     * @return array
     */
    protected static function getBundleDefaultConfig()
    {
        return array(
            'layout_class' => LayoutInterface::class,
            'mail_class' => MailInterface::class,
            'layout_templates' => array(),
            'mail_templates' => array(),
            'filters' => array(
                'templates' => array(),
                'transports' => array(),
            ),
            'transports' => array(
                'swiftmailer' => array(
                    'embed_image' => array(
                        'enabled' => false,
                        'host_pattern' => '/(.*)+/',
                    ),
                    'dkim_signer' => array(
                        'enabled' => false,
                        'private_key_path' => null,
                        'domain' => null,
                        'selector' => null,
                    ),
                ),
            ),
        );
    }
}
