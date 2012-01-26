PHP-APE Explorer
================


Synopsis
--------

Actual application based on the PHP Application Programming Environment
(PHP-APE), PHP-APE Explorer implements a simple web-based file or image
explorer, which allows to easily grant read and/or write access to a given
directory (and its sub-directories) on the host filesystem.


Dependencies
------------

 - [MUST] PHP-APE (of corresponding major/minor version)
 - [MUST] PHP gd extension
 - [MAY] PHP Smarty (if the image explorer or gallery is required)


Features
--------

PHP-APE Explorer provides:
 - a file explorer, which can be used to read and (optionally) upload, update,
   and/or delete files
 - an image explorer, which can be used to view images and (optionally) modify
   their IPTC tags
 - an image gallery, which can be used to implement a simple image viewer

Global and directory-specific configuration settings allow to control access
and (read/insert/update/delete) permissions based on user and/or groups.

