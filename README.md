# Appointments (CS-Cart Addon) 1.0
-------
Appointments for CS-Cart software allows users to order services that require an appointment (ie Therapy Session, Spa Appointments, etc) and allow admins to create product/appointments for purchase (ie Webinars, Training, etc). Administrator can manage appointments and notify customers of appointment changes via email.

## FEATURES

* Users can schedule an appointment for service based products
* Admins can schedule meeting times for service based products such as meetings and webinars
* Admins can mange existing appointments (reschedule, cancel, delete)
* Admins can notify customers of appoiintment changes.

For up-to-date installation and setup notes, visit the FAQ:
[http://lab.thesoftwarepeople.com/tracker/wiki/cscart-apa-MainPage](http://lab.thesoftwarepeople.com/tracker/wiki/cscart-apa-MainPage)


## GENERAL INSTALLATION NOTES

* Download from repository
* Unzip the zip file
* Open addons/ folder and copy the tsp_the_bug_genie_for_cscart to [your cscart install dir]/addons/
* Open the basic/ folder and copy admin/, customer/ and mail/ folders to all the necessary skins INCLUDING basic
* Open CS-Cart Administration Control Panel
* Navigate to Settings-> Addons
* Find the "The Software People: Appointments" addon and click "Install"
* After Install, from the Addons listing click on Settings for "The Software People: Appointments"
* Update The Appointment settings

## USING THE MODULE

The Appointments module, upon install, adds product global options to the database and adds settings to products.

The Products->Global Options that are added include:

* Appointment Date
* Appointment Time
* Appointment Duration
* Appointment Location
* Appointment Additional Information

Each product has settings that can be turned on if the admin wishes to sell an appointment as a product. The settings include:

* Appointment Date
* Appointment Time
* Appointment Duration
* Appointment Location
* Appointment Additional Information

### Creating an Appointment Service (ie Therapy Session, Spa Appointments, etc)

In order to create a product that is an appointment service you will need to perform the following steps:

* Create the product and save (after save the Options tab will be available to you.
* Navigate to the Options tab and add the Global options above to the product by clicking on "Add Global Option" (you can change the field names and drop down options by clicking edit).
* Save and close the product
* And thats it! Now you can allow your customers to purchase products that are appointment services.

#### Managing Existing Appointments

Any appointment the user creates they will be able to see on their order receipt and the admin can view and manage appointments by visiting Customer->Appointments in the admin panel.

### Creating an Appointment Event (ie Webinars, Training, etc)

In order to create a product that is an event you will need to perform the following steps:

* Create the product and save (after save the Addons tab will be available to you.
* Navigate to the Addons tab and scroll to the section "The Software People: Appointments".
* Supply the event details and be sure to include instruction on how to prepare before the event.
* Save and close the product
* And thats it! Now you can allow your customers to purchase events.


## REPORTING ISSUES

Thank you for downloading Appointments for CS-Cart 1.0
If you find any issues, please report them in the issue tracker on our website:
[http://lab.thesoftwarepeople.com/tracker/cscart-apa](http://lab.thesoftwarepeople.com/tracker/cscart-apa)

## COPYRIGHT AND LICENSE

Copyright 2013 The Software People, LLC

Software is available under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License; additional terms may apply. See [http://creativecommons.org/licenses/by-nc-nd/3.0/](Terms of Use) for details.