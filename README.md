<p align="center">
  <img src="https://github.com/davidgoy/reflect/blob/master/logo.png" width="350" alt="Reflect logo">

</p>
<br>
<h3 align="center">INSTANT STATIC SITE FOR HEADLESS WORDPRESS CMS</h3>
<p align="center">
  The ultimate fusion of <i>static site generator</i> and <i>server-side renderer</i>, Reflect is the perfect front-end app for those wishing to use WordPress as a headless CMS. <b>Ready to deploy out-of-the-box.</b>
</p>
<br>
<br>

## TABLE OF CONTENTS

- [What is Reflect](#what-is-reflect)

- [Installation](#installation)

- [Usage](#usage)

- [Demo](#demo)

- [Acknowledgements](#acknowledgements)

- [Feedback](#feedback)

<span><br><br></span>

## WHAT IS REFLECT

Reflect is a front-end app developed to specifically pair with WordPress (which functions as the back-end) to create the ultimate decoupled architecture. 

Like WordPress, you can simply install Reflect on your server. No coding required. 

Once installed, Reflect will fetch content from your WordPress site, render them on the server-side, and serve them on http requests. 

You can also use Reflect to pre-fetch and pre-generate the content into static HTML files. Then simply turn on *static mode*, and your Reflect site will function like a static site.

<span><br><br></span>

## INSTALLATION

> #### Minimum requirements
> 
> - Two domain (or subdomain) names available for use... one for your WordPress site, and the other for your Reflect site.
> 
> - Apache HTTP web server (or a compatible alternative such as LiteSpeed) with PHP 7.0 or later installed. 
>   
>   **Optional:** For maximum security, you may host the front-end app (your Reflect site) on a separate server to the back-end app (your WordPress site).
> 
> - WordPress v5.0 or later installed. 
> 
> - WordPress content should be created using either the standard Classic or Gutenberg editor.

<br>

### Prepare your WordPress site for use with Reflect

On your WordPress site...

1. Go to ***Permalink Settings***, then select ***Post name*** as the permalink structure.
   ![](https://github.com/davidgoy/reflect/blob/master/installation-1.png)

2. Create a blank ***Home* page** (i.e. front page) and a blank ***Posts* page** (i.e. blog page) and immediately publish them (you can add the content later). Take note of the page slugs.

3. **Optional:** Create a blank ***Privacy Policy* page** and a blank ***Terms of Use* page**. Then immediately publish the pages (you can add the content later). Take note of the page slugs.

4. **Optional:** Create a blank ***Under Maintenance* page**. Then immediately publish the page (you can add the content later). Take note of the page slug.

5. Go to ***Reading Settings***. Under the heading titled ***Your homepage displays***, select the ***A static page*** option. Then assign the ***Home* page** to ***Homepage***. Also assign the ***Posts* page** to ***Posts page***.
   ![](https://github.com/davidgoy/reflect/blob/master/installation-2.png)

6. Reflect currently supports two menu locations: a primary menu and a footer menu. So go to ***Menus*** and proceed to create a new menu called ***Primary Menu*** and another menu called ***Footer Menu***. After that...
   
   - Assign the ***Home*** and ***Posts*** pages to the ***Primary Menu***.
   
   - Assign the ***Privacy Policy*** and the ***Terms of Use*** pages (if you created them earlier) to the ***Footer Menu***.
   
   **Note:** Do not assign the ***Under Maintenance*** page to any menu.

7. Download the [WP Reflect Support plugin file](https://github.com/davidgoy/reflect/raw/master/WP-Reflect-Support-Plugin/deploy/wpreflect.zip). Then install the plugin by uploading the file using WordPress'  ***Upload Plugin*** button. Proceed to activate the plugin on WordPress.

<br>

### Install and setup your Reflect site

For illustration purpose, let's pretend that your Reflect site will be using the domain name `example.com`.

1. Download and extract the [Reflect App package](https://github.com/davidgoy/reflect/raw/master/Reflect-App/deploy/reflect-app.zip). You will find two folders:
   
   - `reflect`
   
   - `public_html`

2. Upload the content of `public_html` into the root directory of your web server (also known as the *document root*).
   
   > **Note:** This may sound obvious, but please upload the folder's CONTENT ONLY, and not the entire `public_html`folder itself! 
   
   Next, upload the entire `reflect` folder to a location just outside of the document root. Your web server's directory structure should look something like this:
   
   ```text
   example.com/
   ├─ reflect/
   └─ (document root)/ 
   ```

3. On your web browser, navigate to the URL of your Reflect site (e.g. `https://example.com`) to begin the setup process. 
   **Don't forget to save the settings!**

4. Navigate to the URL of your Reflect site again. This time, you should see content from your WordPress site mirrored onto your Reflect site. 
   
   > **Note:** Reflect will only mirror WordPress page/post content as well as primary and footer menu items. Widget components (e.g. Archives, RSS, Tag Cloud, etc.) and theme-specific layout sections (e.g. Sidebar) will not be mirrored.

<br>

### Deny public access to your WordPress site

One of the key advantages of a decoupled architecture is that it allows the front-end of a website to be separated from its back-end.

Since the general public only interacts with the front-end app (your Reflect site) and never directly with the back-end app (your WordPress site), you should **deny all access to your WordPress site except requests coming from**:

- Your Reflect site

- Admin users (e.g. you)

- Optional: Users you approve (e.g. content authors)

You can easily accomplish this with a basic understanding of *htaccess*. You will also need the following information:

- The IP address of the server that hosts your Reflect site

- Your public IP address (ideally you should have a static IP)

- Optional: IP addresses of users you approve

[Download this sample *htaccess* file](https://raw.githubusercontent.com/davidgoy/reflect/master/WP-Reflect-Support-Plugin/deploy/sample-wordpress.htaccess) and replace the dummy IP addresses with the real ones as per above. Rename the file to *.htaccess*, then upload it to the directory where your WordPress site is installed. 

> **Warning:** This will replace the original *htaccess* file created by WordPress, so back up the original file first!

<span><br><br></span>

## USAGE

### Authoring and publishing content (*hint...* *you don't use Reflect for that*)

This may be obvious to some, but for the sake of those who are unfamiliar with using a decoupled system, content creation and publishing is done on the back-end app. In this case, this is your WordPress site.

After you have set up both your Reflect site and WordPress site, you can now use WordPress' Gutenberg or classic editors as per normal to create and publish content.

Your Reflect site will mirror the content of any published (but not draft or private) pages or posts.

<br>

### Accessing Reflect site settings

You can access your Reflect site's ***Settings* page** anytime by navigating to the following URL:

`example.com/reflect-settings`

<br>

### Accessing Reflect theme settings

The look and feel of your Reflect site is controled by its theme. Each theme also has its own settings separate from the core app. 

Although you may have multiple themes, only one theme can be active at a time. You can access the settings of an active theme by navigating to:

`example.com/reflect-settings/themes/(name of theme)`

Reflect comes with a default theme which is creatively named... *Default*. Therefore to access its settings, go to:

`example.com/reflect-settings/themes/default`

<br>

### Customising the look and feel of your Reflect site

The *Default* theme has a few properties which you can change via its settings page. 

However, if you need a more extensive design change, then you can customise the theme on the code level.

Some knowledge of HTML and CSS will be required (the *Default* theme is based on Bootstrap). Understanding of PHP would be advantageous.

If this is what you wish to do, then it is recommended that you duplicate the *Default* theme instead of modifying it directly. Here's where themes are located within Reflect:

```text
   example.com/
   ├─ reflect/
      ├─ themes/
         ├─ default/
         └─ (name of your custom theme)/
```

You can then access your custom theme's settings page by navigating to:

`example.com/reflect-settings/themes/(name of your custom theme)`

Of course, don't forget to set your Reflect site to use your new theme.

<br>

### Making Bootstrap forms automatically submitable on your Reflect site

#### Using *Reflect Form Mailer* addon

Reflect comes with an addon called *Reflect Form Mailer* which you can enable on Reflect's ***Settings* page** (`example.com/reflect-settings`).

> **Note:** With this addon enabled, simply drop a plain Bootstrap form into a page or post on your WordPress site, and the form will become automatically submitable when it is rendered on your Reflect site.
> 
> So when a user submits the form on your Reflect site, the form data will be emailed to you. 

Assuming that you have already enabled the addon, you should now configure it by going to: 

`example.com/reflect-settings/addons/reflect-form-mailer`

> **Tips:** You can use Chris Youderian's drag-and-drop [Bootstrap Form Builder](https://bootstrapformbuilder.com/) which allows you to visually put together a Bootstrap form and then generate the corresponding HTML code. You can then simply chuck the HTML code into your WordPress page or post.

<br>

### Turn Reflect into a static site

By default, your Reflect site will render (on the server-side) the content it fetches from your WordPress site and serve them on the fly.

However, Reflect can also be set to behave like a static site. You can use Reflect's ***Static Files Manager*** feature to pre-generate the static pages.

To access the ***Static Files Manager* page**, navigate to:

`example.com/reflect-sfm`

Don't forget to turn on ***Static Mode***!

<span><br><br></span>

## DEMO

Check out our [demo Reflect site](https://examplereflect.com/). 

Also, take a look at the [example WordPress site](https://examplecms.com/) that Reflect fetches its content from. 

> **Note:** As mentioned earlier, it is recommended that you prevent your WordPress site (i.e. your back-end app) from being directly accessible by the general public. However, for the purpose of demonstration, we allow our example WordPress site here to be accessible so you can compare the content mirrored on the Reflect site. 

<span><br><br></span>

## ACKNOWLEDGEMENTS

The following components are used in the Reflect project:

- [Apache Server Configs](https://github.com/h5bp/server-configs-apache)

- [Babel](https://babeljs.io/)

- [Bootstrap](https://getbootstrap.com/)

- [Composer](https://getcomposer.org/)

- [core-js](https://github.com/zloirock/core-js)

- [RandomLib](https://github.com/ircmaxell/RandomLib)

- [regenerator-runtime](https://github.com/facebook/regenerator/blob/master/packages/regenerator-runtime/runtime.js)

- [sweetalert2](https://sweetalert2.github.io/)

- [Transactional Email Templates](https://github.com/mailgun/transactional-email-templates)

- [underscores](https://underscores.me/)

- [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)  

<span><br><br></span>

## FEEDBACK

Got questions, suggestions or feature requests? [Get in touch with me](https://davidgoy.github.io/).
