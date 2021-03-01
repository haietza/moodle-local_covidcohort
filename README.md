The COVID cohort plugin allows the adding and removing of users to a preconfigured COVID cohort by uploading a CSV file.


# Installation instructions
Install the plugin according to the [MoodleDocs](https://docs.moodle.org/en/Installing_plugins) as a directory named covidcohort in the local directory.

Create a custom role for COVID cohort manager based on ARCHETYPE: Authenticated user, assignable at the system context level, and with the Add and remove COVID cohort members (local/covidcohort:assign) capability allowed.
Use Assign system roles to assign COVID cohort manager role to desired users.
Create a custom role for COVID cohort member based on ARCHETYPE: Authenticated user, assignable at the system context level.
Enter the shortname for the COVID cohort member role under Site administration > Plugins > Local > COVID cohort > settings.
Instruct COVID cohort manager users to add the Navigation block to their Dashboard, so they can access the link to the Upload users form.
Add a User tour for the desired Dashboard notification filtered to the COVID cohort member role.
Enter the ID for the COVID cohort user tour under Site administration > Plugins > Local > COVID cohort > settings.

# Usage instructions
COVID cohort managers can use the Upload users form to add or remove users from the COVID cohort and assign or unassign the COVID cohort custom role.
Select the desired action (Add, Remove) from the Action select menu.
Upload a CSV file of usernames on which to have the desired action performed. Usernames should be in a single column with no column header.