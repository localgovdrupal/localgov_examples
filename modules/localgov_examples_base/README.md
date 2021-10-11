# LocalGov Drupal Examples: Base

## Adds a content type and view mode

Content types, fields and other configuration added by the LocalGov
Distribution prefix their machine names with `localgov_`. It's highly advised
if you are adding site specific configuration to use a consistent `siteid_`.
You can do this for fields by changing `config/sync/field_ui.settings.yml`
prefix.

## Creates and updates configuration

Provides an example of creating new configuration, and the conditionally
updating it based on any changes that the site might have made.
