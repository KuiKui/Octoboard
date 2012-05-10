Phing and Propel Clone
======================

What is it?
-----------

There are 4 repos:

1. https://github.com/Xosofox/propel
2. https://github.com/Xosofox/propel1.6
3. https://github.com/Xosofox/phing
4. https://github.com/Xosofox/propelVendor

They are all copies/clones of the official propel and phing SVN repositories.
* http://svn.propelorm.org/branches/
* http://svn.phing.info/tags/

Why "propel" and "propel1.6"?
-----------------------------

Difference between "propel" and "propel1.6" is, that in 1.6 you have the current 1.6 version in the master branch
In "propel", there are branches 1.1 to 1.6, so you need to/can select your desired version

Which version is "phing"?
-------------------------

The phing repo is currently using the version 2.3.3 (http://svn.phing.info/tags/2.3.3/) due to compatibility reasons with the PropelBundle

Why at all?
-----------

The intention of these repos is to use them as a git submodule in your project, so you don't have to play with git-svn or anything like that.

How?
----

For example, when working with Symfony2 and git, and using Propel as your ORM, you might be using the PropelBundle

https://github.com/willdurand/PropelBundle

Any you are including it as a git submodule.

Since the bundle depends on phing and propel, you might want to include

    > git submodule add https://github.com/Xosofox/phing vendor/phing
    > git submodule add https://github.com/Xosofox/propel1.6 vendor/propel
