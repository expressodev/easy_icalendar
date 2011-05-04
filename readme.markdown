# Easy iCalendar

Create valid iCalendars in seconds  
Usage: Really basic, just construct a template with a channel entries tag, like so:

	{exp:easy_ical:calendar timezone="Pacific/Auckland" name="My Easy Event Calendar"}
		{exp:channel:entries channel="events" show_future_entries="yes" show_expired="yes" limit="20"}
			{exp:easy_ical:event uid="{entry_id}" start_time="{entry_date}" end_time="{expiration_date}" location="{event_location}" summary="{title}"}
				{event_description}
			{/exp:easy_ical:event}
		{/exp:channel:entries}
	{/exp:easy_ical:calendar}

All of the CRLF and escaping characters nonsense will be handled for you automatically.

*NOTE: Anything else in the template outside the {exp:easy_ical:calendar} tag will be ignored!*

## Optional tag parameters

### Parameters for the Calendar-tag
**content_type="debug"**  
This will output the template with a html/text header and pre-tag for debugging. Or specify your own content_type.  
Example: {exp:easy_ical:calendar content_type="debug ... }

### Parameters for the Event-tag
Example: {exp:easy_ical:event url="{url_title_path='group/template'}" update="{revision_num} ... }

**url="{url_title_path='group/template'}"**  
This allows you to add a link to a calendar event entry

**update={number}**  
This adds a SEQUENCE=(number) to the event. Needed if you update an entry, otherwise iCal won't update the event.  
Use a simple counter custom field, like http://github.com/GDmac/Reevision.ee_addon
