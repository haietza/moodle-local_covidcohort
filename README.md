The COVID cohort plugin allows the adding and removing of users to a preconfigured COVID cohort by uploading a CSV file.

# Installation instructions
Install the plugin according to the [MoodleDocs](https://docs.moodle.org/en/Installing_plugins) as a directory named covidcohort in the local directory.

# Usage instructions

## Site admin usage
* Create a cohort for members and enter the shortname under Site administration > Plugins > Local > COVID cohort > Settings.
* Create a custom role for COVID cohort manager
  * Based on ARCHETYPE: Authenticated user
  * Assignable at the system context level
  * With the Add and remove COVID cohort members (local/covidcohort:assign) capability allowed.
* Use Assign system roles to assign COVID cohort manager role to desired users.
* Instruct COVID cohort manager users to add the Navigation block to their Dashboard, so they can access the link to the COVID cohort > Upload users form.
* Create a custom role for COVID cohort member
  * Based on ARCHETYPE: Authenticated user
  * Assignable at the system context level
* Enter the shortname for the COVID cohort member role under Site administration > Plugins > Local > COVID cohort > Settings.
* Add a User tour for the desired Dashboard notification filtered to the COVID cohort member role.
* Enter the ID for the COVID cohort user tour under Site administration > Plugins > Local > COVID cohort > Settings.
* If COVID cohort managers need a current list of cohort members, site admins can Download from Site administration > Users > Accounts > Bulk user actions (use Cohort ID filter for shortname).

## COVID cohort manager usage
COVID cohort managers can use the Upload users form to add or remove users from the COVID cohort (and assign or unassign the COVID cohort custom role).
* Select the desired action (Add, Remove) from the Action select menu.
* Upload a CSV file of usernames on which to have the desired action performed. Usernames should be in a single column with no column header.

## General usage
* The user tour will reset once per day at 3:05am (so it will display again on the Dashboard). This schedule can be configured under Site administration > Server > Tasks > Scheduled tasks > Reset COVID cohort user tour.
* The assignment of users to the cohort is an ad hoc task, scheduled to run every minute, or with the next cron run.