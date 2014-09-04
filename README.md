#Tuto MVC


"Tuto MVC" is a WordPress plugin for developers that helps you develop and customize your WordPress installation.


##Features


###PHP Exception Handler

<img src="http://tutomvc.com/wp-content/uploads/2014/09/Exception.png" alt="PHP Exception" title="PHP Exception" />

When an exception is thrown and it's matching the current error_reporting, the error will be logged, backtraced and outputed for easy debugging.

###Log
 
<img src="http://tutomvc.com/wp-content/uploads/2014/09/Screen-Shot-2014-09-04-at-11.42.17.png" alt="PHP Log File" title="PHP Log File" />

Log an event. View the log files from the WP Admin area.
```php
$systemFacade = \tutomvc\Facade::getInstance( \tutomvc\Facade::KEY_SYSTEM );
$systemFacade->logCenter->add( "Save this to log file." );
```

###Meta Boxes

<img src="http://tutomvc.com/wp-content/uploads/2014/09/Screen-Shot-2014-09-04-at-12.27.20.png" alt="Meta Box" title="Meta Box" />

Create custom meta fields for post types and users.

```php
namespace myapp;
use \tutomvc\MetaBox;
use \tutomvc\MetaField;
use \tutomvc\SingleSelectorMetaField;

class HeroBannerMetaBox extends MetaBox
{
	const NAME = "tutomvc_hero_banner";
	const IMAGES = "images";
	const TEMPLATE = "template";
	const TEMPLATE_WIDE = "template_wide";
	const TEMPLATE_STRAIGHT_COVER = "template_straight_cover";
	const TEMPLATE_STRAIGHT_FULL = "template_straight_full";
	const TEMPLATE_STRAIGHT_FIT = "template_straight_fit";

	function __construct()
	{
		parent::__construct(
			self::NAME,
			__( "Hero Banner", "myapp" ),
			array( "post", "page" ),
			1,
			MetaBox::CONTEXT_NORMAL,
			MetaBox::PRIORITY_HIGH
		);

		$this->addField( new MetaField(
			self::IMAGES,
			__( "Images", "myapp" ),
			"",
			MetaField::TYPE_ATTACHMENT,
			array(
				MetaField::SETTING_MAX_CARDINALITY => -1,
				MetaField::SETTING_FILTER => array( "image" )
			)
		) );

		$this->addField( new SingleSelectorMetaField(
			self::TEMPLATE,
			__( "Template", "myapp" ),
			"",
			array(
				self::TEMPLATE_WIDE => __( "Wide 2:1 (with controls)", "myapp" ),
				self::TEMPLATE_STRAIGHT_COVER => __( "Straight & Cover", "myapp" ),
				self::TEMPLATE_STRAIGHT_FULL => __( "Straight & Full", "myapp" ),
				self::TEMPLATE_STRAIGHT_FIT => __( "Straight & Fit", "myapp" )
			),
			self::TEMPLATE_WIDE
		) );
	}
}
```

```php
$systemFacade = \tutomvc\Facade::getInstance( \tutomvc\Facade::KEY_SYSTEM );
$systemFacade->metaCenter->add( new HeroBannerMetaBox() );
```

###Notifications

<img src="http://tutomvc.com/wp-content/uploads/2014/09/Screen-Shot-2014-09-04-at-11.47.43.png" alt="WP Admin Notification" title="WP Admin Notification" />

Add notifications in the WP Admin area.
```php
$systemFacade = \tutomvc\Facade::getInstance( \tutomvc\Facade::KEY_SYSTEM );
if(!AppFacade::isProduction()) $systemFacade->notificationCenter->add( "This is <strong>NOT</strong> production environment.", \tutomvc\Notification::TYPE_NOTICE );
```

###Custom Post Types

Use the post type model to create custom post types and add support for custom table culomns.

###Custom Taxonomies**

Use the taxonomy model to create custom taxonomies and add support for custom table culomns.


###Privacy Module

Limit the access to your blog.

###Analytics Module

Add admin settings page to setup your Google Analytics- or Google Tag Manager account.
Then to render:
```php
do_action( \tutomvc\modules\analytics\AnalyticsModule::ACTION_RENDER );
```

###Term Page Module

Do you want to customize the outputted content for a term page?
This module adds the ability to setup a landing pages for terms.

### More Features

- **Create Admin Menu Pages**
- **Create Admin Settings**
- **Add Custom User Columns**

##Dependencies

- PHP > 5.3


##Thanks to

- http://backbonejs.org/
