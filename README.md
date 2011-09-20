About
-----

Cargo is a micro framework established with a crazy idea of a reverse
workflow. The handling is not controlled by a controller design, it begins
with the template. The only thing what you have to do is to register a template file
in your choosen theme directory. The routing must be defined directly in the
template.

This framework has to say a lot of thanks to the developers of the vendor
libraries. Specially many thanks to fabpot an his excelent Silex framework.


Modules
-------

git submodule add git://github.com/symfony/ClassLoader.git vendor/Symfony/Component/ClassLoader
git submodule add git://github.com/symfony/HttpFoundation.git vendor/Symfony/Component/HttpFoundation
git submodule add git://github.com/symfony/Routing.git vendor/Symfony/Component/Routing
git submodule add git://github.com/symfony/Finder.git vendor/Symfony/Component/Finder
git submodule add git://github.com/symfony/EventDispatcher.git vendor/Symfony/Component/EventDispatcher

git submodule add git://github.com/fabpot/Pimple.git vendor/pimple
git submodule add git://github.com/fabpot/Twig.git vendor/twig

git submodule add git://github.com/doctrine/common.git vendor/doctrine-common

Todo
----
 - PHP Templates
 - Markdown Templates
 - RST Templates
 - Template Caching
 - PHAR
