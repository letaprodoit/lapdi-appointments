# Appointments for CS-Cart
-------
Appointments for CS-Cart software allows users to order services that require an appointment (ie Therapy Session, Spa Appointments, etc) and allow admins to create product/appointments for purchase (ie Webinars, Training, etc). Administrator can manage appointments and notify customers of appointment changes via email.

## FEATURES

* Users can schedule an appointment for service based products
* Admins can schedule meeting times for service based products such as meetings and webinars
* Admins can mange existing appointments (reschedule, cancel, delete)
* Admins can notify customers of appoiintment changes.

For up-to-date installation and setup notes, visit the FAQ:
[http://lab.thesoftwarepeople.com/tracker/wiki/Cscart-apa:MainPage](http://lab.thesoftwarepeople.com/tracker/wiki/Cscart-apa:MainPage)


## GENERAL INSTALLATION NOTES

* Download from repository
* Unzip the zip file in the directory where CS-Cart runs
* If the zip creates a new directory called `tsp-appointments` you will need to run the install script, else you are done
* If `tsp-appointments` folder was created by the zip, Navigate to the folder. Update the `$target_loc` and `$theme_folder_name` in the install.php and run its. Command: php install.php
* Open CS-Cart Administration Control Panel
* Navigate to Addons -> Manage Addons
* Find the "The Software People: Appointments" addon and click "Install" (If you don't see it make sure "All Stores" is selected at the top of the screen)
* After Install, from the Addons listing click on Settings for "The Software People: Appointments"
* Update The Appointment settings

## USING THE ADDON

The Appointments module, upon install, adds product global options to the database and adds settings to products.

The Products->Global Options that are added include:

* Appointment Date
* Appointment Time
* Appointment Duration
* Appointment Location
* Appointment Additional Information

Each product has settings that can be turned on if the admin wishes to sell an appointment as a product. 

The Product Listing -> Addon tab includes:

* Appointment Date
* Appointment Time
* Appointment Duration
* Appointment Location
* Appointment Additional Information

### Creating an Appointment Service (ie Training, Class, Therapy Session, Spa Appointments, etc)

In order to create a product that is an appointment service you will need to perform the following steps:

* Create the product and save (after save the Options tab will be available to you.
* Navigate to the Options tab and add the Global options above to the product by clicking on "Add Global Option"
** Add Appointment Date (Apply as Link).
** Add Appointment Time (Apply as Link).
** Add Appointment Duration (Apply as Link).
** Add Appointment Location (Apply as Link).
** Add Appointment Additional Information (Apply as Link).
* Save and close the product
* And thats it! Now you can allow your customers to purchase products that are appointment services.

#### Managing Existing Appointments

Any appointment the user creates they will be able to see on their order receipt and the admin can view and manage appointments by visiting Customer->Appointments in the admin panel.

### Creating an Appointment Event (ie Webinars, Demos, etc)

In order to create a product that is an event you will need to perform the following steps:

* Create the product and save (after save the Addons tab will be available to you.
* Navigate to the Addons tab and scroll to the section "The Software People: Appointments".
* Supply the event details and be sure to include instruction on how to prepare before the event.
* Save and close the product
* And thats it! Now you can allow your customers to purchase events.


## REPORTING ISSUES

Thank you for downloading Appointments for CS-Cart
If you find any issues, please report them in the issue tracker on our website:
[http://lab.thesoftwarepeople.com/tracker/cscart-apa](http://lab.thesoftwarepeople.com/tracker/cscart-apa)

## COPYRIGHT AND LICENSE

Copyright 2013 The Software People, LLC

Software is available under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License; additional terms may apply. See [http://creativecommons.org/licenses/by-nc-nd/3.0/](Terms of Use) for details.