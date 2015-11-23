<?php

/**
 *
 * @see XenResource_Model_Resource
 */
class ThemeHouse_ResourceIcons_Extend_XenResource_Model_Resource extends XFCP_ThemeHouse_ResourceIcons_Extend_XenResource_Model_Resource
{

    public function __construct()
    {
        self::$iconSize = max(
            array(
                'xenforo' => self::$iconSize,
                'optionWidth' => XenForo_Application::get('options')->th_resourceIcons_width,
                'optionHeight' => XenForo_Application::get('options')->th_resourceIcons_height
            ));

        parent::__construct();
    }

    /**
     *
     * @see XenResource_Model_Resource::applyResourceIcon()
     *
     */
    public function applyResourceIcon($resourceId, $fileName, $imageType = false, $width = false, $height = false)
    {
        if (!$imageType || !$width || !$height) {
            $imageInfo = getimagesize($fileName);
            if (!$imageInfo) {
                return parent::applyResourceIcon($resourceId, $fileName, $imageType, $width, $height);
            }
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $imageType = $imageInfo[2];
        }

        if (!in_array($imageType,
            array(
                IMAGETYPE_GIF,
                IMAGETYPE_JPEG,
                IMAGETYPE_PNG
            ))) {
            return parent::applyResourceIcon($resourceId, $fileName, $imageType, $width, $height);
        }

        if (!XenForo_Image_Abstract::canResize($width, $height)) {
            return parent::applyResourceIcon($resourceId, $fileName, $imageType, $width, $height);
        }

        $imageQuality = self::$iconQuality;
        $outputType = $imageType;

        $image = XenForo_Image_Abstract::createFromFile($fileName, $imageType);
        if (!$image) {
            return false;
        }

        $maxDimensions = max(array(
            $image->getWidth(),
            $image->getHeight()
        ));

        if ($image->getOrientation() != XenForo_Image_Abstract::ORIENTATION_SQUARE) {
            $x = floor(($maxDimensions - $image->getWidth()) / 2);
            $y = floor(($maxDimensions - $image->getHeight()) / 2);
            $image->resizeCanvas($x, $y, $maxDimensions, $maxDimensions);
        }

        $newTempFile = tempnam(XenForo_Helper_File::getTempDir(), 'xf');
        if (!$newTempFile) {
            return false;
        }

        $image->output($outputType, $newTempFile, $imageQuality);
        unset($image);

        $returnValue = parent::applyResourceIcon($resourceId, $newTempFile, $imageType, $width, $height);

        if ($returnValue) {
            $filePath = $this->getResourceIconFilePath($resourceId);

            $image = XenForo_Image_Abstract::createFromFile($filePath, $imageType);
            if (!$image) {
                return false;
            }

            $width = XenForo_Application::get('options')->th_resourceIcons_width;
            $height = XenForo_Application::get('options')->th_resourceIcons_height;

            $x = floor(($width - $image->getWidth()) / 2);
            $y = floor(($height - $image->getHeight()) / 2);
            $image->resizeCanvas($x, $y, $width, $height);

            $image->output($outputType, $filePath, $imageQuality);
            unset($image);
        }
    }
}