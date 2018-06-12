# Yikes, Inc. Level Playing Field > Source

This directory contains all of the source files for the plugin. All files follow the [PSR-4](http://www.php-fig.org/psr/psr-4/) standard for class/path naming. The root namespace of this directory is `Yikes\LevelPlayingField`.

## Files

Files within this directory (as opposed to the subdirectories described below) are top-level general files. They include the following:

* `Autoloader` - This is the class responsible for autoloading all of the other classes within the plugin.
* `Container` - This class is meant for containing services that are used by this plugin.
* `Plugin` - This is the main functionality of the Plugin. Its purpose is to set up all of the services that the plugin utilizes.
* `PluginFactory` - This class serves to create a single instance of the `Plugin` class. This prevents the need for a singleton anti-pattern on the `Plugin` class.

The following [interfaces](http://php.net/manual/en/language.oop5.interfaces.php) are also available:

* `Registerable` - This interface allows for classes to declare that they have functionality that needs to be registered, in our case with WordPress.
* `Renderable` - This interface allows for classes to declare that they have content that can be rendered.
* `Service` - This extends the `Registerable` interface. Classes that implement this interface are refered to as **Services** elsewhere in the plugin.

## Subdirectories

For organizational purposes, each of the subdirectories has its own purpose. Each of the sections below describes the purpose of the directory, as well as how to work with the classes it contains.

### Assets

**Interfaces:**

* `Asset`
* `AssetsAware`

**Traits:**

* `AssetsAwareness`

**Abstract classes:**

* `BaseAsset`

**Classes:**

* `AssetsHandler`
* `ScriptAsset`
* `StyleAsset`

### CustomPostType

**Abstract classes:**

* `BaseCustomPostType`

**Classes:**

* `ApplicantManager`
* `ApplicationManager`
* `JobManager`
* `LimitedJobManager`

### Exception

**Interfaces:**

* `Exception`

**Classes:**

* `FailedToLoadView`
* `InvalidAnonymousData`
* `InvalidAssetHandle`
* `InvalidPostID`
* `InvalidService`
* `InvalidURI`
* `MustExtend`
* `TooManyItems`

### ListTable

**Abstract classes:**

* `BasePostType`

**Classes:**

* `JobManager`

### Metabox

**Abstract classes:**

* `AwesomeBaseMetabox`
* `BaseMetabox`

**Classes:**

* `JobManager`

### Model

**Interfaces:**

* `ApplicantMeta`
* `ApplicationMeta`
* `Entity`
* `JobMeta`
* `LimitedEntity`

**Abstract classes:**

* `AnonymousCustomPostTypeEntity`
* `CustomPostTypeEntity`
* `CustomPostTypeRepository`

**Classes:**

* `AnonymousApplicant`
* `ApplicantRepository`
* `Application`
* `ApplicationRepository`
* `Job`
* `JobRepository`

### Roles

**Interfaces:**

* `Capabilities`

**Abstract classes:**

* `BaseRole`
* `ExistingRole`

**Classes:**

* `Administrator`
* `Editor`
* `HiringManager`
* `HumanResources`

### Shortcode

**Abstract classes:**

* `BaseShortcode`

**Classes:**

* `Jobs`

### Taxonomy

**Abstract classes:**

* `BaseTaxonomy`

**Classes:**

* `ApplicantStatus`
* `JobCategory`
* `JobStatus`

### Tools

**Interfaces:**

* `AnonymizerInterface`

**Classes:**

* `Base64Anonymizer`

### View

**Interfaces:**

* `View`

**Classes:**

* `BaseView`
* `FormEscapedView`
* `PostEscapedView`
* `TemplatedView`
