I am using default php server on mac with localhost, and mysql server community version.
Before starting the php server, please edit the database login information at the begining of logincheck.php and RentCheckout.php.
After unzipping the stuff, start php server at the directory where you unzip them in command window and start mysql database with default data provided on NYUClass.

Double click login.html, type in correct member id and any keyword, and you will see all the books that available for rent. Click on the 'Rent Me' hyperlink, it will jump to rent processing result page with all the books info which are rented by the logined user.

All pages include a hyperlink that could go back to login.html to start over.

Please note that I did not check whether the session is geniune at checkout. The hyperlink that created to be used for checkout is not safe in network security sense. I believe that there is no requirement of network security in this assignment from the problem description so I did not design the security stuff.