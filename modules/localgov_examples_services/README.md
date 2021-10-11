# LocalGov Drupal: Service integration

Modules, and their content types, opt-in to being able to be placed into
Services. To maintain the ability to install components outside of the
distribution this is done without making a hard dependency. It is also
made as seamless as possible, so not requiring enabling a seperate submodule.

As the code in this example would normally be in the module providing the
content type having it seperate in a module is unusual, but is done to make it
clear which code is required for integration. The content type is provided by
the Base example.

Opting into services is done by adding a configured entity reference field.
This pattern is repeated in the Directories module where [Directory Item
content types can be added with a field](https://docs.localgovdrupal.org/devs/features/directories-technical.html#advanced-configuration)

Important functions to follow are `localgov_examples_services_install` and 
`localgov_examples_services_modules_installed` which handle making sure the
optional configuration `config/optional` is installed with additional settings
configured.
