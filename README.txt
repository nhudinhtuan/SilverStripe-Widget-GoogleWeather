Google Weather Widget 0.0.1 for SilverStripe
26.03.2012
Created by Nhu Dinh Tuan
nhudinhtuan@gmail.com
http://fb-school.com/
========================================================================
This widget enables you to display weather forecast on your blog.
The weather forecast information is given by Google Weather API.
========================================================================
NOTE: Extract and rename the folder to widgets_googleweather after downloading from gitHub
========================================================================
Installation:

+++If you are using the blog module:
1. Extract the widgets_googleweather folder into the top level of your site.
2. Run "/dev/build?flush=all".
3. You can add this widget to your blog using the Widgets tab.

+++If you are NOT using the blog module:
1. Extract the widgets_googleweather folder into the top level of your site.
2. Ensure that widgets have been enabled in your site. You should have something like the following code in "mysite/code/Page.php":

        public static $has_one = array(
          'SideBar' => 'WidgetArea',
        );
        public function getCMSFields(){
          $fields = parent::getCMSFields();
          $fields->addFieldToTab(
            'Root.Content.Widgets',
            new WidgetAreaEditor('SideBar')
          );
          return $fields;
        }

3. Add the placeholder "$SideBar" to your template where you want to display your Facebook feed.
4. Run "/dev/build?flush=all".
5. Reload the CMS interface, the widget should be usable on the *Widgets* tab.