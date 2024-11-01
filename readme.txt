=== XQ Secure Form ===
Contributors: xqmsgdev
Tags: encryption,encrypt,encrypted,zero trust,security,otp,cryptography,secret,secrecy,privacy,private
Requires at least: 5.7.2
Requires PHP: 7.4
Tested up to: 5.8
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

XQ Secure Forms ensures that every piece of client information is secure, from submission to receipt. Transform your existing forms and collect secure, compliant data emailed straight to you.

[youtube https://www.youtube.com/watch?v=-w79Gh8x8ig ] 


Protect and own your customer data. Show your clients you value their privacy.


## How It Works
XQ Secure Forms is easy to set up and is the most hassle-free solution to safely in-take client information. XQ Secure Forms works by installing a few lines of code on your website. We’ll walk you through a painless set up.


When a client, a patient, a customer, or a prospect fills out your form, a unique encryption key is generated and the form data on your website is encrypted  in the client browser. The encrypted data is then delivered to you and is never stored by XQ. 

This is **end-to-end encryption**.

XQ never stores your data and ensures that only authorized users can decrypt the data of your choice, keeping your client data safe from prying eyes. 



### Stay Compliant
* Client data is always encrypted from your client’s browser, keeping you HIPAA and GDPR compliant.  
### Tracking
* XQ tracking lets you monitor when and where client data is decrypted and ensures that only authorized users have access. We log it all and make it available to you.
### It’s Easy
* XQ works with your existing forms – no need to rebuild everything. XQ automatically finds the forms on your site, encrypts them, and sends you the submissions in email. 


Secure Form submissions will decrypt with the click of a button. If you want to decrypt directly in your email, download one of our XQ Message email clients: 

* [Outlook](https://appsource.microsoft.com/en-us/product/office/WA200000090)
* [Gmail](https://chrome.google.com/webstore/detail/xq-secure-gmail/fldhbblbhbmilgnkklhlfablfiahlhke)
* [G-Suite](https://gsuite.google.com/marketplace/app/xq_secure_email/293580994869)


All non-commercial users get 1000 free submissions per month. If you love Secure Forms and are going over that limit, we'd love to talk to you! Please [drop us a line](https://xqmsg.co/contact-us). 

XQ Message has a versatile toolkit of applications and services all centered around Zero Trust. Please [visit our site](https://xqmsg.co/) to gain more insight into the comprehensive range of capabilities offered by XQ Message.


== Installation ==

First, follow the usual steps:

1. Open WordPress admin, go to Plugins, click Add New
2. Enter "xq secure form" in search and hit Enter
3. Plugin will show up as the first on the list; click "Install Now"
4. Activate & open plugin's settings page located under the Settings menu

Or if needed, upload manually;

1. Download the latest stable version from from [downloads.wordpress.org/plugin/xq-secure-form.latest-stable.zip](https://downloads.wordpress.org/plugin/xq-secure-form.latest-stable.zip)
2. Unzip it and upload to _/wp-content/plugins/_
3. Open WordPress admin - Plugins and click "Activate" next to "XQ Secure Form"
4. Open plugin's admin page located under the Settings menu and you will see the initial authentication screen

   We will now walk you through the screens you will encounter when you install XQ Secure Form software.

  **Screen 1: Authentication**
    * Enter your email address in order to register with XQ Message 

  **Screen 2: Validation**
    * Enter the 6 digit **PIN** number that was sent to your email account.
    * **Let XQ send me Emails**  This is checked by default.
      This means that the underlying form action attribute is pointing to an endpoint on our system so that we can handle the email delivery of your forms to the email address you used to sign up. But don't worry, they are encrypted! Only you and any recipients you specified can decrypt them. 
      The XQ Plugin supports html form assembly via the [CoBlocks](https://wordpress.org/plugins/gutenberg/) form builder that comes with your Wordpress default installation. You can also create your forms by hand. 
    * You may not want the encrypted data to flow through our system and instead would prefer to handle the data flow yourself. In that case please uncheck this item and take a look at any of our [APIs](https://github.com/xqmsg) of the ecosystem built around [Zero Trust](https://en.wikipedia.org/wiki/Zero_trust_security_model) that is available to you when using the XQ Message API.
     Once you choose to go your own route you also will have to write your own mapper function in order to format the data according to your needs. More on this below. 
    * Enter the email addresses of the people you want to be authorized to decrypt your form data.
    * Enter the URL of a "thank you" or "goodbye" page to which you would like to redirect users after successful form submission.


  **Screen 3: Confirmation**
    This page confirms that your API key was generated successfully so that your website can   
    now communicate with XQ Message.

   **Screen 4: Clients**
    This screen concludes the installation process and contains useful Links to XQ Message 
    email clients which can be used to make decrypting the forms content simpler.

== Frequently Asked Questions ==

 = What do I need to do to use XQ Secure Form straight out of the box? =
Just install the plugin and go through the set-up steps: confirm your account and add authorized recipients. We do the rest.  

 = What do I do if I don't want SecureForms data emailed to me? =
If you want to integrate your Secure Form with an external system, you can follow the instructions [here](https://github.com/XQ-Message-Inc/secureforms#take-full-control-using-our-api).

= What if I need to remove an authorized user from the plugin? = 
To remove an authorized user, just return to the XQ Secure Forms page in your Wordpress settings backend. From here, you can easily remove the authorized user so they will no longer receive the form data. 

  ***On The Client***

   First off, when going through the process of generating a plugin designed to communicate with your own endpoint make sure the following is true:

   * `Let XQ send me Emails` is unchecked
   * You selected one of the available output formats, i.e. either `Text`, `Json`, `XML` or `CSV`.

Upon submitting your secure form the plugin gets to work. 

  1) It will collect all form elements under the **`<form>`** tag, extract their names and values and create the payload,  
      a string of key value pairs.  

  2) A quantum key is generated and in order to encrypt that payload.   

  3) The key will be uploaded to our servers and token is generated.  
      The token will be neccessary later to download the encryption key.  

  4) The payload to be posted to the endpoint, 
      which you specified `<form action="your-enpoint">` using the format:  

  *`"payload": {"token": <the-token>","data": "<the-encrypted-data>"} `*

  ***On The Server***

 *1 Authorization*
   Before you can communicate with XQ you need to validate your identity using the email with which you registered at XQ Message during the plugin intallation process.

*`call Authorize() with <your-email>`*

 *2 Validation*
   An email containing a validation PIN will be sent to that account. Send that PIN back to us using the CodeValidator class.

*`call CodeValidator() with <pin-sent-to-your-email-account>`*

 *3 Decryption*
   Just pass payload, i.e the encrypted data and the token to the Decrypt( ) function and you are done. 
   
   "Why pass the token?" you may ask. This is needed to find your particular encyption key on our servers. Remember that this very token was returned on the client during the form submission process after successful upload of the encryption key to the server.

*`call Decrypt() with <token> and <data>`*

 = How do I find out more detail about the APIs ? =
To learn more about the full power of our any of our APIs by clicking  [here](https://github.com/xqmsg)


== Screenshots ==
1. Authentication Screen 
2. Validation Screen 
3. Installation Confirmation Screen
4. Our Email Clients Available for download

== Changelog ==

= 1.0.0 =
* Initial Release
= 1.0.1 =
* Change Delivery Format if default action is disabled
* Store Plugin Settings in WordPress database
= 1.0.2 =
* Redirect to login screen if key expired
= 1.0.3 =
* url changes
= 1.0.4 =
* format text with html
* basic support for ActiveDEMANT form html builder
* improved support for CoBlocks form html builder
= 1.0.5 =
* added action parameter back
= 1.0.6 =
* support for multiple API Keys per dashboard user
= 1.0.7 =
* Bug fix: changing website name from Word Press Admin did not properly delete the key under the old name.
= 1.0.8 =
* Asset changes
= 1.0.9 =
* Added video
= 1.1.0 =
* Added more assets
= 1.1.1 =
* version support for xq.js
= 1.1.2 =
* version changed to  v1
= 1.1.3 =
* reverted content to that of 1.1.0