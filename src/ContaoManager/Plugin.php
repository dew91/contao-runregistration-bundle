<?php

declare(strict_types=1);

/*
 * Contao Manager plugin for Runregistration bundle.
 *
 * (c) 2021 Dominic Ernst
 *
 * @license GPL 3.0
 */

namespace Dew91\ContaoRunregistrationBundle\ContaoManager;

use Dew91\ContaoRunregistrationBundle\ContaoRunregistrationBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoRunregistrationBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
?>
