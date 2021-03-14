# YOURLS-YouTube-title-fix
A YOURLS plugin to fetch YouTube page titles via the Google API

### Why?
YouTube titles are broken in YOURLS, only displaying a naked url. This can be corrected by fetching the page title via the Google API.
### Requirements
- A Google API key
    - Information [here](https://developers.google.com/youtube/v3/getting-started)
- YOURLS 1.7.9 ( only tested against this version )

### Installation
- Download this repo and place `youtube-title-fix` into `user/plugins/`
- Enable the plugin in the admin area of YOURLS
- Set API key in YouTube API Admin page

### Note
- Limit the API key credential to the IP address of your YOURLS server to avoid abuse. Other limiting methods seemed to result in failure.
- Use with [Title Refetch](https://github.com/joshp23/YOURLS-title-refetch) to fix broken YouTube titles that already exist in the YOURLS database

### Tips
Dogecoin: DARhgg9q3HAWYZuN95DKnFonADrSWUimy3

License
-------
Copyright 2020 Joshua Panter  
