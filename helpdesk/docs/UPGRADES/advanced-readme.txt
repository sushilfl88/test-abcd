-------------------
Advanced Update
-------------------

This is for people who have modified the original templates/stylesheets etc and don`t want to lose their changes.

Note that restrictions may apply. For example, for major template changes it may be better to start again.

---------------------------
Beta Release
---------------------------

If this is a beta release, approach with caution. It is recommended you DON`T update your live 
environment with a beta version in case there are bugs

---------------------------
Upgrade Instructions
---------------------------

1. Make a backup of your support system and database.

2. Download the latest version of Maian Support
   http://www.maiansupport.com/download.html

3. If you added additional priority levels to 'control/priority-levels.php' in v2.1, ensure this file is in your
   installation before you upgrade the database. This is not required if you are running 2.2 or higher already.

4. Overwrite your installation with the new file set with the EXCEPTION of the following which should NOT be updated:

   control/connect.inc.php
   licence.lic
   templates/*
   stylesheet.css

5. Next, a little patience is required. Go through each of the instruction files in the 'CHANGES' folder and update as instructed.

   Depending on which version you are upgrading from, this could take a few minutes.
   
   Update in version order as changed detailed are from the previous version.
   
6. Access your support 'install/upgrade.php' file in a browser and follow the instructions.
    
   NOTE: install/upgrade.php NOT install/index.php

7. Once the upgrade is complete, remove or rename the 'install' folder.

8. Refer to the latest version installation instructions for any new folders that require permissions:
   "Step 4: Permissions" > docs/install_2.html

9. Refer to the latest version installation instructions for any new crontabs/job that may require setting up:
   "Step 7: Crontabs/Cronjobs" > docs/install_2.html
   
10. Finally, refer to the changelog to see whats new:
    http://www.maiansupport.com/changelog.txt  

----------------
Problems
----------------

If the database update doesn`t work, try it again. Go into your database`s 'msp_settings' table and reset the 'softwareVersion' value back to the previous version.

Then re-run the upgrade. Your servers mysql error log may reveal details of why upgrades are failing.

----------------
Changelog
----------------

http://www.maiansupport.com/changelog.txt