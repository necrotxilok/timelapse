![Timelapse-Logo](http://timelapse-demo.ntkserver.com/public/img/timelapse-logo.png)

# Timelapse Web App

A full web application developed with PHP and HTML5 to manage the work time of the participants in a single project.

You can see a demo of the application here:

| [DEMO](http://timelapse-demo.ntkserver.com/) |
| -------------------------------------------- |
| **User**: user - **Pass**: user              |

Just login and save your everyday work progress ;)



## Screenshots

![Screenshot1](http://timelapse-demo.ntkserver.com/screenshots/screen01.png)

| ![Screenshot2](http://timelapse-demo.ntkserver.com/screenshots/screen02.png) | ![Screenshot3](http://timelapse-demo.ntkserver.com/screenshots/screen03.png) |
| ------------------------------------------------------------ | ------------------------------------------------------------ |
| ![Screenshot4](http://timelapse-demo.ntkserver.com/screenshots/screen04.png) | ![Screenshot5](http://timelapse-demo.ntkserver.com/screenshots/screen05.png) |
| ![Screenshot6](http://timelapse-demo.ntkserver.com/screenshots/screen06.png) | ![Screenshot7](http://timelapse-demo.ntkserver.com/screenshots/screen07.png) |



## Features

- Authorization system with **admin** and **basic** roles
- Dashboard with the overall timing of all users of the project
- Simple work time storage (Each user only have to select a day of work and a number of hours)
- Single Page Application (SPA) design to improve the performance
- API system to communicate with the server
- Automatic avatar based on username or Gravatar image linked with the user email
- Add/Edit/Remove/Deactivate users directly from the website (Only admin role)
- Users can customize his profiles and change the access password
- System log of all events updating data in the database (Only admin role)
- Simple database storage in independent files with autogeneration



## Installation

Download or clone this repository into your web server folder.

```
# git clone https://github.com/necrotxilok/timelapse.git
```

***Enjoy it!***

> First time you log into the application you can use the user '**admin**' and the pass '**admin**'.



## Configuration

All the project code is designed with the simplest methods and structures to easy implement new features or change the existing features.

### Database

The database is fully managed from php using SQLite 3. This allows you deploy your application in a simple server without configuring any database engine. All data files are stored in:

```
core/db/data/*.db
```

If any of this files are removed the application will regenerate the file next time it have to store some data. 

The tables and the fields are configured using a php array in the next file:

```
core/db/tables.php
```

To add default data into the tables in the database file creation we can use this file:

```
core/db/default.php
```

If we need to limit the information obtained from a table with a specific role we can use the file:

```
core/db/views.php
```

To add calculated information from data we can use the file:

```
core/db/parser.php
```

### API

The API is implemented under a basic file structure under **core/api** folder. Each folder into it represents a module in the application associated with a database file with the same name. Each php file into the module folder represents an action over that module (Login, get a list of users, update the timing in a day, etc). Then we can connect to the server API using this pattern:

```
http://appdomain.com/api/<module>/<action>
```

Which corresponds with the file:

```
core/api/<module>/<action>.php
```

### Debug

With DEBUG param activated each API call returns the SQL executed in the server. If we don't want to show this information to users we must disable this option.

```php
/* core/config.php */
define('DEBUG', 0);
```

### Sessions

If you need to install the application for multiple projects under the same domain you can change the name of the application to generate a new Cookie for each site:

```php
/* core/config.php */
define('APPNAME', 'NewProjectName');
```



## Customization

You can also modify the styles of the entire application easily to fit with your project.

![Custom Style 1](http://timelapse-demo.ntkserver.com/screenshots/custom01.png)

![Custom Style 2](http://timelapse-demo.ntkserver.com/screenshots/custom02.png)



## Vendors

The application was developed using a combination of the latest web technologies: php 7, SQLite 3, HTML5, UIKit 3 and jQuery.

![Vendor Logos](http://timelapse-demo.ntkserver.com/vendors/logos.png)



## Support this project

If you like this project share it or invite me to a beer [here](https://paypal.me/DChiloechesSuarez?locale.x=es_ES)



Developed by [necro_txilok](http://necrotxilok.github.io/) - 2018