<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=80; */

/**
 * @package     omeka
 * @subpackage  fedora-connector
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


class Image_Renderer extends FedoraConnector_AbstractRenderer
{


    private static $_excludedFormats = array(
        'jp2', 'x-mrsid-image'
    );


    /**
     * Check of the renderer can handle a given mime type.
     *
     * @param string $mimeType The mimeType.
     * @return boolean True if this can display the datastream.
     */
    function canDisplay($mimeType) {
        $exclude = implode('|', self::$_excludedFormats);
        return (bool)(preg_match('/^image\/(?!'.$exclude.')/', $mimeType));
    }


    /**
     * Display an object.
     *
     * @param Omeka_Record $object The Fedora object record.
     * @return string The display HTML for the datastream.
     */
    function display($object, $params = array()) {

        // Construct the image URL.
        $url = "{$object->getServer()->url}/objects/{$object->pid}" .
            "/datastreams/" . $params['dsid'] . "/content";

        // Check params to see how big the image should be; default to svc.level 3
        
        if(!array_key_exists('size', $params)) {
            $params['size'] = 3;
        }

        $serviceLevel = NULL;
        $crop = NULL;
        
        switch ($params['size']) {
            case "fullsize":
                $serviceLevel = 3;
                break;
            case "square_thumbnail":
                $serviceLevel = 2;
                $crop = "0,0,225,225"
                break;
            case "thumbnail":
                $serviceLevel = 1;
                break;
            default:
                $serviceLevel = 3;
        }
        
        // Wrap it in the URL for Djatoka
        
        $scaled = "http://digital.grinnell.edu:8080/adore-djatoka/resolver?url_ver=Z39.88-2004&rft_id=" 
            . $url . "&svc_id=info:lanl-repo/svc/getRegion&svc_val_fmt=info:ofi/fmt:kev:mtx:jpeg2000&svc.format=image/png&svc.level="
            . $serviceLevel . "&svc.rotate=0";
            
        if ($crop != NULL) {
            $scaled .= "&svc.region=" . $crop;
        }

        // Return the image tag.
        return "<img class='fedora-renderer' alt='image' src='{$scaled}' />";

    }


}
