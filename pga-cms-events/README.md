# pga-cms-event		
Supports an Event with all of the needed configs.
- Creates an Events entity.
- Creates Event Administrator and Event Editor roles.
- Creates Event Year and Event Name Vocabularies (taxonomy)
  - Adds current year-1, current year, and current year+1 terms to Event Year Vocabulary
  - Adds PGA Chamionship and Senior PGA Championship terms to Event Name Vocabulary
  
* Drush enable will currently fail. Must be enabled via UI.
* $expire for PrivateTempStoreFactory can only be overriden if extending the class. see http://www.drupalcontrib.org/api/drupal/drupal%21core%21modules%21user%21lib%21Drupal%21user%21TempStore.php/property/TempStore%3A%3Aexpire/8
