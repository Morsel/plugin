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
