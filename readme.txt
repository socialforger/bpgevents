=== Buddypress Groups Events ===
Contributors: socialforger
Tags: buddypress, buddyboss, events, groups, maps, leaflet, participation, ics
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Groups Events Management for Buddypress and BuddyBoss. Create, manage and display events inside groups, with maps, participation, virtual/presential modes, widgets and shortcodes.

== Description ==

**Buddypress Groups Events** adds a lightweight and modern event system inside BuddyPress and BuddyBoss groups.

It includes:

- Group Events tab inside BuddyPress/BuddyBoss groups  
- Presential and Virtual events  
- Leaflet maps with OpenStreetMap  
- Automatic city geocoding for virtual events  
- Participation system (Join/Leave)  
- ICS calendar export  
- Widgets (Upcoming Events, Events Map)  
- Shortcodes for lists, maps and user events  
- Clean templates for single and archive views  
- Fully translatable (POT included)  
- No external APIs required (OSM only)

Designed to be **fast, simple, privacy‑friendly and theme‑compatible**.

---

== Features ==

### 🟦 Group Events
Adds an **Events** tab inside each BuddyPress/BuddyBoss group, showing all events created by group members.

### 🟩 Event Types
- **Presential** events with address, city, province, country  
- **Virtual** events with meeting URL  
- Automatic fallback geocoding for virtual events without coordinates  

### 🗺️ Maps (Leaflet + OpenStreetMap)
- No API keys required  
- Custom markers  
- Widget for global events map  
- Shortcode for single event map  

### 👥 Participation System
Users can:
- Join an event  
- Leave an event  
- See participant count in real time (AJAX)

### 📅 ICS Export
Each event can be downloaded as an `.ics` file and imported into:
- Google Calendar  
- Apple Calendar  
- Outlook  
- Thunderbird  

### 🧩 Shortcodes
**List all events**
[bpgevents_events_list]
**List events of the logged‑in user**
[bpgevents_my_events]
**Single event map**
[bpgevents_event_map id="123" height="350px"]

### 🧱 Widgets
- **Upcoming Events**
- **Events Map**

### 🎨 Templates Included
- `bpgevents-single-event.php`
- `bpgevents-archive.php`
- `bpgevents-group-events.php`

Automatically override theme templates.

---

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the “Plugins” menu in WordPress
3. If BuddyPress/BuddyBoss is active, a new **Events** tab will appear inside each group
4. Use shortcodes or widgets to display events anywhere

---

== Frequently Asked Questions ==

= Does it work with BuddyBoss? =
Yes. The plugin is fully compatible with BuddyBoss Platform.

= Do I need Google Maps API keys? =
No. The plugin uses **Leaflet + OpenStreetMap**, which is free and requires no API keys.

= Can users create events from the frontend? =
Yes, using the standard WordPress editor or any frontend post form plugin.

= Are virtual events supported? =
Yes. Virtual events include a meeting URL and optional automatic city geocoding.

= Can I customize templates? =
Yes. Copy the templates from `/templates/` into your theme:
yourtheme/bpgevents/bpgevents-single-event.php yourtheme/bpgevents/bpgevents-archive.php

= Is the plugin translatable? =
Yes. A `.pot` file is included in `/languages/`.

---

== Screenshots ==

1. Group Events tab inside BuddyPress/BuddyBoss  
2. Single event page with map  
3. Events archive  
4. Upcoming Events widget  
5. Events Map widget  

---

== Changelog ==

= 1.0.0 =
* Initial release  
* Group events  
* Maps (Leaflet)  
* Participation system  
* ICS export  
* Widgets  
* Shortcodes  
* Templates  
* BuddyPress/BuddyBoss integration  

---

== Upgrade Notice ==

= 1.0.0 =
First stable release.
