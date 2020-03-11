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

// EE v2 backward compatibility
// Ignored by EE v3+
$plugin_info = array(
    'pi_name'           => EASY_ICAL_NAME,
    'pi_version'        => EASY_ICAL_VERSION,
    'pi_author'         => EASY_ICAL_AUTHOR,
    'pi_author_url'     => EASY_ICAL_AUTHOR_URL,
    'pi_description'    => EASY_ICAL_DESC
);

class Easy_ical
{

    public $return_data = '';

    /**
     * Constructor
     *
     * @access  public
     * @return  string
    */
    public function __construct()
    {
        // default output
        $this->return_data = '' . EASY_ICAL_NAME . '<br>' . EASY_ICAL_DESC;
    }

    /**
     * CALENDAR CREATION
     *
     * @access  public
     * @return  string
    */
    public function calendar()
    {
        $this->return_data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\n";
        $this->return_data .= "PRODID:-//ExpressionEngine Easy iCalendar plugin//NONSGML v". EASY_ICAL_VERSION ."//EN\r\n";

        if (ee()->TMPL->fetch_param('timezone') !== FALSE) {
            $this->return_data .= "X-WR-TIMEZONE:".$this->escape(ee()->TMPL->fetch_param('timezone'))."\r\n";
        }

        if (ee()->TMPL->fetch_param('calendar_name') !== FALSE) {
            $this->return_data .= "X-WR-CALNAME:".$this->escape(ee()->TMPL->fetch_param('calendar_name'))."\r\n";
        }

        // trim away whitespace between each entry
        $tagdata = trim(ee()->TMPL->tagdata);
        $tagdata = preg_replace('/END\:VEVENT\s*BEGIN\:VEVENT/', "END:VEVENT\r\nBEGIN:VEVENT", $tagdata);
        if (!empty($tagdata)) $this->return_data .= $tagdata."\r\n";

        $this->return_data .= "END:VCALENDAR";

        // print output directly with the correct content-type
        $content_type   = ee()->TMPL->fetch_param('content_type');
        $filename       = $this->escape(ee()->TMPL->fetch_param('filename'));
        if (empty($content_type)) $content_type = 'text/calendar; charset=UTF-8';
        if (empty($filename)) $filename         = 'save-the-date';

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '.ics"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        // header('Content-Length: ' . filesize($file));
        exit($this->return_data);
    }

    /**
     * CALENDAR EVENT CREATION
     *
     * @access  public
     * @return  string
    */
    public function event()
    {
        $this->return_data = "BEGIN:VEVENT\r\n".
            "UID:".$this->escape(ee()->TMPL->fetch_param('uid'))."\r\n";

        if (ee()->TMPL->fetch_param('location') !== FALSE) {
            $this->return_data .= "LOCATION:".$this->escape(ee()->TMPL->fetch_param('location'))."\r\n";
        }

        $this->return_data .= "DTSTAMP:".$this->ical_time(ee()->TMPL->fetch_param('start_time'))."\r\n";
        $this->return_data .= "DTSTART:".$this->ical_time(ee()->TMPL->fetch_param('start_time'))."\r\n";

        if (ee()->TMPL->fetch_param('end_time') != FALSE) {
            $this->return_data .= "DTEND:".$this->ical_time(ee()->TMPL->fetch_param('end_time'))."\r\n";
        } else {
            $this->return_data .= "DTEND:".$this->ical_time(strtotime(ee()->TMPL->fetch_param('start_time')) + 3600)."\r\n";
        }

        if (ee()->TMPL->fetch_param('summary') !== FALSE) {
            $this->return_data .= "SUMMARY:".$this->escape(ee()->TMPL->fetch_param('summary'))."\r\n";
        }

        if (ee()->TMPL->fetch_param('sequence') !== FALSE) {
            $this->return_data .= "SEQUENCE:".$this->escape(ee()->TMPL->fetch_param('sequence'))."\r\n";
        }
        if (ee()->TMPL->fetch_param('url') !== FALSE) {
            $this->return_data .= "URL:".$this->escape(ee()->TMPL->fetch_param('url'))."\r\n";
        }

        $description = trim(ee()->TMPL->tagdata);
        if (!empty($description)) {
            $this->return_data .= "DESCRIPTION:".$this->escape($description)."\r\n";
        }

        $this->return_data .= "END:VEVENT"."\r\n";

        return $this->return_data;
    }

    /**
     * HELPER: STRING ESCAPING/CLEANING
     *
     * @access  public
     * @return  string
    */
    public function escape($str)
    {
        // strip any html tags
        $str = preg_replace('/\<p\>/i', "\n\n", $str);
        $str = preg_replace('/\<br\s*\/?\>/i', "\n", $str);
        $str = strip_tags($str);
        $str = trim(html_entity_decode($str, ENT_QUOTES, 'UTF-8'));

        // no more than two newlines please
        $str = preg_replace("/(\r?\n){3,}/", "\n\n", $str);

        // lines can't be more than 75 chars, use 60 to be safe
        $lines = str_split($str, 60);

        foreach ($lines as $key => $line) {
            // escape special icalendar chars and convert newlines to '\n'
            $lines[$key] = str_replace(array('\\', ',', ';'), array('\\\\', '\,', '\;'), $lines[$key]);
            $lines[$key] = preg_replace("/\r?\n/", '\n', $lines[$key]);
        }

        return implode("\r\n ", $lines);
    }

    /**
     * HELPER: DATE FORMATTING
     *
     * @access  public
     * @return  string
    */
    public function ical_time($time)
    {
        return ee()->localize->format_date('%Y%m%dT%H%i%s', $time);
    }

}

/* End of file pi.easy_ical.php */
/* Location: /system/expressionengine/third_party/easy_ical/pi.easy_ical.php */