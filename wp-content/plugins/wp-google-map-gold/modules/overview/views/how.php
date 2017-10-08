<?php
/**
 * Plugin Overviews.
 * @package Maps
 * @author Flipper Code <flippercode>
 **/

?>

<div class="container wpgmp-docs">
<div class="row flippercode-main">
    <div class="col-md-12">
           
		   
            <h4 class="alert alert-info"> How to Create your First Map? </h4>
              <div class="wpgmp-overview">
                <ol>

                    <li><?php
                    $url = admin_url( 'admin.php?page=wpgmp_manage_settings' ); ?>
                   First create a  <a href="http://bit.ly/29Rlmfc" target="_blank">  Google Map API Key</a>. Then go to <a href="<?php echo $url ?>"> Settings </a> page and insert your google maps API Key and save.
                    </li>
					
					<li><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_location' ); ?>
                    Create a location by using<a href="<?php echo $url; ?>" target="_blank">  Add Location</a> page. To import multiple locations on a single click, Go to  "Import Location" page and browse your csv file and import it. </a>.
                    </li>
					
					<li><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_map' ); ?>
                    Go to <a href="<?php echo $url; ?>" target="_blank">  Add Map</a> page and insert details as per your requirement. Assign locations to map and save your map.
                    </li>
															                    
                </ol>
            </div>
			
			<h4 class="alert alert-info"> How to Display Map in Frontend? </h4>
              <div class="wpgmp-overview">
                		
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_manage_map' ); ?>
                    Go to <a href="<?php echo $url; ?>" target="_blank"> Manage Map</a> and copy the shortcode then paste it to any page/post where you want to display map.
                    </p>
                    
              </div>
			  
		 <h4 class="alert alert-info"> How to Create Routes in Map? </h4>
              <div class="wpgmp-overview">
                <ol>
                   
					<li><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_route' ); ?>
                   	Go to <a href="<?php echo $url; ?>" target="_blank"> Add Routes </a> page and Insert route title, stroke color, opacity, weight, travel modes, unit, start location(Journey Starting Point), end Location (Destination) and waypoints. You can choose max 8 waypoints.
                    </li>
					
					<li><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_map' ); ?>
                    Go to "Route Direction Settings" on 'Edit Map' Page and enable "Turn on Map Route Direction" and select routes to display on the map.
                    </li>
										                    
                </ol>
            </div>
			
		
        <h4 class="alert alert-info"> How to Draw Shapes in Google Map? </h4>
              <div class="wpgmp-overview">
                <ol>
                    <li><?php
                    $url = admin_url( 'admin.php?page=wpgmp_manage_drawing' ); ?>
                   Click <a href="<?php echo $url; ?>" target="_blank"> Drawing</a> page and select a Map.
                    </li>
					
					<li>
                    Select a shape which you want to draw on map e.g Circle, Polygon, Line, Rectangle.
					</li>
					
					<li>
                    Draw the shape which you like on the map. Click the shape for shape properties where you can select color , opacity. You can display message on shape click or redirect to a page.
                    </li>
										                    
                </ol>
            </div>

		<h4 class="alert alert-info"> How to display Categories, Direction, Nearby and Routes in Map? </h4>
                <div class="wpgmp-overview">
                	<p>You can display Category, Direction, Nearby and Routes tabs on the map by using <b>Tabs Settings</b> located under <b>Add/Manage Map Page</b>. Each tab gives you ability to apply marker filtration on the map.
					<?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_map' ); ?>
                    </p>
                    <p>
                    Go to <b>Tabs Settings</b> on <a href="<?php echo $url; ?>" target="_blank"> Add Map</a> page and tick to display categories, direction, nearby and Routes.
                   </p>
		</div>


		<h4 class="alert alert-info"> How to display Listing below the Map? </h4>
                <div class="wpgmp-overview">
                		<p>You can display paginated location listings below the map and can display a search box, category filter, sorting filter, print and grid view buttons by using <b>Listing Settings</b> located under <b>Add/Manage Map Page</b>.</p>
                		
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_map' ); ?>
                   Go to <b>Listing Settings</b> on <a target="_blank" href="<?php echo $url; ?>"> Add Map Page</a> and tick on "Display Listing". You can show search box, category filter, sorting filter, print and grid view buttons by choosing options.
                   </p>
			    </div>
			    

		<h4 class="alert alert-info"> How to Create Marker Category? </h4>
                <div class="wpgmp-overview">
                		
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_group_map' ); ?>
                    Go to <a href="<?php echo $url;?>" target="_blank"> Add Marker Category</a> and choose parent category if any , category title and choose icon. These categories can be assigned to the location on "Add Locations" page.
                   </p>
			    </div> 


			 <h4 class="alert alert-info"> How to Export Multiple Location in CSV File? </h4>
              <div class="wpgmp-overview">
                		
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_manage_location' ); ?>
                    Go to <a href="<?php echo $url; ?>" target="_blank">Manage Location</a> and select locations which you want to export or leave it blank to export all locations. Then choose 'Export as CSV' in "Bulk Action" drop down menu and click on 'Apply' button.
                    </p>
                    
              </div>
			  
			 <h4 class="alert alert-info"> How to Import Multiple Location using CSV File? </h4>
              <div class="wpgmp-overview">
                		
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_import_location' ); ?>
                    Go to <a href="<?php echo $url ?>" target="_blank"> Import Location</a>  page and choose the delimeter and browse the csv file which contains multiple locations and import it. You will find all imported locations in "Manage Locations" page.
                    </p>
                    
              </div>     	

			<h4 class="alert alert-info"> How to Create Extra Fields? </h4>
                <div class="wpgmp-overview">
                		<p>Extra fields are used to display extra information of the location in infowindow by using custom placeholders.</p> 
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_manage_settings' ); ?>
                    Go to <a href="<?php echo $url ?>" target="_blank"> Settings</a> and add extra fields like phone, fax, email etc. You can use this extra field as a placeholder like {phone},{fax},{email} etc.
                   
                   </p>
			    </div>      

			<h4 class="alert alert-info"> How to display Posts on the Google Maps? </h4>
                <div class="wpgmp-overview">
                		<p>To display post content on the map you can use "Wp Google Map Metabox" located at Posts/pages where you have to enter the location, choose marker category, location redirect and select the map.</p>
			    </div>      	


			<h4 class="alert alert-info"> How to customize Infowindow Message? </h4>
                <div class="wpgmp-overview">
                	<p>Using placeholders, you can customize the infowindow message. like {marker_title}, {marker_address}, {marker_city} etc are used to customize the body of the infowindow message.</p>
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_map' ); ?>
                    'Infowindow Message' in 'Add/Edit Map' page is used to display information from manually added location. Go to <b>Info Window Message</b> at <b>Infowindow Settings</b> under <a target="_blank" href="<?php echo $url; ?>"> Add Map</a> page and insert the placeholder according to your choice.
                   </p>

                    <p>You can use following placeholders. </p>

         		<ul>
                <li><b>Location ID :</b><code>{marker_id}</code></li>
                <li><b>Location Title :</b><code>{marker_title}</code></li>
                <li><b>Location Address :</b><code>{marker_address}</code></li>
                <li><b>Location Message :</b><code>{marker_message}</code></li>
                <li><b>Location Categories :</b><code>{marker_category}</code></li>
                <li><b>Location Marker Icon :</b><code>{marker_icon}</code></li>
                <li><b>Location Latitude :</b><code>{marker_latitude}</code></li>
                <li><b>Location Longitude :</b><code>{marker_longitude}</code></li>
                <li><b>Location City :</b><code>{marker_city}</code></li>
                <li><b>Location State :</b><code>{marker_state}</code></li>
                <li><b>Location Country :</b><code>{marker_country}</code></li>
                <li><b>Location Zoom :</b><code>{marker_zoom}</code></li>
                <li><b>Location Postal Code :</b><code>{marker_postal_code}</code></li>
        </ul>
<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_form_map' ); ?>
                    'Infowindow Message for Posts' in 'Add/Edit Map' page is used to display post's information. Go to <b>Info Window Message for Posts</b> at <b>Infowindow Settings</b> under <a target="_blank" href="<?php echo $url; ?>"> Add Map</a> page and insert the placeholder according to your choice.
                   </p>

        <ul>
                <li><b>Post Title :</b><code>{post_title}</code></li>
                <li><b>Post Title with Link :</b><code>{post_link}</code></li>
                <li><b>Post Excerpt :</b><code>{post_excerpt}</code></li>
                <li><b>Post Content :</b><code>{post_content}</code></li>
                <li><b>Featured Image :</b><code>{post_featured_image}</code></li>
                <li><b>Categories :</b><code>{post_categories}</code></li>
                <li><b>Tags :</b><code>{post_tags}</code></li>
                <li><b>Custom Fields :</b><code>{%custom_field_slug_here%}</code> eg. {%age%}, {%salary%}</li>

        </ul>

        </div>  

          <h4 class="alert alert-info"> Shortcodes </h4>
            <div class="wpgmp-overview">
           
            <p>
               This plugin provides shortcodes which helpful for a non-programmer and programmer to display maps dynamically. Below are the shortcode combinations you may use though possiblity are endless to create combinations of shortcodes.
            </p>
            <p>
                <h5 class="alert alert-info">Display Map using latitude & longitude</h5>
            </p>
            <p>
                Standard format for shortcode is as below.
            </p>
            <p>
            <code>
[display_map width="500" height="500" zoom="5" language="en" map_type="ROADMAP" map_draggable="true"  marker1="39.639538 | -101.527405 | title | infowindow message | marker category name"]
</code>

                So you can display any number of markers using this shortcode.
            </p>
            <p>Below are few examples to understand it better.</p>
            <p>
                <b>Single Location :</b>
                <br />
                <code>[display_map marker1="39.639538 | -101.527405 | hello world | This is first marker's info window message | category"]</code>
                <br>
            </p>
            <p>
                <b>Multiple Locations :</b>
                <br />
                <code>
[display_map marker1="39.639538 | -101.527405" marker2="39.027719|-111.546936"]
</code>
            </p>
            <p>
                <h5 class="alert alert-info">Display Map using Address</h5>
            </p>
            <p>
               Standard format for shortcode is as below.
            </p>
            <p>
                <code>
[display_map width="500" height="500" zoom="5" language="en" map_type="ROADMAP" map_draggable="true" scroll_wheel="true" <br /> address1="New Delhi, india | title | infowindow message |  marker category name"]
</code>

            </p>
            <p>Below are few examples to understand it better. </p>
            <p>
                <b>Single Location :</b>
                <br />
                <code>[display_map address1="New Delhi, india | hello world | This is first marker's info window message | category"]</code>
                <br />
            </p>
            <p>
                <b>Multiple Locations :</b>
                <br />
                <code>
[display_map address1="New Delhi, India" address2="Mumbai, India"]
</code>
            </p>
        </div>

    

    <h4 class="alert alert-info"> How to take Backup? </h4>
                <div class="wpgmp-overview">
                		<p>You can take backup of your all plugin in a single click. Your backup will be store at Manage Backup where you can import it very easily if data is accidently deleted. You can also choose your backup file from your computer</p>
					<p><?php
                    $url = admin_url( 'admin.php?page=wpgmp_manage_backup' ); ?>
                    Go to <a href="<?php echo $url; ?>" target="_blank"> Manage Backup</a> and click on a Plugin backup. it will take all backup of your plugin.
                   </p>
			    </div>

	<h4 class="alert alert-info"> Google Map API Troubleshooting </h4>
        <div class="wpgmp-overview">
			  <p>Sometime API key is properly inserted but map is not working. Then WHAT To Do? To overcome this issue ,We have created a simple guide about <a target="_blank" href="http://bit.ly/292gCV2">Google Maps API Key. </a> </p>
			  <p>First Go to "Google Console Overview Page" and make sure you have enabled following 6 api's key which is widely used in Google Maps API Programming. </p>
                
				
        </div>	
	
		<h4 class="alert alert-info"> Google Map API Troubleshooting </h4>
<div class="wpgmp-overview">
<p>If your google maps is not working. Make sure you have checked following things.</p>
<ul>
<li> 1. Make sure you have assigned locations to your map.</li>
<li> 2. You must have google maps api key.</li>
<li> 3. Check HTTP referrers. It must be *.yourwebsite.com/* <br>
<p><img src="<?php echo WPGMP_IMAGES; ?>referrer.png"> </p>
</li>
</ul>
<p>If still any issue, Create your <a target="_blank" href="http://www.flippercode.com/forums">support ticket</a> and we'd be happy to help you asap. </p>
</div>			
</div>
</div>
</div>