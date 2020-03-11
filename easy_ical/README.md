# Easy iCalendar

ExpressionEngine plugin that builds valid iCalendar files.
*Forked from https://github.com/expressodev/easy_icalendar/*

## Requirements

ExpressionEngine v2+
*Compatible with EE v2-5*

## Installation

- **EE v2:** Copy `easy_ical` directory into `/system/expressionengine/third_party/`
- **EE v3:** Copy `easy_ical` directory into `/system/user/addons/`
- **EE v4:** Copy `easy_ical` directory into `/system/user/addons/`
- **EE v5:** Copy `easy_ical` directory into `/system/user/addons/`

## Usage

### `{exp:easy_ical:calendar}`

Along with `{exp:easy_ical:event}`, builds the <a href="https://en.wikipedia.org/wiki/ICalendar#Core_object" target="_blank" title="Read more about the iCalendar Core Object">iCalendar Core Object</a>

#### Parameters

##### `timezone` (*optional*)

Specify the timezone for all events

– **Type:** string
- **Default:** `America/New_York`
– **Options:** [PHP timezones](https://www.php.net/manual/en/timezones.php)

##### `calendar_name` (*optional*)

Give your calendar a name

– **Type:** string
- **Default:** `Save the Date!`

##### `content_type` (*optional*)

Force the specified content type (for debugging)

– **Type:** string
- **Default:** `text/calendar; charset=UTF-8`
– **Options:** `text/plain`

##### `filename` (*optional*)

Name for the generated iCalendar file

– **Type:** string
- **Default:** `save-the-date`

### `{exp:easy_ical:event}`

Along with `{exp:easy_ical:calendar}`, builds the <a href="https://en.wikipedia.org/wiki/ICalendar#Core_object" target="_blank" title="Read more about the iCalendar Core Object">iCalendar Core Object</a>

#### Parameters

##### `uid` (*optional*)

A unique identifier for the event, typically `{entry_id}`

– **Type:** string
- **Default:** unix timestamp

##### `start_time` (*optional*)

The event start date and time, typically `{entry_date}`

– **Type:** int
- **Default:** Current date and time (unix timestamp)

##### `end_time` (*optional*)

The event end date and time, typically `{expiration_date}` or custom channel field (date)

– **Type:** int
- **Default:** Current date and time + 24 hours (unix timestamp)

##### `location` (*optional*)

The event location, typically a custom channel field (text).

– **Type:** string
- **Default:** `New York, NY`

##### `summary` (*optional*)

The event summary, typically `{title}` or a custom channel field (text)

– **Type:** string
- **Default:** `An event happening in New York, NY`

##### `url` (*optional*)

Allows you to add a link to the event.

– **Type:** string
- **Default:** `{site_url}{url_title}` via current install

##### `sequence` (*optional*)

Needed if you update an entry with the same `uid`, otherwise iCal will not update the event.

– **Type:** int
- **Default:** unix timestamp

#### Example Usage
```
{exp:easy_ical:calendar timezone="Pacific/Auckland" name="My Event Calendar"}
    {exp:channel:entries channel="events" show_future_entries="yes" show_expired="yes" limit="20"}
        {exp:easy_ical:event uid="{entry_id}" start_time="{entry_date}" end_time="{expiration_date}" location="{event_location}" summary="{title}"}
            {event_description}
        {/exp:easy_ical:event}
    {/exp:channel:entries}
{/exp:easy_ical:calendar}
```
