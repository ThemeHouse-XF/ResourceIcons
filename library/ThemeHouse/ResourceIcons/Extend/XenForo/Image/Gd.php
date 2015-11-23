<?php

/**
 *
 * @see XenForo_Image_Gd
 */
class ThemeHouse_ResourceIcons_Extend_XenForo_Image_Gd extends XFCP_ThemeHouse_ResourceIcons_Extend_XenForo_Image_Gd
{

    /**
     * Resizes the canvas of the image.
     */
    public function resizeCanvas($x, $y, $width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);
        $this->_preallocateBackground($newImage);

        imagecopyresampled($newImage, $this->_image, $x, $y, 0, 0, $this->getWidth(), $this->getHeight(),
            $this->getWidth(), $this->getHeight());
        $this->_setImage($newImage);
    }
}