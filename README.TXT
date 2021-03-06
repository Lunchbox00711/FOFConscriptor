************************************************************************************



    This program is free software: you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation, either version 3 of the License, or

    (at your option) any later version.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program.  If not, see <http://www.gnu.org/licenses/>.



    $Id: README.TXT,v 1.13 2008/05/23 14:51:01 jonbaker Exp $



************************************************************************************



Thanks for using the Front Office Football Conscriptor.  This app is very easy to set up,

but here is a short list of instructions to get you started.



Here is how the Front Office Football Conscriptor works: 



  * If you do not have a user account, regsiter on the main page.

    NOTE: YOUR USER ID WILL BE YOUR TEAM'S IN-GAME CITY. 



  * Log in and choose the players that you are interested in. 



  * When you've chosen the players you're interested in, set a priority for each player. 



  * You can go as deep into the draft as you want, setting as many priorities as you want. 



  * When the highest-rated player is selected, by default the program will delete other players in their queue

    at the same position. This setting can be changed under "Options." 



  * If the next player has picks selected, the program chooses their highest priority from the available players,

    and so on, until it runs out of players to choose from. 



  * The admin can choose for the team that's on the clock if they are taking too long or are an CPU team.



  * The admin can also skip a team that is on the clock.  A team that has been skipped may make their selection

    at any time.



  * The admin may set a time limit for picks, as well as a window where that time limit is in effect.

    If a team does not make a selection by the time limit, they are automatically skipped.  The admin may

    also set each team's relative time limit, i.e. 100%, 75%, 50%, etc. of the actual limit, if a team

    continues to miss picks (depending on your draft rules)



***********************

  SYSTEM REQUIREMENTS

***********************



The FOF Conscriptor was built on PHP 5.2.5 and MySQL 5.0.45.  PHP 4 will run the utility successfully with the

exception of the installer.  See the workaround below.  The system does use some functions that require MySQL 5.

For best results, please use PHP 5 and MySQL 5.



********************

  NEW INSTALLATION 

********************



  * You need a webserver and you need a MySQL database. 



  * Unzip the file into a directory on your webserver (it unzips to "fof_draft") and then navigate

    to that directory. Make sure the webserver can write to the "includes" directory, and make sure

    you have a database you can store the data in. 



  * Replace the ./images/icons/league_icon.jpg with an image of your league's icon saved under the same name.

    This will replace the "League Home" graphic with your own custom graphic.

  * In your web browser open the "fof_draft" directory, and you will be prompted with information on

    how to install. 



  * The installation script will write a file in the includes directory called "config.inc.php." 

    *** If you need to re-install for any reason, simply remove this file.

    *** Installing will delete any existing database tables, but it is not necessary to re-install to start a new draft. 



  * Once you finish the install, sign in as the admin user you just created, and follow the instructions to import

    the draft data. There will be two files, the "players" file and the "draft order" file. Directions on how to

    get those files are displayed on the upload screen. 



  * Once the program is installed, have your players register. On the login page is the registration link, each

    team just needs to enter the 3-character code for their team (i.e. DEN or SEA) and set a password. I didn't

    make any deeper security than that, I figured that would be easier than the admin assigning passwords to

    everyone and since it's unlikely that anyone will try to hack into your draft I choose ease of use over security. 



  * The admin can lock out a team by turning on the "has password" checkbox under the "users" tab. This may be

    done to prevent malicious users from registering as an AI team. Again, since FOF MP is such a community game,

    if you have users who would do that they probably should be expelled from your league! 



****************

 ADVANCED USERS

****************



You can further secure your draft by using an .htaccess password on the directory, most

hosting providers give you tools to set that up, and if you have your own server you probably already

know how to do that as well.  If you want to manually set passwords for the teams, you can do that directly

in the database, the passwords are stored in plain text so you can just enter passwords using phpMyAdmin

or any other MySQL utility.  You can even change the team names, they will show up accordnigly in all

the reports, and the users will need use the new team name to be able to sign in.  If you change the "admin"

team, it will change the login required for the admin.  You should not, however, change the "xxx" team

(if it exists), doing that will mess up the ability to remove or restore draft picks for a team.



*************

  UPGRADING

*************



Please read the UPGRADE.TXT file for information on upgrading.



************************

  E-MAIL NOTIFICATIONS 

************************



E-mail notifications of each pick that is made will automatically be sent to the admin's e-mail address

as well as any team that has entered an e-mail address for themselves if you turn this option on in the

admin options tab.  Some web hosting services do not cache outgoing e-mails efficently to reduce spam

sent by their members; as a result, the player selection process may time out.  Because of this, e-mail

notifications are disabled by default.  The admin does receive all e-mail notifications even if this setting

is turned off.



********************



If you have questions or comments, please feel free to e-mail me at wheelsvt@gmail.com or me@jdavidbaker.com



Thanks for using the Front Office Football Conscriptor!

