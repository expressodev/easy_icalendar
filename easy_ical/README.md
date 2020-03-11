# Easy iCalendar

ExpressionEngine plugin that builds valid iCalendar files.

*Forked from <a href="https://github.com/expressodev/easy_icalendar/" target="_blank" title="Expresso's Easy iCalendar ExpressionEngine Plugin">Expresso's Easy iCalendar ExpressionEngine Plugin</a>*

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

Along with `{exp:easy_ical:event}`, builds the <a href="https://en.wikipedia.org/wiki/ICalendar#Core_object" target="_blank" title="Learn more about the iCalendar Core Object">iCalendar Core Object</a>

#### Parameters

##### `timezone` *(optional)*

Specify the timezone for all events

- **Type:** string
- **Default:** `America/New_York`
- **Options:** <a href="https://www.php.net/manual/en/timezones.php" target="_blank" title="Learn more about PHP timezones">PHP timezones</a>

##### `calendar_name` *(optional)*

Give your calendar a name

- **Type:** string
- **Default:** `Save the Date!`

##### `content_type` *(optional)*

Force the specified content type (for debugging)

- **Type:** string
- **Default:** `text/calendar; charset=UTF-8`
- **Options:** `text/plain`

##### `filename` *(optional)*

Name for the generated iCalendar file

- **Type:** string
- **Default:** `save-the-date`

### `{exp:easy_ical:event}`

Along with `{exp:easy_ical:calendar}`, builds the <a href="https://en.wikipedia.org/wiki/ICalendar#Core_object" target="_blank" title="Read more about the iCalendar Core Object">iCalendar Core Object</a>

#### Parameters

##### `uid` *(optional)*

A unique identifier for the event, typically `{entry_id}`

- **Type:** string
- **Default:** unix timestamp

##### `start_time` *(optional)*

The event start date and time, typically `{entry_date}`

- **Type:** int
- **Default:** Current date and time (unix timestamp)

##### `end_time` *(optional)*

The event end date and time, typically `{expiration_date}` or custom channel field (date)

- **Type:** int
- **Default:** Current date and time + 24 hours (unix timestamp)

##### `location` *(optional)*

The event location, typically a custom channel field (text).

- **Type:** string
- **Default:** `New York, NY`

##### `summary` *(optional)*

The event summary, typically `{title}` or a custom channel field (text)

- **Type:** string
- **Default:** `An event happening in New York, NY`

##### `url` *(optional)*

Allows you to add a link to the event.

- **Type:** string
- **Default:** `{site_url}{url_title}` via current install

##### `sequence` *(optional)*

Needed if you update an entry with the same `uid`, otherwise iCal will not update the event.

- **Type:** int
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

## Changelog

### 2.0.0 *(2020-03-11)*

- ExpressionEngine 3+ compatibility
- Refactored methods
- Overhauled documentation

### 1.3.0 *(2013-07-08)*

- Updated `name` attr to `calendar_name`
- Fixed a few conditionals

### 1.2.0 *(2013-07-08)*

- ExpressionEngine 2.6 compatibility

### 1.1.1 *(2011-06-09)*

- Adjusted the output slightly to fix compatibility issues with some versions of iCal

### 1.1.0 *(2011-05-05)*

- Added url="" and sequence="" parameters (thanks to [GDmac](http://github.com/GDmac))

### 1.0.0 *(2010-11-24)*

- Initial release

## License

Copyright © Crescendo Multimedia Ltd and individual contributors. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
3. Neither the name of the author nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

## Disclaimer

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS “AS IS” AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
