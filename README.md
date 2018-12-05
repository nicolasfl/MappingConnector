![MappingConnectorBundle](doc/MappingBundle.png)

MappingConnectorBundle
==========================

Import and export products with specific columns names. Map them with the Akeneo attributes and integrate or generate the CSV you need.

The creation of specific connectors is a very frequent request in Akeneo projects. 
The best practice would be to homogenize our data structures across all of our channels, but it is sometimes difficult to edit systems already developed for years. 
This bundle allows you to adapt the exports and imports to any interface by adding a mapping from the back office between the Akeneo attributes and those of your other platforms.

## Requirements

| MappingConnectorBundle     | Akeneo PIM Community Edition | Akeneo PIM Enterprise Edition |
|:------------------------------:|:----------------------------:|:-----------------------------:|
| v1.0.*                         | v2.3.*                         | v2.3.*                              |

## Installation
------

Next, enter the following command line:
```console
$php composer.phar require "nicolas-souffleur/mapping-connector-bundle":"1.0.*"
```

Then enable the bundle in the ```app/AppKernel.php``` file in the registerProjectBundles() method:
```php
$bundles[] = new \Extensions\Bundle\MappingConnectorBundle\ExtensionsMappingConnectorBundle()
```

Warning : The Custom Entity Akeneo Labs extension is required to use this extension.

## Usage
------

### Create a Mapping :
There's two ways to create a mapping, just choose your favorite one :)

##### Creation via interface 
1. Go to **Settings > Mapping**. Here you can find all the mappings created.
2. Click on **Create** and fill all the required informations.
3. Click on **Save**

##### Import via CSV :
You can import easily your mappings via CSV because Akeneo already prepared a job type to import the reference datas.
1. First, go to **Import > Create Import Profile** and create an import job with the "Reference Data import in CSV" job type.
2. Create a new CSV file with the following columns : code, job, attribute, title 
    * **code** : a unique code for the entity (ex  : brand_marque_import)
    * **job** : the job instance code, must be created with the MappingConnectorBundle (ex : product_import_mapping)
    * **attribute** : the Akeneo attribute code (ex : brand)
    * **title** : the CSV column's name (ex : Marque)
3. Import it with the job you created on step 1
4. Go to **Settings > Mapping** and you can see all your imported mappings

### Import / Export :
To use the MappingConnectorBundle, you only need to create a new import or export job with one of those two profiles : 
- Product Import with Mapping (Imports > Create > Job)
- Product Export with Mapping (Exports > Create > Job)

## Roadmap
------
* Add a select field to choose the Job Instance and the Akeneo Attribute in the creation form
* Prices and metric attribute type support
* [DONE] Generate a Mapping code automatically 
* Enable multi-mapping for an attribute

Don't hesitate to send me a message if you would like other features :)

## About me
------
Specialized in Akeneo since its launch in 2014, I'm helping companies to implement this efficient and essential solution, to integrate it into their workflow and to structure their data. Feel free to contact me through my contact form on my website (http://www.nicolas-souffleur.com) or directly by email (contact@nicolas-souffleur.com).

