# Laravel Unused Finder


<a name="usage"></a>
## Usage

<a name="find-classes"></a>
### Find classes

If you want to find the unused classes, you may need to use this command:

```shell
php artisan find-unused:classes {path}
```

If you didn't write any path, the command ask you automatically, and it's required to write the correct path to find unused classes.
So after that, the package goes and find the unused classes and show you all unused classes like this:

![Find Unused Classes](/art/find-classes.png "Find Unused Classes")

