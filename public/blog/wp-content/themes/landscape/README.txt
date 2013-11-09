Landscape WordPress Theme, Copyright 2013 BlankThemes.com
===

Landscape is a responsive, single column, WordPress theme for personal blogs.

Landscape,  like WordPress, is licensed under the GPL.
Landscape is based on Underscores http://underscores.me/, (C) 2012-2013 Automattic, Inc.

Header image by Ansel Adams and is licensed under the Public Domain:
http://commons.wikimedia.org/wiki/File:Adams_The_Tetons_and_the_Snake_River.jpg



Theme FAQs:
===

Q: MY SITE TITLE OR DESCRIPTION DOES NOT APPEAR ON SINGLE POSTS WITH FEATURED IMAGES:

A: If a post has a featured image, the site title and description will not be displayed. If this is unacceptable for your project, you can adjust this by doing the following:

NOTE: Always make a backup of your theme before you make any changes.

Find the custom-header.php file located in the 'inc' directory. Open the custom-header.php file using a text editor of your choice. Around line 205, find the following and delete it.

#masthead hgroup{
	display:none;
}

We use the above as the site title/description might get in the way of a featured image. If a post does not have a featured image, the site title/description will be visible.

--

Q: HOW DO I SETUP THE HOMEPAGE LIKE IN THE MAIN LANDSCAPE THEME DEMO ON BLANKTHEMES.COM?

A: Create a page called 'Home'. Choose 'Homepage Template' as the page template. For your content, we recommend a single paragraph describing your site. Publish your new page that you created. 

Then create a page called 'Blog'. No content or page templates needed. Just the title and publish the page.

Then go to your Settings > Reading > Select 'Static Page' and choose your 'Home' page as the Front page and choose your 'Blog' page as your Posts page.

Then go to Appearance > Widgets and add widgets to your homepage widgets. We used Text Widgets on the main demo on BlankThemes.com.


===
Need further support? Visit:
http://blankthemes.com/contact/



Changelog:
===

V.1.0.3
===
*Fixed Google Chrome menu issue, where last menu item spacing would be off.
*Added support for Infinite Scroll provided by the JetPack plugin.
*Removed fixed custom header image.
*Removed Google Web Font: Droid Serif. 

V.1.0.1
===
*Fixed minor stylesheet errors.

V.1.0
===
*initial release. 
