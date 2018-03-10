# SMTS_Base
a php framework

# Installation
1. Download the framework from github, you can find the most recent version on [the releases tab].

2. Unzip `Smts_Base-master.zip` and place the `Smts_Base` folder into your web-root, for example `C:/xampp/htdocs/`.

3. Optionally, change the `Smts_Base` folder to your project name.

4. Edit the following lines in your `base/config.php` file:

    `DefaultTitle`, `BaseUrl`, `DataBaseName`, `DataBaseUser`, `DataBasePassword`.

    For more information on what you should change them to, see [the wiki].

5. Run the database setup at `/dev/setup`, this will create the database and add some default users.

# Usage
For detailed guides, and information about all classes and funtionality see [the wiki].

# Directory Structure
```
Smts_Base/               Framework code
Smts_Base/assets         Images, js- and css-files
Smts_Base/base           Core framework code
Smts_Base/controllers    Controller files
Smts_Base/models         Model files
Smts_Base/views          View files
```

[the wiki]: https://github.com/SimonMTS/Smts_Base/wiki
[the releases tab]: https://github.com/SimonMTS/Smts_Base/releases