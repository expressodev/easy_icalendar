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
     * CALENDAR OBJECT
     *
     * @access  public
     * @return  string
    */
    public function calendar()
    {
        // parameters
        $timezone      = $this->escape(ee()->TMPL->fetch_param('timezone', 'America/New_York'));
        $calendar_name = $this->escape(ee()->TMPL->fetch_param('calendar_name', 'Save the Date!'));
        $content_type  = ee()->TMPL->fetch_param('content_type', 'text/calendar; charset=UTF-8');
        $filename      = $this->escape(ee()->TMPL->fetch_param('filename', 'save-the-date'));

        // capture event tag adn trim away whitespace
        $tagdata       = trim(ee()->TMPL->tagdata);
        $tagdata       = preg_replace('/END\:VEVENT\s*BEGIN\:VEVENT/', "END:VEVENT\r\nBEGIN:VEVENT", $tagdata);

        // build the calendar object
        $this->return_data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\n" .
                             "PRODID:-//ExpressionEngine " . EASY_ICAL_NAME . " plugin//NONSGML v" . EASY_ICAL_VERSION . "//EN\r\n" .
                             "X-WR-TIMEZONE:" . $timezone . "\r\n" .
                             "X-WR-CALNAME:" . $calendar_name . "\r\n" .
                             ( (!empty($tagdata)) ? $tagdata . "\r\n" : '' ) .
                             "END:VCALENDAR";

        // output headers
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '.ics"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // output file content
        exit($this->return_data);
    }

    /**
     * EVENT OBJECT
     *
     * @access  public
     * @return  string
    */
    public function event()
    {
        // parameters
        $uid         = $this->escape(ee()->TMPL->fetch_param('uid', ee()->localize->now));
        $start_time  = $this->ical_time(ee()->TMPL->fetch_param('start_time', $uid));
        $end_time    = $this->ical_time(ee()->TMPL->fetch_param('end_time', $start_time+(60*60*24)));
        $summary     = $this->escape(ee()->TMPL->fetch_param('summary', 'An event happening in New York, NY'));
        $location    = $this->escape(ee()->TMPL->fetch_param('location'), 'New York, NY');
        $sequence    = $this->escape(ee()->TMPL->fetch_param('sequence', 1));
        $url         = $this->escape(ee()->TMPL->fetch_param('url', ''));
        $description = $this->escape(trim(ee()->TMPL->tagdata));

        $this->return_data = "BEGIN:VEVENT\r\n" . 
                             "UID:" . $uid . "\r\n" . 
                             "DTSTAMP:" . $start_time . "\r\n" . 
                             "DTSTART:" . $start_time . "\r\n" . 
                             "DTEND:" . $end_time . "\r\n" . 
                             "SUMMARY:" . $summary . "\r\n" . 
                             "LOCATION:" . $location . "\r\n" . 
                             ( (!empty($description)) ? "DESCRIPTION:" . $description . "\r\n" : '' ) . 
                             ( (!empty($url)) ? "URL:" . $url . "\r\n" : '' ) . 
                             "SEQUENCE:" . $sequence . "\r\n" . 
                             "END:VEVENT"."\r\n";

        // return event object
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
        // replace, then strip html tags
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