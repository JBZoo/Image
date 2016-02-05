<?php
/**
 * JBZoo Image
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   Image
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/Image
 */

namespace JBZoo\PHPUnit;

use JBZoo\Image\Image;
use JBZoo\Utils\FS;

/**
 * Class ImageTest
 * @package JBZoo\PHPUnit
 */
class ImageTest extends PHPUnit
{
    protected $_class = '\JBZoo\Image\Image';

    public function testCreateInstance()
    {
        $img = new Image();
        isClass($this->_class, $img);
    }

    public function testOpen()
    {
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image($original);
        isClass($this->_class, $img->open($original));
    }

    /**
     * @expectedException \JBZoo\Image\Exception
     */
    public function testOpenUndefined()
    {
        $img = new Image();
        $img->open($this->_getOrig('undefined.jpg'));
    }

    public function testCleanup()
    {
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image($original);
        isClass($this->_class, $img->open($original));

        $img->cleanup();
        isCount(1, array_filter($img->getInfo()));
    }

    public function testGetInfoJpeg()
    {
        $original = $this->_getOrig('butterfly.jpg');

        $img  = new Image($original);
        $info = $img->getInfo();

        is(640, $info['width']);
        is(478, $info['height']);
        is('image/jpeg', $info['mime']);
        is('landscape', $info['orient']);
        isTrue(is_array($info['exif']));
        isTrue($img->isJpeg());
    }

    public function testGetInfoPng()
    {
        $original = $this->_getOrig('butterfly.png');

        $img  = new Image($original);
        $info = $img->getInfo();

        is(640, $info['width']);
        is(478, $info['height']);
        is('image/png', $info['mime']);
        is('landscape', $info['orient']);
        isEmpty($info['exif']);
        isTrue($img->isPng());
    }

    public function testGetInfoGif()
    {
        $original = $this->_getOrig('butterfly.gif');

        $img  = new Image($original);
        $info = $img->getInfo();

        is(478, $info['width']);
        is(640, $info['height']);
        is('image/gif', $info['mime']);
        is('portrait', $info['orient']);
        isEmpty($info['exif']);

        isTrue($img->isGif());
    }

    public function testOrientation()
    {
        $img = new Image($this->_getOrig('butterfly.gif'));
        isTrue($img->isPortrait());

        $img = new Image($this->_getOrig('butterfly.jpg'));
        isTrue($img->isLandscape());

        $img = new Image($this->_getOrig('basketball.gif'));
        isTrue($img->isSquare());
    }

    public function testSave()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        if (copy($original, $actual)) {
            $img  = new Image($actual);
            $info = $img->save(1)->getInfo();

            is(1, $info['quality']);
            is($actual, $info['filename']);
            //isNotEmpty($info['exif']);
            $this->_isFileEq($actual, $excepted);

        } else {
            isTrue(false, 'Can\'t copy original file!');
        }
    }

    public function testConvertToGif()
    {
        $original = $this->_getOrig('butterfly.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.gif');
        $excepted = $this->_getExpected(__FUNCTION__ . '.gif');

        $img = new Image();
        $img->open($original)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testConvertToJpg()
    {
        $original   = $this->_getOrig('butterfly.jpg');
        $excepted   = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actualJpg  = $this->_getActual(__FUNCTION__ . '.jpg');
        $actualJpeg = $this->_getActual(__FUNCTION__ . '.jpeg');

        $img = new Image();
        $img->open($original)->saveAs($actualJpg);
        $this->_isFileEq($actualJpg, $excepted);

        $img = new Image();
        $img->open($original)->saveAs($actualJpeg)->setQuality(100);
        $this->_isFileEq($actualJpeg, $excepted);
    }

    public function testConvertToPng()
    {
        $original = $this->_getOrig('butterfly.jpg');
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');

        $img = new Image();
        $img->open($original)->saveAs($actual);
        $this->_isFileEq($actual, $excepted);
    }

    /**
     * @expectedException \JBZoo\Image\Exception
     */
    public function testConvertToUndefindFormat()
    {
        $original = $this->_getOrig('butterfly.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.qwerty');

        $img = new Image();
        $img->open($original)
            ->saveAs($actual);
    }

    /**
     * @expectedException \JBZoo\Image\Exception
     */
    public function testConvertToUndefindPath()
    {
        $original = $this->_getOrig('butterfly.jpg');
        $actual   = $this->_getActual('qwerty/' . __FUNCTION__ . '.png');

        $img = new Image();
        $img->open($original)
            ->saveAs($actual);
    }

    public function testCreateFromScratchOnlyWidth()
    {
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');

        $img = new Image();
        $img->create(200)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testCreateFromScratchWidthAndHeight()
    {
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');

        $img = new Image();
        $img->create(200, 100)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testCreateFromScratchFull()
    {
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');

        $img = new Image();
        $img->create(200, 100, '#08c')->saveAs($actual);
        $this->_isFileEq($actual, $excepted);
    }

    public function testColorNormalization()
    {
        $img      = new Image();
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');

        $actual = $this->_getActual(__FUNCTION__ . '_sharp_0088cc.png');
        $img->create(200, 100, '#0088cc')->saveAs($actual);
        $this->_isFileEq($actual, $excepted);

        $actual = $this->_getActual(__FUNCTION__ . '_0088cc.png');
        $img->create(200, 100, '0088CC')->saveAs($actual);
        $this->_isFileEq($actual, $excepted);

        $actual = $this->_getActual(__FUNCTION__ . '_sharp_08c.png');
        $img->create(200, 100, '#08c')->saveAs($actual);
        $this->_isFileEq($actual, $excepted);

        $actual = $this->_getActual(__FUNCTION__ . '_08c.png');
        $img->create(200, 100, '08c')->saveAs($actual);
        $this->_isFileEq($actual, $excepted);

        $actual = $this->_getActual(__FUNCTION__ . '_array_08c.png');
        $img->create(200, 100, array('r' => 0, 'g' => '136', 'b' => '204'))->saveAs($actual);
        $this->_isFileEq($actual, $excepted);

        $actual = $this->_getActual(__FUNCTION__ . '_array_0_136_204.png');
        $img->create(200, 100, array(0, 136, 204))->saveAs($actual);
        $this->_isFileEq($actual, $excepted);

        $actual = $this->_getActual(__FUNCTION__ . '_array_no_format.png');
        $img->create(200, 100, array(null, '   136  ', '   204   ', 0))->saveAs($actual);
        $this->_isFileEq($actual, $excepted);
    }

    public function testResizeJpeg()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '_320_239.jpg');
        $original = $this->_getOrig('butterfly.png');

        $img = new Image($original);
        $img->resize(320, 239)->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testResizeGif()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.gif');
        $actual   = $this->_getActual(__FUNCTION__ . '_320_239.gif');
        $original = $this->_getOrig('butterfly.gif');

        $img = new Image($original);
        $img->resize(320, 239)->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testResizeTransparent()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.gif');
        $actual   = $this->_getActual(__FUNCTION__ . '.gif');
        $original = $this->_getOrig('1x1.gif');

        $img = new Image($original);
        $img->resize(50, 50)->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testCrop()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->crop(160, 110, 460, 360)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testCropWrongCoord()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->crop(460, 360, 160, 110)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFitToWidth()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->fitToWidth(100)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFitToHeight()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->fitToHeight(100)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testThumbnailHeight()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->thumbnail(100, 75)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testThumbnailWidth()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.gif');

        $img = new Image();
        $img->open($original)
            ->thumbnail(75)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFlipX()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->flip('x')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFlipY()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->flip('y')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFlipXY()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->flip('x')
            ->flip('y')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFlipRorate90()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->rotate(90)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFlipRorate45()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->rotate(45)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFlipRorateRevert275White()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->rotate(-275, [255, 255, 255, 127])
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testAutoOrient()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.jpg');
        $actual   = $this->_getActual(__FUNCTION__ . '.jpg');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->autoOrient()
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermark()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkTopLeft()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'top left')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkTopRight()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'top right')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkTop()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'top')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkBottomLeft()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'bottom left')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkBottomRight()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'bottom right')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkBottom()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'bottom')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkLeft()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'left')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkRight()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'right')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkCenter()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'center')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testWatermarkOpacity()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');
        $overlay  = $this->_getOrig('overlay.png');

        $img = new Image();
        $img->open($original)
            ->overlay($overlay, 'bottom', 200, 25, 25)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFilterSepia()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->addFilter('sepia')
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFilterPixelate()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->addFilter('pixelate', [25])
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testFilterCustom()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->addFilter(function ($image, $blockSize) {
                imagefilter($image, IMG_FILTER_PIXELATE, $blockSize, true);
            }, [2])
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testOpacity_05()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->opacity(.5)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testOpacity_50()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->opacity(50)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testOpacity_0()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->opacity(0)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }

    public function testOpacity_100()
    {
        $excepted = $this->_getExpected(__FUNCTION__ . '.png');
        $actual   = $this->_getActual(__FUNCTION__ . '.png');
        $original = $this->_getOrig('butterfly.jpg');

        $img = new Image();
        $img->open($original)
            ->opacity(150)
            ->saveAs($actual);

        $this->_isFileEq($actual, $excepted);
    }


    /**** Tools *******************************************************************************************************/


    /**
     * @param $filename
     * @return string
     */
    protected function _getActual($filename)
    {
        $filename = $this->camelCase2Human($filename);
        return FS::clean(PROJECT_ROOT . '/build/' . $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    protected function _getExpected($filename)
    {
        $filename = $this->camelCase2Human($filename);
        return FS::clean(PROJECT_TESTS . '/expected/' . $filename);
    }

    /**
     * @param $filename
     * @return string
     */
    protected function _getOrig($filename)
    {
        return FS::clean(PROJECT_TESTS . '/resources/' . $filename);
    }

    /**
     * @param string $actual
     * @param string $expected
     */
    protected function _isFileEq($actual, $expected)
    {
        $isExistsAct = file_exists($actual);
        $isExistsExp = file_exists($expected);

        isTrue($isExistsExp, 'File not found: ' . $expected);

        if ($isExistsAct && $isExistsExp) {
            $diff = filesize($actual) - filesize($expected);

            if ($diff !== 0) {
                cliMessage(FS::base($actual) . ' = ' . $diff);
            } else {
                is(0, $diff);
            }
        }
    }

    /**
     * @param string $input
     * @return mixed|string
     */
    protected function camelCase2Human($input)
    {
        $original = $input;

        if (strpos($input, '\\') !== false) {
            $input = explode('\\', $input);
            reset($input);
            $input = end($input);
        }

        $input = preg_replace('#^(test)#i', '', $input);
        $input = preg_replace('#(test)$#i', '', $input);

        $output = preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), '_$0', $input);
        $output = preg_replace('#_{1,}#', '_', $output);

        $output = trim($output);
        $output = strtolower($output);

        if (strlen($output) == 0) {
            return $original;
        }

        return $output;
    }

}
