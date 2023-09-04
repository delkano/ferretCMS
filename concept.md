# Concept and ideas for a minimalistic static site generator with an online admin system

## Base

It would be developped with F3. We can use SQLite to store configuration and security.
Structure would be similar to:
+ /
  + db/ -> config and pages database
  + ui/ -> themes and ui files
  + www/ -> admin.php + all the html, css and js generated
  + vendor/ -> f3 and all other needed libs

## admin.php

First of all, name is pending. We probably should find something better.

The admin panel should be quite simple. Dashboard, page editor, menu editor, theme selector, user administration, and plugin management.

Page editor should be, similar to Wordpress, a list of pages. Since we are using SQLite, we can store everything within it.
