== Gravity Forms Keap Feed ==
* Plugin Name: KeapConnect
* Contributors: systasiscomputersystems
* Tags: Systasis, Gravity Forms, add-on, Keap/Infusionsoft, crm
* Requires at least: 5.0
* Requires PHP: 7.0
* Tested up to: 6.7
* Stable tag: 3.0
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

Sync form submissions between Gravity Forms and Keap

== Description ==
### The best Keap feed for Gravity Forms.
Integrates Gravity Forms with Keap (formerly Infusionsoft) CRM, allowing form submissions to be automatically sent to your Keap CRM account.

###Features

####Easily create contacts under certain conditions
For example, Contact creation occurs only when the visitor enters an email address.

####Affiliate tracking
Give your affiliates a link containing their Affiliate code to a Gravity Form. Keap will record the link click.



####Leadsource assignment
Assign leadsources to a Keap contact.

####Duplicate checking
Duplicates will be updated or created. Check on:

 - **Company**, **Name**, **Email**
 - **Name**, **Email**
 - **Email**

####Upload to Contact Filebox
Optionally upload files to a Contact's filebox using a form upload field.

####Combine fields
Avoid assigning too many Keap custom fields. Combine values from multiple Gravity Form fields into one Keap custom Contact field.

####Conditional or Unconditional API Goals
Trigger an Keap campaign based on submitted form values or on every Gravity Form submission.

####Feed export and import
Gravity Forms' Form Export/Import service includes Feed definitions.

== Installation ==
These steps assume that the Gravity Forms WordPress plugin is installed.

1. Install and activate the [Gravity Forms Infusionsoft Feed Add-On](https://wordpress.org/plugins/systasis-gf-infusionsoft-feed/)
2. Configure your Service Account Key. [Instructions on generating your Service Account key](https://help.infusionsoft.com/userguides/get-started/tips-and-tricks/api-key)


== Configuration ==

See [Configuration](https://systasis.co/gravity-forms-infusionsoft-feed-configuration) for configuration instructions.

== Deactivate ==

Use the following process to disable the plugin and clear the custom field cache:

- *Plugins* > Systasis GF Infusionsoft Feed > *Deactivate*

== Uninstall ==

This process *deletes all feeds*, clears all caches and deactivates the plugin.
Use this process when you suspect the plugin is not operating correctly *and you want to delete all feeds*.
This process deactivates the plugin, it does not delete the plugin from the WordPress environment.

Use the following process to *delete all feeds* and Deactivate the plugin.

- *Plugins* > Systasis GF Infusionsoft Feed *Settings* > *Uninstall Add-On*

== Delete ==

You must _Deactivate_ or _Uninstall_ the plugin before performing this process.

Use the following process to remove the plugin from the WordPress environment:

- *Plugins* > Systasis GF Infusionsoft Feed *Delete*

== Frequently Asked Questions ==
= Tag Application =
This feed add-on does not directly support conditional tag application.

- Use an API goal connected to one or more sequences to implement unconditional tag application.
- Map Gravity Forms fields to Keap Contact custom fields, then use an API goal connected to a decision diamond connected to two or more sequences to implement conditional tag application.

= Support Services =
- Item support available at [Gravity Forms Infusionsoft plugin support](https://wordpress.org/support/plugin/systasis-gf-infusionsoft-feed/) includes:
  - Availability of the author to answer questions
  - Answering technical questions about item’s features
  - Assistance with reported bugs and issues
  - Help with included 3rd party assets

- Item support does not include:
  - Customization services
  - Installation services

== Screenshots ==
1. Feed Add-on Settings
1. Create/Edit/Duplicate/Delete Feed
1. Feed Name and Action Settings
1. Upload to Contact Filebox Settings
1. Contact Details Settings
1. API Goal Settings
1. Combine Fields Settings
1. Feed Conditional Logic Settings

== Changelog ==

= 3.0.0 =
Implement Keap Service Account Key (SAK) support.
  You must replace your Legacy API key with a SAK before 31-Oct-2024.
  Site admins will be reminded to generate a SAK on Gravity Forms admin screens until that replacement occurs.
  See <a href="https://help.keap.com/help/api-key" target="_blank">these instructions</a> for help with this process.
  Copy the generated value to the Feed configuration.
  IMPORTANT: Do not re-use the key. Generate a key for each Keap connection, even if it's also a WordPress plugin.

  This is an irreversible upgrade after 31-Oct-2024. 
  This Feed Add-On will cease to function using a Legacy API key after that date per <a href="https://docs.google.com/document/d/1maBNDXt3RcOUbq6OkclInsL6wH9uct_YOZ3GpzqZ0_g/edit" target="_blank">Keap email</a>.
  
  Rename Feed Add-On to Keap Connect.

  Complete transition to private Keap SDK. Obsolete wp_options named INFUSIONSOFT_API_KEY & INFUSIONSOFT_APP_NAME. If no other plugins use the Novak SDK, these entries can be removed.

== Additional Info ==

=== Clear Cache ===
Due to Keap API usage limits, Gravity Forms stores Keap custom fields data for twelve hours.
If you make a change to your custom fields, you might not see it reflected immediately due to this data caching.

1. Navigate to Gravity Forms Settings > Systasis Gravity Forms Feed Add-On
1. Click the "Clear Custom Fields Cache" button

=== Affiliate Field Type ===
The Feed Add-On supports affiliate tracking using a custom field type.
The purpose of this field is to extract an Affiliate Code or ID from the URL, and send it to Keap via its link tracking service.
You will use this field as follows:

1. Add an instance of it to a Gravity Form
2. Configuring the resulting field to indicate the name of the URL query variable

==== Step One: Add an Affiliate field ====
Find the Affiliate ID field in the "Advanced Fields" section

The Affiliate Id field is a *hidden* field on your form
Add the field to a form like any other field, usually at the top of the form.

==== Step Two: Configure an Affiliate field ====
The only configuration required for an Affiliate` field is to name the URL query parameter.

The presence of this field triggers the Feed Add-On to create a link tracking record for the Affiliate in the newly created or updated Contact.

1. Open the field's configuration panel, then click the "General" tab
2. Change the Field Label value to "Affiliate Code" from "Untitled"
1. Click the "Advanced" tab
2. Enter the name of the URL query parameter in the "Parameter Name" setting
   + Name the field "affiliate" when the form will be loaded by an Infusionsoft Referral Partner link, found on the Referral Partner Center.
   + The name choice is yours to make when the form will be loaded via links you create in emails or other web pages.

=== Data Type Mapping ===
Describes how Gravity Forms data types map to Keap data types.

These are suggested GF types. The GF Single Line Text data type will map to any Keap data type.
For example, Keap will not store a Single Line Text with the value "32-Jan-2018" as a Date data type.

Keap data types that **do not** define value choices will reject a bad value when that value is out of range.
Keap data types that define value choices will accept any value even when that value is not in the choice list.
<pre>
Keap                    Gravity Forms      Comment
-------------           ------------       -------
Contact File Box        File Upload

Currency                Number             2 decimal places

Date                    Date

Date/Time               N/A

Day of Week             Drop Down          Use numbers 1 - 7 as values
                                           See Note 1
                        Number             Use GF min/max

Decimal Number          Number

Drilldown               N/A

Dropdown                Drop Down

Email                   Email

Month                   Drop Down          Use numbers 1 - 12 as values
                                           See Note 1
                        Number             Use GF min/max

List Box                Multi Select

Name                    Name (Full)

Percent                 Number

Phone Number            Phone

Radio                   Radio Buttons

Social Security Number  N/A

State                   Drop Down          Use two-letter state abbreviation as values
                                           See Note 1

Text                    Single Line Text   255 characters

Text Area               Checkboxes        \
                        Drop Down          \
                        Multi Select        > 64,000 characters, csv list of selected options
                        Radio Buttons      / See Note 1
                                          / See Note 2

Text Area               Paragraph Text     64,000 characters

User                    N/A

User List Box           N/A

Website                 Website

Whole Number            Number             Round half away from zero

Year                    Drop Down          See Note 1
                        Number             Use GF min/max

Yes/No                  Checkboxes        \ See note 3
                        Drop Down          \ See Note 1
                                            > Yes = 1, No = 0
                        Radio Buttons      /
</pre>
1   Ensure the default value is the empty value; make the first entry an empty value.
2   Map combineable fields using the "Combine Fields" feature and setting the CSS class on the field's <em>Appearance</em> tab.
    Use the Keap Text Area datatype to store more than 255 combined characters.
3   For opt-in, consider a Checkbox with a single option value "Yes"

== Upgrade Notice ==
= 3.0 =
Implement Service Account API keys. irreversible upgrade.

= 2.0 =
Implement conditional API goal. Not backward compatible with version 1.0

= 1.0 =
Change to GPLv3 license from MIT