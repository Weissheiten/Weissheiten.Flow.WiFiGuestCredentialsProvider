Weissheiten.Flow.WiFiGuestCredentialsProvider |version| Documentation
==================================================================================================

This documentation covering version |release| has been rendered at: |today|

Introduction
------------
This package provides WirelessAuthentication codes via JSON that have been preloaded into the database from a CSV.
They can be queried one at a time to be printed in a local outlet via a dektop application that connects to the receipt printer,
so guests can be provided with an access code on demand.

Demonstration
--------------
*) Start Docker instance with installed Flow
*) Show Controller and View
*) Include Model
    -) Show database configuration aka Configure Database Credentials (http://flowframework.readthedocs.io/en/latest/Quickstart/index.html#database-setup)
    -) Kickstart a new Flow Package (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartII/Kickstart.html#kickstart-the-package)
      +) ./flow kickstart:package Weissheiten.Flow.WiFiGuestCredentialsProvider
    -) Create Model for WiFiCodes (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartII/ModelAndRepository.html?highlight=directory#model-and-repository)
      +) ./flow kickstart:model Weissheiten.Flow.WiFiGuestCredentialsProvider WiFiVoucher username:string password:string validitymin:integer
      +) add validation where needed (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartIII/Validation.html#validation)
         We don't need this for the example (data comes from a csv) but will add it for demonstrations sake
      +) add extended Metadata for persistence (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartIII/Persistence.html#persistence) - no relations, we don't need this in the example
    -) Create a Repository for WiFiCodes (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartII/Kickstart.html#command-line-tool)
    -) create the database tables via a new migration
        +) ./flow doctrine:migrationgenerate
        +) ./flow doctrine:migrate
   -) create Database via Doctrine
*) Create a command controller for entering a new testcode via command line (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartII/Controller.html#setup-controller)
   -) ./flow kickstart:commandcontroller Weissheiten.Flow.WiFiGuestCredentialsProvider WifiVoucher
   -) show off with the new cool controller: ./flow wifivoucher:setup
   -) show how to use it: ./flow help wifivoucher:setup
   -) modify the Commandcontroller to create a new voucher
      +) do not check the password length and show how flow validates it via annotations and returns an error
      +) fix the problem by checking the password length and returning a nicer output
   -) Output Code in HTML
   -) Convert the HTML output to JSON (http://flowframework.readthedocs.io/en/latest/TheDefinitiveGuide/PartIII/ModelViewController.html?highlight=render%20json#json-view)



Some markup examples
--------------------

Backticks make for `inline code` and code blocks can be in various languages like YAML:

.. code-block:: yaml

  TYPO3:
    Neos:
      Seo:
        twitterCard:
          siteHandle: '@neoscms'

or PHP:

.. code-block:: php
  :linenos:

  $foo = new Bar();
  $drink = $bar->getKeeper()->order('White Russian');

More information can be found at

* https://www.neos.io/join/contribute.html
* http://sphinx-doc.org/rest.html
* https://docs.readthedocs.org/en/latest/getting_started.html#in-rst
