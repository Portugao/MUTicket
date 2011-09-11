<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.2 (http://modulestudio.de) at Sun Sep 11 16:08:57 CEST 2011.
 */

use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;

/**
 * Utility base class for image helper methods.
 */
class MUTicket_Util_Base_Image extends Zikula_AbstractBase
{
    /**
     * This method is used by the muticketImageThumb modifier
     * as well as the Ajax controller of this application.
     *
     * It serves for creating and displaying a thumbnail image.
     *
     * @param  string    $filePath   The input file path (including file name).
     * @param  int       $width      Desired width.
     * @param  int       $height     Desired height.
     * @param  array     $thumbArgs  Additional arguments.
     *
     * @return string The thumbnail file path.
     */
    public static function getThumb($filePath = '', $width = 100, $height = 80, $thumbArgs = array())
    {
        if (empty($filePath) || !file_exists($filePath)) {
            return;
        }
        if (!is_array($thumbArgs)) {
            $thumbArgs = array();
        }

        // compute thumbnail file path using a sub folder
        $pathInfo = pathinfo($filePath);
        $thumbFilePath = $pathInfo['dirname'] . '/tmb/' . $pathInfo['filename'] . '_' . $width . 'x' . $height . '.' . $pathInfo['extension'];

        // return thumbnail file path if it is already existing
        if (file_exists($thumbFilePath)) {
            return $thumbFilePath;
        }

        // use Imagine library for creating the thumbnail image
        // documentation can be found at https://github.com/avalanche123/Imagine/tree/master/docs/en
        try {
            // create instance of Imagine
            $imagine = new Imagine\Gd\Imagine();
            // alternative
            // $imagine = new Imagine\Imagick\Imagine();

            // scale down thumbnails per default
            $thumbMode = Imagine\ImageInterface::THUMBNAIL_INSET;
            if (isset($thumbArgs['thumbMode']) && $thumbArgs['thumbMode'] == Imagine\ImageInterface::THUMBNAIL_OUTBOUND) {
                // cut out thumbnail
                $thumbMode = Imagine\ImageInterface::THUMBNAIL_OUTBOUND;
            }

            // define target dimension
            $thumbSize = new Box($width, $height);
            // $thumbSize->increase(25); // add 25 pixels to x and y values
            // $thumbSize->scale(2); // double x and y values

            // open image to be processed
            $image = $imagine->open($filePath);
            // remember the image size
            $originalSize = $image->getSize();

            $thumb = $image->thumbnail($thumbSize, $thumbMode);

            /**
             * You can do many other image manipulations here as well:
             *    resize, rotate, crop, save, copy, paste, apply mask and many more
             * It would even be possible to visualise the image histogram.
             * See https://github.com/avalanche123/Imagine/blob/master/docs/en/image.rst
             *
             * Small example from manual:
             *
             *     $bgColour = new Color('fff', 30).darken(40);
             *     $thumb = $image->resize(new Box(15, 25))
             *         ->rotate(45, $bgColour)
             *         ->crop(new Point(0, 0), new Box(45, 45));
             */

            /**
             * Create a new image with fully-transparent black background:
             *     $bgColour = new Color('000', 100);
             *     $thumb = $imagine->create($thumbSize, $bgColour);
             * Create a new image with a vertical gradient background:
             *     $thumb = $imagine->create($thumbSize)
             *         ->fill(
             *             new Imagine\Fill\Gradient\Vertical(
             *                 $size->getHeight(),
             *                 new Color(array(127, 127, 127)),
             *                 new Color('fff')
             *             )
             *         );
             */

            /**
             * If you want to do drawings with elements like ellipse, chord or polygon
             * see https://github.com/avalanche123/Imagine/blob/master/docs/en/drawing.rst
             *
             *     $centerPoint = new Point($thumbSize->getWidth()/2, $thumbSize->getHeight()/2);
             */

            /**
             * For font usage use
             * $font = $imagine->font($file, $size, $colour);
             */

            // save thumb file
            $saveOptions = array();
            if (in_array($pathInfo['extension'], array('jpg', 'jpeg', 'png'))) {
                $saveOptions['quality'] = 85;
            }
            $thumb->save($thumbFilePath);

            // return path to created thumbnail image
            return $thumbFilePath;

        } catch (Imagine\Exception\Exception $e) {
            $dom = ZLanguage::getModuleDomain('MUTicket');
            // log this exception
            LogUtil::registerError(__f('An error occured during thumbnail creation: %s', array($e->getMessage()), $dom));
            // return the original image as fallback
            return $filePath;
        }
    }
}
