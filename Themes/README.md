# Themes

This is the base folder for al themes you have installed. Any that havent already been installed through the website wil not be usable.

> **_NOTE:_**  DO NOT DELETE THE `.default` THEME. the website relies on that as a main/backup theme for style and functionality

## Existing Theme

### Installation

To install a new theme, you have to do onto the admin domain for the system you would like to install it on to and navigate to `Themes > Install a new theme` and upload the zip file containing the theme you want to install.

### Apply a theme to a domain

After you have installed a new theme, you may want to apply it to a domain. To do this, you have to navidate to the domain you are wanting to apply the theme to `Domains > 'Domain' > Theme` and from the ropown box select the thee you are wanting to apply, then press apply.

## Build a theme

### File structure

the basic file structure for any new theme would be as follows

```bash
├── assets
│   ├── script.js
│   └── style.css
└── info.jsons
```

If you want to start with a template theme, have a look at the folder named `.template`. This directory houss all of the basic files you need to build a theme of your own!
