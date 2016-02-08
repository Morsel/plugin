=== Morsel ===
Contributors: nishantn
Donate link: http://www.eatmorsel.com/
Tags: Morsel, eatmorsel
Requires at least: 3.5.1
Tested up to: 4.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Share eatmorsel's content

== Description ==

The Morsel journey began with a restaurant publicist who heard those compelling, insightful stories straight from chefs, every day. She knew only a fraction were getting outside the four walls of the restaurant and she knew the secret to building restaurant business was getting those stories to diners hungry for insights into the inspirations, philosophies and vision of chefs.


== Installation ==

How to install the plugin and get it working.

e.g.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Morsel'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `morsel.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `morsel.zip`
2. Extract the `morsel` directory to your computer
3. Upload the `morsel` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard
5. Go to Setting page of Morsel's plugin
6. Enter Morsel user name and password and click on connect
7. Now you can create Post or Page and Put the shortcode [morsel_post_display] and that page display your top 20 morsels

== Frequently Asked Questions Ellen please update what you want==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* First launch

= 2.0 =
* Add Morsel Signup and Login functionality
* Add Comment and like/unlike functionality
* Add Embed Code functionality

= 2.1 =
* Modified the shortcode [morsel_post_display] add attribute to it to show no of latest morsel, made them central align, gap between morsel
[morsel_post_display count=4 center_block=1 gap_in_morsel=5px] like this
"count" : an integer value , define how much latest morsel you want to show.
"center_block" : it should be 1 or 0, this is for center the blocks of morsel (Default is 0).
"gap_in_morsel" : You can set through like 5px or 5% as a string, than it creates gaps between morsel blocks through padding-left and padding right with important,otherwise normal gap is maintained.
"wrapper_width" : Set the morsel wrapper width in %, if you want to make morsel window smaller in view, default is 100%.
"default email wordpress" : Stopped email for new user is created by morsel plugin. default role is subscriber and wp-admin does not open for subscriber. 


Morsel Plugin description
=============================================================================================================


Admin Side 
Settings : Login with your eatmorsel account also you have to put host settings.
Associated user : Admin can add other eatmorsel user in to his site and fetch their morsel as well.
Morsel : Whole morsel listing with several options over their.
         # Admin can edit whole morsel just same as eatmorsel site.
         # Pick keywords : Morsel also categorized by keyword(every morsel has only one keyword). Admin can select keyword or delete as well.
         # Pick Topic : Morsel also categorized by Topic(every morsel has multiple topics). Admin can select multiple topics via select box or delete as well.
         # Publish Morsel : Admin can also publish his unpublished morsel.
         # Schedule : Admin can also set time and date to publish his morsel automatically.

Manage Keywords: Admin can add edit delete keyword which is associated with his account.
Morsel Topic: Admin can add edit delete topic which is associated with his account.

Display : Admin can create shotcode for showing morsel on his site, There are certain parameters over shortcode.
        ## General Option
	         #Number of morsels to display: Number of morsel.
	         #Keyword to display: Choose keyword by select drop down.
	         #Associated user to display: Choose user by select drop down.
	         #Topic to display:  Choose topic by select drop down.
        ## Advance Option
             #Gap In Morsel: Gaps between morsel blocks through padding-left and padding right.
             #Wrapper Width: Morsel wrapper width in %, if you want to make morsel window smaller in view.
             #Center Block: For center the blocks of morsel.
             #Morsel In A Row: how many morsel in a row will be shown by shortcode
        ## Community Shortcode : You can create morsel add funnctionality over page and post by just put this [create_morsel] shortcode.
Slider : Just put this [morseldisplayslider] shortcode, slider will be put over any page and post. You can choose what morsel image you want over slider by simply select checkbox.
		# Width: Width of slider in % or px;
		# Slider Duration: interval duration between slides (in ms).
		# Autoplay: trur/false


=============================================================================================================


