# Export Module
Export Module is MediaWiki extension to export lua script module namespace.
# MediaWiki Extension: Export Module
---
## 1. Information
* **Purpose:** Allows administrators and developers to easily export Lua script modules from a MediaWiki installation.
* **Namespace:** Targets the `Module:` namespace where Scribunto Lua scripts are stored.
* **Source Code:** Hosted on GitHub under the [N635-wiki](https://github.com/N635-wiki/Export-Module) organization/user.

---

## 2. Installation Instructions
1. **Download or Clone:**
   Download the extension files into your MediaWiki `extensions/` directory.

2. **Directory Naming:**
   Ensure the folder is named `Export Module`.

3. **Configure `LocalSettings.php`:**
   Add the following line to the bottom of your `LocalSettings.php` file:
   ```php
   wfLoadExtension( 'Export Module' );
   ```

4. **Run Maintenance Scripts:**
   Open a terminal in your MediaWiki installation folder and run the update script to apply any necessary changes:
   ```bash
   php maintenance/update.php
   ```

---

## 3. GitHub & Version Control Integration

### Cloning via Git
To clone the repository directly into your `extensions` directory:
```bash
cd extensions/
git clone https://github.com/N635-wiki/Export Module.git
```

### Keeping Extension Updated
To update the extension to the latest version via GitHub:
```bash
cd extensions/Export Module
git pull origin main
```

---

## 4. Usage & Configuration Options

* **Accessing the Tool:**
  Once installed, navigate to `Special:SpecialPages` on your MediaWiki wiki and locate **Export Module** under the developer/admin utilities section.

* **Permissions:**
  Ensure that user roles (e.g., `sysop`) have the required permissions granted in `LocalSettings.php` if custom access control is needed:
  ```php
  $wgGroupPermissions['sysop']['export-module'] = true;
  ```
