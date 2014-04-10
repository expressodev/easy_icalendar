<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Easy iCalendar plugin by Crescendo (support@crescendo.net.nz)
 *
 * Copyright (c) 2010 Crescendo Multimedia Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR "AS IS" AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

$plugin_info = array(
    'pi_name'           => 'Easy iCalendar',
    'pi_version'        => Easy_ical::VERSION,
    'pi_author'         => 'Exp:resso',
    'pi_author_url'     => 'http://exp-resso.com/',
    'pi_description'    => 'Create valid iCalendars in seconds',
    'pi_usage'          => Easy_ical::usage(),
);

class Easy_ical
{
    const VERSION = '1.2.1';

    public function calendar()
    {
        $out = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\n";
        $out .= "PRODID:-//ExpressionEngine Easy iCalendar plugin//NONSGML v".self::VERSION ."//EN\r\n";

        if (ee()->TMPL->fetch_param('timezone') !== FALSE) {
            $out .= "X-WR-TIMEZONE:".$this->escape(ee()->TMPL->fetch_param('timezone'))."\r\n";
        }

        if (ee()->TMPL->fetch_param('calname') !== FALSE) {
            $out .= "X-WR-CALNAME:".$this->escape(ee()->TMPL->fetch_param('calname'))."\r\n";
        }

        // EE has probably put heaps of useless whitespace between each entry
        $tagdata = trim(ee()->TMPL->tagdata);
        $tagdata = preg_replace('/END\:VEVENT\s*BEGIN\:VEVENT/', "END:VEVENT\r\nBEGIN:VEVENT", $tagdata);
        if (!empty($tagdata)) $out .= $tagdata."\r\n";

        $out .= "END:VCALENDAR";

        // print output directly with the correct content-type
        $content_type = ee()->TMPL->fetch_param('content_type');
        if (empty($content_type)) $content_type = 'text/calendar; charset=UTF-8';

        header('Content-Type: '.$content_type);
        exit($out);
    }

    public function event()
    {
        $out = "BEGIN:VEVENT\r\n".
            "UID:".$this->escape(ee()->TMPL->fetch_param('uid'))."\r\n";

        $all_day  = FALSE;
        $localize = TRUE;

        if (ee()->TMPL->fetch_param('all_day') !== FALSE) {
            if (
                ee()->TMPL->fetch_param('all_day') === "y" OR 
                ee()->TMPL->fetch_param('all_day') === "yes"
            ) {
                $all_day = TRUE;
            }
        }

        if (ee()->TMPL->fetch_param('localize') !== FALSE) {
            if (
                ee()->TMPL->fetch_param('localize') === "n" OR 
                ee()->TMPL->fetch_param('localize') === "no"
            ) {
                $localize = FALSE;
            }
        }

        if (ee()->TMPL->fetch_param('location') !== FALSE) {
            $out .= "LOCATION:".$this->escape(ee()->TMPL->fetch_param('location'))."\r\n";
        }

        $out .= "DTSTAMP:".$this->ical_time(ee()->TMPL->fetch_param('start_time'), $all_day, $localize)."\r\n";
        $out .= "DTSTART:".$this->ical_time(ee()->TMPL->fetch_param('start_time'), $all_day, $localize)."\r\n";

        if (ee()->TMPL->fetch_param('end_time') !== FALSE) {
            $end_time = ee()->TMPL->fetch_param('end_time');

            if ($all_day) {
                $end_time = strtotime('+1 day', $end_time);
            }

            $out .= "DTEND:".$this->ical_time($end_time, $all_day, $localize)."\r\n";
        }

        if (ee()->TMPL->fetch_param('summary') !== FALSE) {
            $out .= "SUMMARY:".$this->escape(ee()->TMPL->fetch_param('summary'))."\r\n";
        }

        if (ee()->TMPL->fetch_param('sequence') !== FALSE) {
            $out .= "SEQUENCE:".$this->escape(ee()->TMPL->fetch_param('sequence'))."\r\n";
        }
        if (ee()->TMPL->fetch_param('url') !== FALSE) {
            $out .= "URL:".$this->escape(ee()->TMPL->fetch_param('url'))."\r\n";
        }

        $description = trim(ee()->TMPL->tagdata);
        if (!empty($description)) {
            $out .= "DESCRIPTION:".$this->escape($description)."\r\n";
        }

        $out .= "END:VEVENT"."\r\n";

        return $out;
    }

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

    public function ical_time($time, $all_day, $localize)
    {
        if ($localize) {
            if ($all_day) {
                return ee()->localize->format_date('%Y%m%d', $time);
            } else {
                return ee()->localize->format_date('%Y%m%dT%H%i%s', $time);
            }
        } else {
            if ($all_day) {
                return date('Ymd', $time);
            } else {
                return date('Ymd\THis', $time);
            }
        }
    }

    public static function usage()
    {
        // for performance only load README if inside control panel
        return REQ == 'CP' ? file_get_contents(PATH_THIRD.'easy_ical/README.md') : '';
    }
}
