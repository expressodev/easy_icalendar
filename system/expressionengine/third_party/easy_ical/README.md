Easy iCalendar
================

Create valid iCalendars in seconds.

Installation
------------

To install Easy iCalendar, simply copy the entire `easy_ical` folder to
`system/expressionengine/third_party` on your server. You will then be able to use these
tags in your templates.

Requirements
------------

* ExpressionEngine 2.1.3+

Complete Example
----------------

    {exp:easy_ical:calendar timezone="Pacific/Auckland" name="My Simple Event Calendar"}
        {exp:channel:entries channel="events" show_future_entries="yes" show_expired="yes" limit="20"}
            {exp:easy_ical:event uid="{entry_id}" start_time="{entry_date}" end_time="{expiration_date}" location="{event_location}" summary="{title}"}
                {event_description}
            {/exp:easy_ical:event}
        {/exp:channel:entries}
    {/exp:easy_ical:calendar}
    
All of the CRLF and escaping characters nonsense will be handled for you automatically.

**NOTE: Any code in your template outside of the {exp:easy_ical:calendar} tag will be ignored!**

Calendar Tag Parameters
-----------------------

### timezone="Pacific/Auckland"

Specify the timezone for all dates

### name="My Calendar"

Give your calendar a name

### content_type="text/plain"

Force the specified content type (for debugging). Defaults to `text/calendar; charset=UTF-8`

Event Tag Parameters
--------------------

Any text inside the event tag will be used as the event description.

### uid="{entry_id}"

A unique identifier for the event

### start_time="{entry_date}"

The event start time/date

### end_time="{expiration_date}"

The event end time/date

### location="{event_location}"

The event location (text). You probably want to pull this from a custom channel field.

### summary="{title}"

The event summary (title). You probably want to pull this from a custom channel field.

### url="{url_title_path='group/template'}"

Allows you to add a link to the event.

### sequence="{event_sequence}"

This adds a simple sequence number to the event. This is needed if you update an entry, otherwise
iCal won't update the event. Use a simple counter custom field, like [Reevision](http://github.com/GDmac/Reevision.ee_addon)

Changelog
---------

**1.1.1** *(2011-06-09)*

* Adjusted the output slightly to fix compatibility issues with some versions of iCal

**1.1** *(2011-05-05)*

* Added url="" and sequence="" parameters (thanks to [GDmac](http://github.com/GDmac))

**1.0** *(2010-11-24)*

* Initial release
