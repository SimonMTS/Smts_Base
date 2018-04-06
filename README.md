# SMTS_Base
a php framework

# Installation
1. Download the framework from github, you can find the most recent version on [the releases tab].

2. Unzip `Smts_Base-version.zip` and place the `framework` folder into your web-root, for example `C:/xampp/htdocs/`.

3. Optionally, change the `framework` folder to your project name.

4. Edit the following lines in your `base/config.php` file:

    `DefaultTitle`, `BaseUrl`, `DataBaseName`, `DataBaseUser`, `DataBasePassword`.

    For more information on what you should change them to, see [the wiki].

5. Run the database setup at `/dev/setup`, this will create the database and add some default users.

# Usage
For detailed guides, and information about all classes and funtionality see [the wiki].

# Directory Structure
```
framework/                  Framework code

framework/base              Supporting code
framework/base/core         Core framework code
framework/base/helpers      Helper classes
framework/base/i18n         Translation files
framework/base/validators   Validator classes

framework/assets            Images, js- and css-files
framework/controllers       Controller files
framework/models            Model files
framework/views             View files
framework/modules           Module files
```

[the wiki]: https://github.com/SimonMTS/Smts_Base/wiki
[the releases tab]: https://github.com/SimonMTS/Smts_Base/releases