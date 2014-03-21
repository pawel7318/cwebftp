== cwebftp

cwebftp is a web interface for easy FTP accounts management. Developed with MVC in mind uses:

* PHP
* Smarty - framework for Views
* proftpd - FTP daemon
* MySQL - to store all metadata

It provides 3 roles of account:
* admin - can peform any action in the application
* superuser - can manage it's own pool of users and their FTP accounts
* user - has access to his FTP account

