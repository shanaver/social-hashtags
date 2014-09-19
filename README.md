# Social Hashtags

### Info

Contributors: shanaver, mandiberg, thomasrstorey, hachacha, janiceaa

Tags: instagram, youtube, hashtags, videos, photos, images, API, twitter, teleportd

Requires at least: 3.0.1

Tested up to: 3.9

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html

### Changes made by Team Mandiberg 

(please note that the contributor & granular commit information is here: https://github.com/mandiberg/social-hashtags)

* Added option to keep or remove hashtagged words in retrieved posts.
* Added functionality to store more metadata from posts retrieved from Twitter and Instagram, including:
  * URL to post on Twitter/Instagram
  * URL to user on Twitter/Instagram
  * Timestamp for post from Twitter/Instagram
* Exposed metadata from retrieved posts to be displayed in the archive page.
* Added option to keep or remove Emoji from retrieved posts.
* Added option to whitelist usernames.
* Added option to pick a wordpress user to use as the author for social-hashtag posts.


### Future Possible Changes

* Add Infinite Scroll
* Abstract custom API token as a parameter set in Settings
* Iron out errors when retrieving posts manually from Twitter


###Installation

* Upload all files to a `social-hashtags` folder in the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

###Usage

* Add an API source & hashtag to pull from
* Run manually or set a cron schedule to run automatically

###Notes

Duplicates can occur if you run it manually while it's running already.  If you get duplciates, you can delete either one, or delete both and re-run it manually - just make sure you empty the trash because it looks in the trash for existing posts as well.
