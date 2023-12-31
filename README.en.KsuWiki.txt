NAME

    KsuWiki - a multi-site extension of PukiWiki 1.5.4
    
    PukiWiki - PHP scripts for Web pages which can be edited by anyone, 
               at any time, from anywhere. 

        PukiWiki 1.5.4
        Copyright
          2001-2022 PukiWiki Developement Team
          2001-2002 yu-ji (Based on PukiWiki 1.3 by yu-ji)
        License: GPL version 2 or (at your option) any later version
        https://pukiwiki.osdn.jp/

DESCRIPTION

KsuWiki is an enhanced PuwkiWiki that supports multiple sites under a single 
installation.

FEATURES 

1. Support multiple sites under a single PukiWiki installation

2. Support two usage modes 
  - 'view' mode, for readonly use, hide navigation bar and tool bar
  - 'edit' mode, for edit (after login), show navigation bar and tool bar

3. New plugins 
  - site.inc.php: a site administration tool
  - timed.inc.php: enable scheduled page access
  - snippet.inc.php: syntax-highlight source code

4. Reorganized layout of files and directories


INSTALL

1. Unzip the package to any folder, called PKWK_ROOT, under DocumentRoot 
2. Enter the PKWK_ROOT, install dependencies by typing
    composer install
3. Change permissions and ownership of wiki folders
    sudo chown -R apache.apache wiki
    sudo chmod -R 777 wiki
4. Access to wiki sites
  (1) View site list
    http://example.jp/ksuwiki/?cmd=site
  (2) View a site, for instance, site1 
    http://example.jp/ksuwiki/site/site1  

DIRECTORY/FILE LAYOUT

PKWK_ROOT
- index.php
- INSTALL.txt
- README.txt
- ...
- UPDATING.txt
+ assets/   
  + image/  
  + skin/   
  + snippet/    # for `snipet` plugin
+ config/
  - en.lang.php
  - ja.lang.php
  - default.ini.php
  - ksuwiki.ini.php   # NEW! 
  - pukiwiki.ini.php  # UPDATED: update constant definitions
+ lib/
  - auth.php    # UPDATED: enable site login
  - ...
  - init.php    # UPDATED: alter path to '*.ini.php', '*.lang.php' 
  - ksuwiki.php # NEW!
  - ...
  - pukiwiki.php  
+ wiki/
  + _template/  
    + attach/
    + backup/
    + cache/
    + counter/
    + diff/
    + wiki/
    + wiki.en/
    - .site.yaml
  + sites/  # NEW!
    + site1/
      + attach/
      + ...
      + wiki/
      + wiki.en/
      - .site.yaml
    + site2/
      + attach/
      + ...
      + wiki/
      + wiki.en/
      - .site.yaml

HOW DOES IT WORK?

A. Store all data for different sites in separate directories 
  A.1. Create 'sites' and '_template' directories under 'wiki/'
  A.2. Move original wiki site to 'wiki/_template/', used as template for creating new sites 
    attach/, backup/, cache/, counter/, diff/, wiki, wiki.en/
  A.3. Create a directory for each site (e.g., site1) under 'wiki/sites/' 　 
    attach/, backup/, cache/, counter/, diff/, wiki, wiki.en/
  A.4. Create a config file, named '.site.yaml' under site directory, (e.g., 'wiki/sites/site1/') 
    title: site's title
    skin: which skin to use
    admin: name of the administrator
    passwd: password for site administration, md5 hashed
    toppage: default page of the site
    readonly: is the site is readonly

    For example,
      title: 'Sample Site'
      skin: default
      admin: hoge
      passwd: '{x-php-md5}81dc9bdb52d04dc20036dbd8313ed055'
      toppage: FrontPage
      readonly: 0

B. Other Optimization
  (1) Move all static content to 'assets/' directory
  (2) Move all '*.ini.php' and '*.lang.php' to 'config' directory

C. New/updated PHP scripts and files for KsuWiki
  (1) New Contants
    PKWK_ROOT, WIKI_DIR, CONF_DIR,
    SITE_ID, SITE_TITLE, SITE_URL, SITE_ADMIN,
    PKWK_SKIN_SHOW_FOOTER (in 'pukiwiki.skin.php') 
  (2) DATA_HOME . 'index.php' (updated, add new definitions and require statement), 
     DATA_HOME . '.htaccess'(updated, add rewrite rules), 'composer.json'(new)
  (3) CONF_DIR. 'ksuwiki.ini.php'(new, for site initialization)
     CONF_DIR . 'pukiwiki.ini.php' (updated, add site-related definition)
  (4) LIB . 'ksuwiki.php'(new, functions specially implemented for KsuWiki)
  (5) LIB . 'auth.php' (updated, allow site login)
  (6) PLUGIN_DIR . 'site.ini.php' (new plugin for site administration)
    PLUGIN_DIR . 'snippet.inc.php' (new plugin for code syntax-highlighting)
    PLUGIN_DIR . 'timed.inc.php' (new plugin for scheduled page access)
  (7) New skins
     DATA_HOME . 'skin/' . [default | ksu | modern | blue-box | orange-box]

C. Dependencies
 (1) symfony/yaml
 (2) bramus/router

D. Site administration
  A new plugin named 'site' is provided for site administration.
  --------------------------------------------------------------------  
  URL                                 | Comment
  --------------------------------------------------------------------  
  ?cmd=site                           | list all sites 
  ?cmd=site&act=new                   | create a new site from template
  ?cmd=site&act=copy&site_id=site1    | create a new site from site1 
  ?cmd=site&act=delete&site_id=site1  | delete site1
  ?cmd=site&act=setup&site_id=site1   | modify config of site1
  ?cmd=site&act=passwd&site_id=site1  | change password of site1
  ?cmd=site&act=login                 | login as administrator
  ?cmd=site&act=logout                | logout as administrator
