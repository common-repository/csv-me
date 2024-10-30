=== CSV Me ===
Contributors: scottyla
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=JUX7ZYCJHF4H2&lc=US&item_name=CSV%20Me%20Donations&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: csv, shortcode, upload
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Upload csv files from a local directory or via a URL. Display the data in any post or page using a basic shortcode.

== Description ==
CSV Me is a very simple plugin that allows users to add a csv file from any url or select files from their local computer. The plugin stores the files and makes the data available for use in Posts and Pages via a shortcode.

**How to use CSV Me**

1. Follow the steps outlined in the Installation section to install the plugin.
1. Open the CSV Me File Manager by using the dashboard widget or find it under the Tools section of the Admin Menu.
1. Enter in a valid url linking to a csv file in the text box and clicking submit. Alternatively, users can select a file from their local machine and clicking save. If it is a valid csv file it will show up in your available files in the File Manager. See the frequently asked questions section for more information on valid csv file formats.
1. Now the saved csv files are available for display using shortcodes in post and pages.

The following are examples of how to use the CSV Me shortcodes:

**To display all data from a file named filename.csv:**

	[csv_me name='filename.csv']

Notice the underscore on csv_me, quotes around the file, and the filename.csv must match one of the files listed in the File Manager. 

**To display only column names of col1, col2 and col3:**

	[csv_me name='filename.csv' columns=’col1, col2, col3’]

 This will display only col1, col2, col3, in that order, if they are valid columns listed in the File Manager.  Learn more about how columns work [CSV Me Documentation]( http://www.zootowntech.com/wordpress-plugins/csv-me/) as well as the frequently asked questions section.

**To display only rows 3 through 10:**

	[csv_me name='filename.csv' row_start=3 row_end=10]

**Display sortable tables:**

	[csv_me name="example-file.csv" sortable='yes' header_style='blue']

This will display all the data in a table and allow users to sort the data by columns. The default header style is 'blue' however, it can be set to 'green' as well. It utilizes the excellent jQuery plugin called [tablesorter.js]( http://tablesorter.com/docs/ ).

Find out more tips on how to use the CSV Me shortcodes here: [CSV Me Documentation](  http://www.zootowntech.com/wordpress-plugins/csv-me/)

== Installation ==

**From your Wordpress Dashboard**

1. Visit 'Plugins > Add New'
1. Search for ‘CSV Me’.
1. Activate CSV ME from your Plugins page. 
1. Visit CSV Me File Manager dashboard widget or find it under the Tools Admin Menu to upload new files.

**From Wordpress.org**

1. Download CSV Me.
1.  Upload the csv-me folder to your '/wp-content/plugins/' directory.
1. Activate CSV ME from your Plugins page. 
1. Visit CSV Me File Manager dashboard widget or find it under the Tools Admin Menu to upload new files.

== Frequently Asked Questions ==

= My file is not displaying the columns correctly, what's wrong? =

Make sure that your csv file has column header values for the first row. Otherwise it will display all columns by default. Also check to make sure each column name in your shortcode matches one of the column names listed for your file in the File Manager.

= Can I change the color, fonts, or other styles of my displayed tables? =

Unfortunately, not at this time. All tables are displayed using the default Wordpress styles. 

= Can I order my rows to be displayed in descending order? =

Again, not at this time. However, future versions may support this feature.

= I can’t upload my file I keep getting the error *does not seem to be a CSV file and was not added to your available csv files*, what is wrong? =

CSV Me checks the MIME-Type and since this plugin is only meant to handle csv files there are only certain types that are saved. The following MIME types are allowed: text/comma-separated-values, text/csv, application/csv, application/excel, application/vnd.ms-excel, application/vnd.msexcel, text/plain

= I cannot upload a file from my computer, what's going on? =

CSV Me uses a function, called finfo, to check what type of files are uploaded. It is a handy function but it only works in PHP 5.3 or above. A lot of hosting providers run PHP 5.2 as default. To fix this problem you can update your hosting server to run PHP 5.3. If using cPanel most providers should be similar to this: [cPanel Configuration](http://www.inmotionhosting.com/support/website/php/how-to-change-the-php-version-your-account-uses).

== Screenshots ==

1. CSV Me File Manager.
2. Example shortcode in a post.
3. Output produced by example shortcode.

== Changelog ==

= 2.0 =
* Update: Added ability to allow sortable tables via shortcode
* Fix: Improved display of data

= 1.1 = 
* Fix: Upload via url uses more secure and efficient wp_remote_get function
* Fix: Shortcode now displays first column of the csv file correctly when spcified in shortcode
* Fix: Shortcode ignores invalid column names

= 1.0 =
*Initial release

== Upgrade Notice ==

= 2.0 =
New and improved table display as well as the option for sortable tables via shortcode.

= 1.1 =
Improves shortcode functionality when specifying column names. Also utilizes wp_remote_get for retrieving csv files via url, making this process more efficient and secure.