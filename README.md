# Buddypress Groups Events
**Groups Events Management for Buddypress and BuddyBoss.**  
Create, manage and display events inside groups, with maps, participation, virtual/presential modes, widgets, shortcodes and ICS export.

Author: **Socialforger**  
License: **GPLv2 or later**  
Text Domain: **bpgevents**

---

## ✨ Features

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
• 	Upcoming Events
• 	Events Map

### 🎨 Templates Included
• 	bpgevents-single-event.php
• 	bpgevents-archive.php
• 	bpgevents-group-events.php
Automatically override theme templates.

### 📦 Installation
1. 	Upload the plugin folder to /wp-content/plugins/
2. 	Activate the plugin through the “Plugins” menu in WordPress
3. 	If BuddyPress/BuddyBoss is active, a new Events tab will appear inside each group
4. 	Use shortcodes or widgets to display events anywhere

### 🧩 Template Overrides
To customize templates, copy them into your theme:
yourtheme/bpgevents/bpgevents-single-event.php
yourtheme/bpgevents/bpgevents-archive.php
yourtheme/bpgevents/bpgevents-group-events.php

### 🌍 Translations
The plugin is fully translatable.
• 	 file included in 
• 	Compatible with Poedit, WP‑CLI and GlotPress

### 🧪 Development

**Requirements**
• 	WordPress 5.8+
• 	PHP 7.4+
• 	BuddyPress or BuddyBoss (optional but recommended)

**Folder Structure**

bpgevents/
│
├── admin/
├── assets/
│   ├── css/
│   └── js/
├── includes/
│   ├── helpers/
│   └── ...
├── shortcodes/
├── templates/
├── widgets/
├── languages/
└── bpgevents.php

**Autoloader**
The plugin uses a lightweight PSR‑4‑style autoloader.

### ❓ FAQ
Does it work with BuddyBoss?
Yes. Fully compatible.
Do I need Google Maps API keys?
No. The plugin uses Leaflet + OpenStreetMap, which is free and requires no API keys.
Are virtual events supported?
Yes. Virtual events include a meeting URL and optional automatic city geocoding.
Can I customize templates?
Yes. Copy them into your theme as described above.

### 📸 Screenshots (WordPress.org)
1. 	Group Events tab
2. 	Single event page with map
3. 	Events archive
4. 	Upcoming Events widget
5. 	Events Map widget

### 📝 Changelog
1.0.0
• 	Initial release
• 	Group events
• 	Maps (Leaflet)
• 	Participation system
• 	ICS export
• 	Widgets
• 	Shortcodes
• 	Templates
• 	BuddyPress/BuddyBoss integration

### 🧹 Uninstall
The plugin includes an uninstall.php file that removes:
• 	plugin options
• 	event meta keys
• 	participation meta
• 	transients
Event posts (bpge_event) are not deleted.

### 📄 License
Released under the GPLv2 or later license.
You are free to modify, distribute and contribute.

### 🤝 Contributing
Pull requests are welcome.
For major changes, please open an issue first to discuss what you would like to change.

### ❤️ Credits
Developed by Socialforger <socialforger@gmail.com>

