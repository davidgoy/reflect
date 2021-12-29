<p align="center">
  <img src="https://github.com/davidgoy/reflect/blob/master/logo.png" width="500" alt="Reflect logo">
</p>

<h3 align="center">STATIC SITE GENERATOR FOR WORDPRESS</h3>
<br>
<p align="center">
 <a href="https://github.com/davidgoy/reflect/raw/master/reflect-app/deploy/reflect-app.zip" target="_blank"><img src="https://github.com/davidgoy/reflect/blob/master/download.png" width="150" alt="Download Reflect"></a>
</p>
<br>
<br>
<br>
<p align="center">Latest version 1.0.0-beta.16<br>(released 29 December 2021)</p>
<br>
<br>

## TABLE OF CONTENTS

- [About](#about)

- [Requirements](#requirements)

- [Installation](#installation)

- [Usage](#usage)

- [Hardening](#hardening)

- [Updating](#updating)

- [Acknowledgements](#acknowledgements)

- [Feedback](#feedback)

<span><br><br></span>

## ABOUT

### What is Reflect

Reflect is an app that you can use in conjunction with your WordPress site to create a secure, fast-loading static site.

Create and publish pages/posts using the popular WordPress CMS. Then have Reflect generate those pages/posts into static pages and served upon HTTP request.

(**Note:** This arrangement is known as a decoupled architecture, where WordPress functions as a headless CMS (i.e. back-end) and your Reflect static site functions as the front-end.) 

<span><br><br></span>

### How does Reflect work

Simply upload and host your Reflect static site on one domain and your WordPress site on another domain.

Then use Reflect to fetch content from your WordPress site and convert them into static pages.

Reflect will automatically serve those static pages upon HTTP request (i.e. when someone visits your Reflect site).

<span><br><br></span>

### Why use Reflect

#### Greatly enhanced security

Since you're using WordPress purely for content authoring, you can firewall the entire CMS for internal access only while leaving your Reflect static site publicly accessible.

Unlike your WordPress site, your static site contains no database or user accounts. Consequently it has a tiny attack surface, not to mention there's no valuable loot to steal even if the server hosting it is compromised.

#### Faster page loads

Reflect can serve the generated static pages much faster than a regular WordPress site could using server-side rendering.

<span><br><br></span>

## REQUIREMENTS

### Your Reflect static site

1. Your Reflect static site should be hosted on a separate domain to your WordPress site.
   
   (**Note:** For maximum security, you can even host your Reflect static site on a separate server to your WordPress site).

2. Your web server must support the use of ***.htaccess*** 

3. PHP 7.x or later.

<br>

### Your WordPress site

WordPress 5.x or later.

<span><br><br></span>

## INSTALLATION

### How to install your Reflect static site

1. [Download the Reflect App ZIP file here](https://github.com/davidgoy/reflect/raw/master/reflect-app/deploy/reflect-app.zip). Extract the ZIP file and you will find two folders:
   
   - `reflect`
   
   - `public_html`

2. Upload everything inside `public_html` into your server's document root (i.e. web root) folder.
   
   > **Tip:** 
   > 
   > It may be a good idea to ensure that your Reflect site content is served... 
   > 
   > - only from *https* rather than *http*
   > 
   > - either with or without the *www* prefix (and not both) 
   > 
   > If you know a bit of *htaccess*, you can edit this [sample *htaccess* file](https://raw.githubusercontent.com/davidgoy/reflect/master/reflect-app/deploy/sample-reflect.htaccess). Then simply replace the existing *htaccess* file in the `public_html` folder with the new one. 

3. Upload the `reflect` folder to your server so that it sits outside of the document root. Your web server's directory structure should look something like this:
   
   ```text
   (your domain name)/
   ├─ reflect/
   └─ (your document root)/ 
   ```

4. On your web browser, navigate to the URL of your Reflect site `https://(your domain name)` to begin the setup process.  **Don't forget to save the settings!**
   
   > **Attention:** 
   > 
   > Reflect will only fetch WordPress page/post content as well as primary and footer menu items. Widget components (e.g. Archives, RSS, Tag Cloud, etc.) and theme-specific layout sections (e.g. Sidebar) will not be fetched.

<br>

### How to set up your WordPress site to work with Reflect

1. The [WP Reflect Support plugin](https://github.com/davidgoy/reflect/raw/master/wp-reflect-support-plugin/deploy/wpreflect.zip) should be installed and activated to enable your WordPress site to work with your Reflect site. Please manually [download the plugin here](https://github.com/davidgoy/reflect/raw/master/wp-reflect-support-plugin/deploy/wpreflect.zip) and upload it to your WordPress site.

2. ***Post name*** should be selected as the permalink structure:
   
   ![](https://github.com/davidgoy/reflect/blob/master/installation-1.png)

3. Have at least two pages - a ***Home* page** (i.e. front page) and a ***Posts* page** (i.e. blog page).

4. For the ***Reading Settings***, ensure that ***A static page*** option is selected:
   
   ![](https://github.com/davidgoy/reflect/blob/master/installation-2.png)

5. Up to two menus are supported - a primary menu and a footer menu.

6. When creating page or post content, you should use the default block editor that comes with WordPress. Reflect does not support third party page builders.

<span><br><br></span>

## USAGE

### How to create and publish content

For the sake of those who are unfamiliar with the concept of decoupled architecture, content creation (i.e. authoring pages/posts and menus) and publishing are done on your WordPress site. 

(**Note:** Please use WordPress' default block editor when creating pages/posts. Reflect does not support third party page builders.)

Your Reflect site can then fetch this content, convert them into static pages and serve them upon HTTP request.

<br>

### How to change Reflect site settings

You can access your Reflect site's ***Settings* page** anytime by navigating to the following URL:

`https://(your domain name)/reflect-settings`

<br>

### How to access Reflect's ***Static Files Manager*** (for static page generation, etc.)

By default, your Reflect site will dynamically render (on the server-side) the content it fetches from your WordPress site.

Once you have published your content on your WordPress site, you can now use Reflect's ***Static Files Manager*** to pre-generate the static pages.

To access the ***Static Files Manager* page**, navigate to:

`https://(your domain name)/reflect-sfm`

Remember to turn on ***Static Mode*** so that your Reflect site will serve the pre-generated static pages instead of fetching content dynamically from your WordPress site.

<span><br><br></span>

### How to change the look and feel of your Reflect site's theme

The look and feel of your Reflect site is controled by its theme. Each theme also has its own settings. You can access the settings of an active theme by navigating to:

`https://(your domain name)/reflect-settings/themes/(name of theme)`

Reflect comes with a default theme built on Bootstrap which is creatively named... *Default*. Therefore to access this theme's settings, go to:

`https://(your domain name)/reflect-settings/themes/default`

(**Note:** Although you may have multiple themes, only one theme can be active at a time.)

<br>

### How to further customise the design of your Reflect site

The *Default* theme has a few properties which you can change via its settings page. 

However, if you need a more extensive design change, then you can customise the theme on the code level.

Some knowledge of HTML and CSS will be required (the *Default* theme is based on Bootstrap). Understanding of PHP is optional but would be advantageous.

If this is what you wish to do, then it is recommended that you duplicate the *Default* theme and give it a custom name. Here's where theme folders are located within Reflect:

```text
   (your domain name)/
   ├─ reflect/
      ├─ themes/
         ├─ default/
         └─ (name of your custom theme)/
   └─ (document root)      
```

You can then access your custom theme's settings page by navigating to:

`https://(your domain name)/reflect-settings/themes/(name of your custom theme)`

Of course, don't forget to go to Reflect's *Settings* page and set your site to use the new theme.

<br>

### How to make forms automatically submitable on your Reflect site

Reflect comes with an addon called *Reflect Form Mailer*.

With this addon enabled, simply drop a plain Bootstrap form into a page or post on your WordPress site, and the form will become automatically submitable when it is rendered on your Reflect site.

That means when a user submits the form on your Reflect site, the form data will be emailed to you.

You can enable any addons on Reflect's ***Settings* page** (accessible by navigating to `https://(your domain name)/reflect-settings`).

Assuming that you have already enabled the addon, you should now configure it by going to: 

`https://(your domain name)/reflect-settings/addons/reflect-form-mailer`

> **Tips:** 
> 
> You can use Chris Youderian's drag-and-drop [Bootstrap Form Builder](https://bootstrapformbuilder.com/) which allows you to visually put together a Bootstrap form and then generate the corresponding HTML code. You can then simply chuck the HTML code into your WordPress page or post.

<br>

## HARDENING

### How to deny public access to your WordPress site

One of the key advantages of a decoupled architecture is that it allows the front-end of a website to be separated from its back-end.

Since the general public only interacts with the front-end (i.e. your Reflect site) and never directly with the back-end app (i.e. your WordPress site), you should **deny all access to your WordPress site except requests coming from**:

- The server hosting your Reflect site

- Main admin users (e.g. you)

- Other admin users you approve (e.g. content authors)

You can easily accomplish this with a basic understanding of *htaccess*. You will also need the following information:

- The primary IP address of the server that hosts your Reflect site (simply ping your server's hostname to get its IP address)

- Your public IP address (ideally you should have a static IP)

[Download this sample *htaccess* file](https://raw.githubusercontent.com/davidgoy/reflect/master/wp-reflect-support-plugin/deploy/sample-wordpress.htaccess) and replace the dummy IP addresses with the real ones as per above. Rename the file to *.htaccess*, then upload it to the directory where your WordPress site is installed. 

> **Warning:** 
> 
> This will replace the original *htaccess* file created by WordPress, so back up the original file first!

### How to enhance security even further

You can also host your WordPress site and your Reflect site on a separate server.

This means in the extremely, unlikely event that a hacker manages to compromise your Reflect site and break into the server, your WordPress site is not affected.

<span><br><br></span>

## UPDATING

Starting from version 1.0.0-beta.7, Reflect has an auto-update feature. This feature allows you to update to the latest version of Reflect at the click of a button (located in Reflect's *Settings* page).

(**Note:** Updating Reflect will also automatically update all the themes and addons that are officially bundled with Reflect.)

<span><br><br></span>

## ACKNOWLEDGEMENTS

The following are used in the Reflect project:

- [Apache Server Configs](https://github.com/h5bp/server-configs-apache)

- [Babel](https://babeljs.io/)

- [Bootstrap](https://getbootstrap.com/)

- [Composer](https://getcomposer.org/)

- [core-js](https://github.com/zloirock/core-js)

- [PclZip](https://www.phpconcept.net/)

- [Pickr](https://github.com/Simonwep/pickr)

- [RandomLib](https://github.com/ircmaxell/RandomLib)

- [regenerator-runtime](https://github.com/facebook/regenerator/blob/master/packages/regenerator-runtime/runtime.js)

- [SweetAlert2](https://sweetalert2.github.io/)

- [Transactional Email Templates](https://github.com/mailgun/transactional-email-templates)

- [underscores](https://underscores.me/)

- [unfetch](https://github.com/developit/unfetch)

- [WordPress](https://wordpress.org/)

- [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)  

<span><br><br></span>

## FEEDBACK

Got questions, suggestions or feature requests? [Get in touch with me](https://davidgoy.dev).
