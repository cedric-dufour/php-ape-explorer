PHP Application Programming Environment (PHP-APE) - Explorer Application
========================================================================


Synopsis
--------

The PHP-APE Explorer Application allows one to easily grant read and/or write
access to a given directory (and its sub-directories) on the host filesystem
and thus implement a simple web-based file or image explorer.


Dependencies
------------

 - [MUST] PHP-APE (of corresponding major/minor version)
 - [MUST] PHP gd extension
 - [MAY] PHP Smarty (if the image explorer or gallery is required)


Features
--------

The PHP-APE Explorer Application provides:
 - a file explorer, which can be used to read and (optionally) upload, update,
   and/or delete files
 - an image explorer, which can be used to view images and (optionally) modify
   their IPTC tags
 - an image gallery, which can be used to implement an image gallery

Global and directory-specific configuration settings allow to control access
and (read/insert/update/delete) permissions based on user and/or groups.

