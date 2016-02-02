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

/**
 * Class ImageTest
 * @package JBZoo\PHPUnit
 */
class ImageTest extends PHPUnit
{

    public function test()
    {
        $resultDir = PROJECT_TESTS . '/fixtures/result';
        $origDir   = PROJECT_TESTS . '/fixtures/original';

        $img = new Image();

        // Create from scratch
        $img->create(200, 100, '#08c')
            ->save($resultDir . '/create-from-scratch.png');

        // Convert to GIF
        $img->load($origDir . '/butterfly.jpg')
            ->save($resultDir . '/butterfly-convert-to-gif.gif');

        // Strip exif data (just load and save)
        $img->load($origDir . '/butterfly.jpg')
            ->save($resultDir . '/butterfly-strip-exif.jpg');

        // Flip horizontal
        $img->load($origDir . '/butterfly.jpg')
            ->flip('x')
            ->save($resultDir . '/butterfly-flip-horizontal.jpg');

        // Flip vertical
        $img->load($origDir . '/butterfly.jpg')
            ->flip('y')
            ->save($resultDir . '/butterfly-flip-vertical.jpg');

        // Flip both
        $img->load($origDir . '/butterfly.jpg')
            ->flip('x')
            ->flip('y')
            ->save($resultDir . '/butterfly-flip-both.jpg');

        // Rotate 90
        $img->load($origDir . '/butterfly.jpg')
            ->rotate(90)
            ->save($resultDir . '/butterfly-rotate-90.jpg');

        // Auto-orient
        $img->load($origDir . '/butterfly.jpg')
            ->auto_orient()
            ->save($resultDir . '/butterfly-auto-orient.jpg');

        // Resize
        $img->load($origDir . '/butterfly.jpg')
            ->resize(320, 239)
            ->save($resultDir . '/butterfly-resize.jpg');

        // Thumbnail
        $img->load($origDir . '/butterfly.jpg')
            ->thumbnail(100, 75)
            ->save($resultDir . '/butterfly-thumbnail.jpg');

        // Fit to width
        $img->load($origDir . '/butterfly.jpg')
            ->fit_to_width(100)
            ->save($resultDir . '/butterfly-fit-to-width.jpg');

        // Fit to height
        $img->load($origDir . '/butterfly.jpg')
            ->fit_to_height(100)
            ->save($resultDir . '/butterfly-fit-to-height.jpg');

        // Best fit
        $img->load($origDir . '/butterfly.jpg')
            ->best_fit(100, 400)
            ->save($resultDir . '/butterfly-best-fit.jpg');

        // Crop
        $img->load($origDir . '/butterfly.jpg')
            ->crop(160, 110, 460, 360)
            ->save($resultDir . '/butterfly-crop.jpg');

        // Desaturate
        $img->load($origDir . '/butterfly.jpg')
            ->desaturate()
            ->save($resultDir . '/butterfly-desaturate.jpg');

        // Invert
        $img->load($origDir . '/butterfly.jpg')
            ->invert()
            ->save($resultDir . '/butterfly-invert.jpg');

        // Brighten
        $img->load($origDir . '/butterfly.jpg')
            ->brightness(100)
            ->save($resultDir . '/butterfly-brighten.jpg');

        // Darken
        $img->load($origDir . '/butterfly.jpg')
            ->brightness(-100)
            ->save($resultDir . '/butterfly-darken.jpg');

        // Contrast
        $img->load($origDir . '/butterfly.jpg')
            ->contrast(-50)
            ->save($resultDir . '/butterfly-contrast.jpg');

        // Colorize
        $img->load($origDir . '/butterfly.jpg')
            ->colorize('#08c', .75)
            ->save($resultDir . '/butterfly-colorize.jpg');

        // Edge Detect
        $img->load($origDir . '/butterfly.jpg')
            ->edges()
            ->save($resultDir . '/butterfly-edges.jpg');

        // Mean Removal
        $img->load($origDir . '/butterfly.jpg')
            ->mean_remove()
            ->save($resultDir . '/butterfly-mean-remove.jpg');

        // Emboss
        $img->load($origDir . '/butterfly.jpg')
            ->emboss()
            ->save($resultDir . '/butterfly-emboss.jpg');

        // Selective Blur
        $img->load($origDir . '/butterfly.jpg')
            ->blur('selective', 10)
            ->save($resultDir . '/butterfly-blur-selective.jpg');

        // Gaussian Blur
        $img->load($origDir . '/butterfly.jpg')
            ->blur('gaussian', 10)
            ->save($resultDir . '/butterfly-blur-gaussian.jpg');

        // Sketch
        $img->load($origDir . '/butterfly.jpg')
            ->sketch()
            ->save($resultDir . '/butterfly-sketch.jpg');

        // Smooth
        $img->load($origDir . '/butterfly.jpg')
            ->smooth(6)
            ->save($resultDir . '/butterfly-smooth.jpg');

        // Pixelate
        $img->load($origDir . '/butterfly.jpg')
            ->pixelate(8)
            ->save($resultDir . '/butterfly-pixelate.jpg');

        // Sepia
        $img->load($origDir . '/butterfly.jpg')
            ->sepia(8)
            ->save($resultDir . '/butterfly-sepia.jpg');

        // Overlay
        $img->load($origDir . '/butterfly.jpg')
            ->overlay($origDir . '/overlay.png', 'bottom right', .8)
            ->save($resultDir . '/butterfly-overlay.jpg');

        // Change opacity
        $img->load($origDir . '/butterfly.jpg')
            ->opacity(.5)
            ->save($resultDir . '/butterfly-opacity.png');

        // Text
        $img->load($origDir . '/butterfly.jpg')
            ->text('Butterfly', $origDir . '/delicious.ttf', 32, '#FFFFFF', 'bottom', 0, -20)
            ->save($resultDir . '/butterfly-text.jpg');

        // Text with multiple colors
        $colors = ['#F00', '#FF7F00', '#FF0', '#0F0', '#0FF', '#00F'];
        $img->load($origDir . '/butterfly.jpg')
            ->text('Butterfly', $origDir . '/delicious.ttf', 32, $colors, 'bottom', 0, -20, null, null, null, 3)
            ->save($resultDir . '/butterfly-text-stroke-multi-colored-text.jpg');

        // Text with stroke
        $img->load($origDir . '/butterfly.jpg')
            ->text('Butterfly', $origDir . '/delicious.ttf', 32, '#FFFFFF', 'bottom', 0, -20, '#000', 2)
            ->save($resultDir . '/butterfly-text-with-stroke.jpg');

        // Text with multiple colored stroke
        $colors = ['#F00', '#FF7F00', '#FF0', '#0F0', '#0FF', '#00F'];
        $img->load($origDir . '/butterfly.jpg')
            ->text('Butterfly', $origDir . '/delicious.ttf', 32, '#000', 'bottom', 0, -20, $colors, 2, null, 3)
            ->save($resultDir . '/butterfly-text-with-stroke-multi-colored-stroke.jpg');

        // Right align text
        $img->load($origDir . '/butterfly.jpg')
            ->text('Lorem Ipsum', $origDir . '/delicious.ttf', 32, '#FFFFFF', 'top right', 0, 0, null, null, 'right')
            ->save($resultDir . '/butterfly-right-align-text.jpg');

        // Resizing GIFs with transparency
        $img->load($origDir . '/basketball.gif')
            ->resize(50, 50)
            ->save($resultDir . '/basketball-resize.gif');

        // Manipulate base64 gif string and save as png (requires PHP 5.4+)
        $base64 = 'data:image/gif;base64,R0lGODlhEAAQAOZeAHBwcKCgoOraIvDw8Mu9Hi4rBvPFJvTKJpyRF/bXJfPAJ/jeJU5IC0B'
            . 'AQOq0J+ewKPnmJffZJVc6FvPCJruuGz46CYyDFPXPJpCQkH10EqugGSYaC+GoKAAAALCwsNicKUlGQvbUJtGaKWBgYODg4LWB'
            . 'JDUnF/jhJa9+JXFgSl4/GUxHQjwpEdadKUxDOEM9NmNdVcaPJ9rLIPbRJtifKUEwGuSsKNmhKV1XDXdPG6p2JXhRG1U5FiMdF'
            . 'lpWU76DJ8DAwMuVKtDQ0GlFFrR8JEQtELF8KO24J3VNGT0zKG1lEIxgHdugKXBNGykbCzw3Mt6kKNOcKfXMJt6mKG5LG2JAFT'
            . 'gwJW5fSlNNRenSI/THJo5iIPC8J/rpJf///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'
            . 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAF4ALAAAAAAQABAAAAfZ'
            . 'gF6CXiQAIBsbSSNAg40YTlstDw8cMVUrA40jKjcKWiEhMwZHRCYkggE8UxMLXa6uCVw/T14DPVETWQwarhoWXRFcOwEBTVwJC'
            . 'B0VrhUdFF0HNC8AKAoQAhkErggMsA5FDSIHr+SvCwoS4QcUAuVdBBYR6AAlBhYZ5QIFBBcOLB45uJwogOAVAYIQJgSB4cXKhw'
            . 'MCcBRQwqCCNilcqATw4mEIhwtdBFCQ0QXCBS5GQAwCgISJgQgLFiQw4ECHi0yDAtRY8sHGAyglJPjA2WgAhgZXUmABIKRRIAA7';

        $img->load_base64($base64)
            ->resize(32, 32)
            ->save($resultDir . '/smiley-base64.png');

        isTrue(true);
    }
}