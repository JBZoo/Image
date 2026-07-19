<?php

/**
 * JBZoo Toolbox - Image.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Image
 */

declare(strict_types=1);

if (!\defined('ROOT_PATH')) { // for PHPUnit process isolation
    \define('ROOT_PATH', \dirname(__DIR__));
}

// main autoload
if ($autoload = \realpath(ROOT_PATH . '/vendor/autoload.php')) {
    require_once $autoload;
} else {
    echo 'Please execute "composer update" !' . \PHP_EOL;
    exit(1);
}

// Tests write generated images to ./build/images. The Makefile "update" target also creates it,
// but running PHPUnit directly (as the hermetic gate does) must not depend on that side effect.
$buildImagesDir = ROOT_PATH . '/build/images';
if (!\is_dir($buildImagesDir)) {
    \mkdir($buildImagesDir, 0777, true);
}
