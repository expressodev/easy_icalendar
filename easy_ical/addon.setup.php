<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Easy iCalendar (forked)
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Matthew Kirkpatrick
 * @copyright   Copyright (c) 2020, Matthew Kirkpatrick
 * @link        https://github.com/javashakes
 */

// config
include(PATH_THIRD.'easy_ical/config.php');

return array(
    'name'              => EASY_ICAL_NAME,
    'version'           => EASY_ICAL_VERSION,
    'author'            => EASY_ICAL_AUTHOR,
    'author_url'        => EASY_ICAL_AUTHOR_URL,
    'docs_url'          => EASY_ICAL_DOCS,
    'description'       => EASY_ICAL_DESC,
    'namespace'         => EASY_ICAL_NAMESPACE,
    'settings_exist'    => FALSE
);

/* End of file addon.setup.php */
/* Location: /system/expressionengine/third_party/easy_ical/addon.setup.php */