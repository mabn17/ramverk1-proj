# Ramverk1 Examination Project

[![Build Status](https://travis-ci.org/mabn17/ramverk1-proj.svg?branch=master)](https://travis-ci.org/mabn17/ramverk1-proj) [![Build Status](https://scrutinizer-ci.com/g/mabn17/ramverk1-proj/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mabn17/ramverk1-proj/build-status/master) [![Code Intelligence Status](https://scrutinizer-ci.com/g/mabn17/ramverk1-proj/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mabn17/ramverk1-proj/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mabn17/ramverk1-proj/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/3cdafdb870523ae34e24/maintainability)](https://codeclimate.com/github/mabn17/ramverk1-proj/maintainability) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/9122f95f443c4717b9a202d8e5c3d57f)](https://www.codacy.com/app/mabn17/ramverk1-proj?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mabn17/ramverk1-proj&amp;utm_campaign=Badge_Grade)

This is my own version of a stackoverflow ish webbsite where the main focus is forum posts, user login/registration and comments. Check the links at the bottom of the page for more information, note that the information is in swedish (same as the target audience).

## Install and setup your own copy
First clone the repo you can use the following command: ```git clone git@github.com:mabn17/ramverk1-proj.git```

### Update pagages and install tools
Make sure you have the latest versions: ```composer update```

Run ```make install``` to get the nessecary tools.

### Set up the database
The database is witten in **MySQL** to set it up run the following command while in the root directory: 
```mysql -u{root user} -p{your password} < sql/ddl/proj_mysql.sql```

Then change the configuration file name from `config/database_sample.php` to `config/database.php` and change the values as you see fit.

### License

```
.
..: Copyright (c) 2018 - 2019 Martin Borg (martin.d@live.se)
```

Repo for the [examination project](https://dbwebb.se/kurser/ramverk1-v2/kmom10) in course Ramverk1 @ [BTH](https://www.bth.se/eng/), dbwebb.

*Webpage is built in the [anax](https://github.com/canax) framework*