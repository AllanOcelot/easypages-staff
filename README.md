#Easypages - Staff / Meet the team.
>Easypages Staff is a plugin for Wordpress that allows you to quickly and effortlessly add a "Staff" section to any existing Wordpress site.
>The plugin creates a custom post type behind the scenes and allows you to add staff members via the Wordpress admin menu.

<hr>

![Example of the plugin in action](http://i.imgur.com/dpkzZN7.png)


#How to install:
Simply download the plugin and place the files into your Wordpress plugin folder. Go to the Wordpress plugins page and enable the plugin.

#Adding Staff:
Simply go to the "Staff" option in the sidebar and add a Staff member the same way you would a normal post.
![Adding a new staff member](http://i.imgur.com/0CeGNu1.png)

#Staff Options:
The following can be edited on the individual staff member's edit page.

 - Title:
    The title should be the Staff member's full name.
 - Content:
    The content will appear on the single staff profile page (If enabled)
 - Excerpt:
    The excerpt is used to display the a short summary about the staff member, without the staff pages may look bare.
 - Featured Image:
    The features image should be a profile photograph of the staff member.
 - Job Role:
    The staff member's job role at the company
 - Banner Image:
    If provided the banner image will be placed at the top of the page on the single staff member page.

#Shortcodes and Shortcode options:
 The following are shortcodes, along with their options:

 ```
  [easyStaff]
 ```
 This is the main shortcode and will query all existing staff members and output them with our HTML. You can give the short code the following options.

 ```
   //How many staff members should there be on a single row? (Takes INT value. 1-4. Default: 4.)
   staff_per_row =  4

   //Should the staff members name link to their post type? (Takes boolean value: true / false. Default: true.)
   staffname_is_link = true

   //Should the staff member's roles be visible (Takes boolean value: true / false. Default: true.)
   rolesEnabled = true

   //If you wish to order the way the staff are output.  
   //Display new staff first.
   staff_order = "new"

   //Display old staff first.
   staff_order = "old"

   //Display staff in random order.
   staff_order = "random"

   //Display link to the staff members profile page as a button? (Takes boolean value: true / false. Default: true.)
   staffname_button_visible = true
   //If so, what text should it have? (Takes a string. Default: "Read More")
   staffname_button_text  = "Read More"

 ```

#Single Staff Page?
The plugin is built around Wordpress's fantastic custom post type structure, meaning each staff member can have their own profile page (Enabled by default).
This page is useful if you wish to give a history of the employee, or conduct an "interview" style biography. Each profile contains a Banner image, a content block and with other staff members being displayed at the bottom of the page.
![Single page 'Related' Staff](http://i.imgur.com/7jqVihO.png)



#Notes:
 - Current version 0.7alpha. May be bugs. Mostly missing advanced features I would like to add.
 - Post Type name is "staff_members" , incase you wish to query it.
