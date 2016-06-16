<?php
    // Create Public / Global Variables
    // --------------------------------
    $this->TS_VCSC_Installed_Icon_Fonts = array(
        "Font Awesome"                  		=> "Awesome",
        "Brankic 1979 Font"            		 	=> "Brankic",
        "Countricons Font"              		=> "Countricons",
        "Currencies Font"               		=> "Currencies",
        "Elegant Icons Font"            		=> "Elegant",
        "Entypo Font"                   		=> "Entypo",
        "Foundation Font"               		=> "Foundation",
        "Genericons Font"               		=> "Genericons",
        "IcoMoon Font"                  		=> "IcoMoon",
        "Ionicons Font"                  		=> "Ionicons",
        "MapIcons Font"                		    => "MapIcons",
        "Metrize Font"                		    => "Metrize",
        "Monuments Font"                		=> "Monuments",
        "Social Media Font"             		=> "SocialMedia",
        "Themify Font"                          => "Themify",
        "Typicons Font"                 		=> "Typicons",
        "Custom User Font"              		=> "Custom",
    );
    
    $this->TS_VCSC_Icon_Font_Settings = array(
        "Font Awesome"                  		=> array("setting" => "Awesome",                "author" => "Dave Gandy",               "type" => "Standard",       "default" => "true",		"active" => "true",    "always" => "false",    "count" => ""),
        "Brankic 1979 Font"            		 	=> array("setting" => "Brankic",                "author" => "Brankic1979",              "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Countricons Font"              		=> array("setting" => "Countricons",            "author" => "Freepik",                  "type" => "Specialty",      "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Currencies Font"               		=> array("setting" => "Currencies",             "author" => "Freepik",                  "type" => "Specialty",      "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Elegant Icons Font"            		=> array("setting" => "Elegant",                "author" => "Elegant Themes",           "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Entypo Font"                   		=> array("setting" => "Entypo",                 "author" => "Entypo",                   "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Foundation Font"               		=> array("setting" => "Foundation",             "author" => "ZURB University",          "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Genericons Font"               		=> array("setting" => "Genericons",             "author" => "Automatic",                "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "IcoMoon Font"                  		=> array("setting" => "IcoMoon",                "author" => "Keyamoon",                 "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Ionicons Font"                  		=> array("setting" => "Ionicons",               "author" => "Ionic Framework",          "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "MapIcons Font"                		    => array("setting" => "MapIcons",               "author" => "Scott De Jonge",           "type" => "Specialty",      "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Metrize Font"                		    => array("setting" => "Metrize",                "author" => "Alessio Atzeni",           "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Monuments Font"                		=> array("setting" => "Monuments",              "author" => "Freepik",                  "type" => "Specialty",      "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Social Media Font"             		=> array("setting" => "SocialMedia",            "author" => "SimpleIcon",               "type" => "Specialty",      "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Themify Font"                 		    => array("setting" => "Themify",                "author" => "Themify",                  "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Typicons Font"                 		=> array("setting" => "Typicons",               "author" => "Stephen Hutchings",        "type" => "Standard",       "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
        "Custom User Font"              		=> array("setting" => "Custom",                 "author" => "Custom User Font",         "type" => "Custom",         "default" => "false",		"active" => "false",    "always" => "false",    "count" => ""),
    );

    $this->TS_VCSC_Visual_Composer_Elements = array(
        "TS Google Charts"						=> array("setting" => "GoogleCharts",           "file" => "google_charts",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Google Maps"						=> array("setting" => "GoogleMaps",             "file" => "google_maps",       			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Google Docs"						=> array("setting" => "GoogleDocs",             "file" => "google_docs",       			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Google Trends"						=> array("setting" => "GoogleTrends",           "file" => "google_trends",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Icon Fonts"							=> array("setting" => "FontIcons",              "file" => "icon",              			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Icon Box"							=> array("setting" => "IconBox",                "file" => "icon_box_tiny",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Icon Box (Deprecated)"				=> array("setting" => "IconBoxOld",             "file" => "icon_box",          			"type" => "internal",		"default" => "false",		"active" => "false",	"deprecated" => "true",     "required" => ""),
        "TS Icon List Item"						=> array("setting" => "IconList",               "file" => "icon_listitem",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Icon Button"						=> array("setting" => "IconButton",             "file" => "icon_button",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Icon Title"							=> array("setting" => "IconTitle",              "file" => "icon_title",        			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Counter Icon"						=> array("setting" => "IconCounter",            "file" => "icon_counter",      			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Counter Circle"						=> array("setting" => "CircleCounter",          "file" => "circliful",         			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Countdown"							=> array("setting" => "Countdown",              "file" => "countdown",         			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Content Flip"						=> array("setting" => "ContentFlip",            "file" => "content_flip",      			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Meet The Team (Deprecated)"			=> array("setting" => "MeetTeamOld",            "file" => "meet_team",         			"type" => "internal",		"default" => "false",		"active" => "false",	"deprecated" => "true",     "required" => ""),
        "TS Single Teammate (Deprecated)"		=> array("setting" => "TeammateOld",            "file" => "teammates_old",         		"type" => "class",			"default" => "false",		"active" => "false",	"deprecated" => "true",     "required" => ""),
        "TS Pricing Table"						=> array("setting" => "PricingTable",           "file" => "pricing_table",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Social Networks"					=> array("setting" => "SocialNetworks",         "file" => "social_networks",   			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Processes"							=> array("setting" => "TimelineOld",            "file" => "timeline",          			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Isotope Timeline"					=> array("setting" => "Timeline",        	    "file" => "timelines",          		"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Isotope Posts Grid"					=> array("setting" => "Postsgrid",        	    "file" => "postsgrid",          		"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Posts Slider"					    => array("setting" => "Postsslider",        	"file" => "postsslider",          		"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Posts Ticker"					    => array("setting" => "Poststicker",        	"file" => "poststicker",          		"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Divider"							=> array("setting" => "Divider",                "file" => "divider",           			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Shortcode"							=> array("setting" => "Shortcode",              "file" => "shortcode",         			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),        
        "TS Star Rating"						=> array("setting" => "StarRating",             "file" => "seostars",         			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),      
        "TS QR-Code"							=> array("setting" => "QRCode",                 "file" => "qrcode",            			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Teaser Block"						=> array("setting" => "TeaserBlock",            "file" => "teaser_block",      			"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Textillate"							=> array("setting" => "Textillate",             "file" => "textillate",        			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Overlay"						=> array("setting" => "ImageOverlay",           "file" => "image_overlay",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Adipoli"						=> array("setting" => "ImageAdipoli",           "file" => "image_adipoli",     			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Picstrips"					=> array("setting" => "ImagePicstrips",         "file" => "image_picstrips",   			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Caman"						=> array("setting" => "ImageCaman",             "file" => "image_caman",       			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Switch"						=> array("setting" => "ImageSwitch",            "file" => "image_switch",      			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Full"							=> array("setting" => "ImageFull",        	    "file" => "image_full",      			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Image Hotspot"					    => array("setting" => "ImageHotspot",           "file" => "image_hotspot",    			"type" => "class",		    "default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => "4.3.0"),
        "TS Image iHover"					    => array("setting" => "ImageIHover",            "file" => "image_ihover",    			"type" => "class",		    "default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Lightbox Image"						=> array("setting" => "LightboxImage",          "file" => "lightbox_image",    			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Lightbox Gallery"					=> array("setting" => "LightboxGallery",        "file" => "lightbox_gallery",  			"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Lightbox Gallery (Deprecated)"		=> array("setting" => "LightboxGalleryOld",     "file" => "lightbox_gallery_old",  		"type" => "internal",		"default" => "false",		"active" => "false",	"deprecated" => "true",     "required" => ""),
        "TS Modal Popup"						=> array("setting" => "ModalPopup",             "file" => "modal",             			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS IFrame Embed"						=> array("setting" => "IFrame",                 "file" => "iframe",            			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Audio HTML5"					    => array("setting" => "HTML5Audio",             "file" => "html5audio",       			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Video HTML5"					    => array("setting" => "HTML5Video",             "file" => "html5video",       			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Video DailyMotion"					=> array("setting" => "DailyMotion",            "file" => "dailymotion",       			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Video Vimeo"						=> array("setting" => "Vimeo",                  "file" => "vimeo",             			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Video YouTube"						=> array("setting" => "YouTube",                "file" => "youtube",           			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS YouTube Background"					=> array("setting" => "Background",             "file" => "background",        			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Spacer / Clear"						=> array("setting" => "Spacer",                 "file" => "spacer",            			"type" => "internal",		"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        //"TS Login Form"                       => array("setting" => "LoginForm",              "file" => "loginform",                  "type" => "class",		    "default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Anything Slider"					=> array("setting" => "AnySlider",    		    "file" => "anyslider",  				"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "TS Animations Frame"					=> array("setting" => "Animations",    		    "file" => "animations",  				"type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),        
        "TS Figure Navigation"					=> array("setting" => "FigureNavigation",       "file" => "figure_navigation",          "type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),        
        //"TS Icon Tabs"					    => array("setting" => "IconTabs",    		    "file" => "icontabs",  				    "type" => "class",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
        "GoPricing Table"						=> array("setting" => "GoPricing",              "file" => "gopricing",					"type" => "external",		"default" => "false",		"active" => "false",	"deprecated" => "false",    "required" => ""),
        "QuForm"								=> array("setting" => "Quform",                 "file" => "quform",             		"type" => "external",		"default" => "false",		"active" => "false",	"deprecated" => "false",    "required" => ""),
        "Demos"									=> array("setting" => "Demos",             	    "file" => "demos",             			"type" => "demos",			"default" => "true",		"active" => "true",		"deprecated" => "false",    "required" => ""),
    );
    
    $this->TS_VCSC_TeamPageBuilder_Included = array (
        // Extensions elements
        'TS_VCSC_Team_Page_Featured',
        'TS_VCSC_Team_Page_NameTitle',
        'TS_VCSC_Team_Page_Contact',
        'TS_VCSC_Team_Page_Social',
        'TS_VCSC_Team_Page_Description',
        'TS_VCSC_Team_Page_Opening',
        'TS_VCSC_Team_Page_Download',
        'TS_VCSC_Team_Page_Skills',
        'TS_VCSC_Star_Rating',
        'TS-VCSC-Icon-Counter',
        'TS-VCSC-Circliful',
        'TS-VCSC-Icon-Title',
        'TS_VCSC_Skill_Sets_Standalone',
        'TS-VCSC-Divider',
        'TS-VCSC-Spacer',
        'TS_VCSC_Spacer',
        'TS-VCSC-QRCode',
        // Original VC Elements
        'vc_column_text',
        'vc_toggle',
        'vc_message',
        'vc_separator',
        'vc_text_separator',
    );
    
    $this->TS_VCSC_AnySlider_Excluded = array (
        // Extension Elements
        'TS_VCSC_Anything_Slider',
        'TS_VCSC_Animation_Frame',
        'TS_VCSC_Timeline_Container',
        'TS_VCSC_Timeline_Single',
        'TS_VCSC_Timeline_Break',
        'TS_VCSC_Team_Mates_Single',
        'TS_VCSC_Team_Mates_Slider_Custom',
        'TS_VCSC_Team_Mates_Slider_Category',
        'TS_VCSC_Testimonial_Single',
        'TS_VCSC_Testimonial_Slider_Custom',
        'TS_VCSC_Testimonial_Slider_Category',
        'TS_VCSC_Teaser_Block_Single',
        'TS_VCSC_Teaser_Block_Slider_Custom',
        'TS_VCSC_Logo_Single',
        'TS_VCSC_Logo_Slider_Custom',
        'TS_VCSC_Logo_Slider_Category',
        'TS_VCSC_Posts_Grid_Standalone',
        'TS_VCSC_Posts_Slider_Standalone',
        'TS-VCSC-Image-Full',
        'TS_VCSC_Image_Full',
        'TS-VCSC-YouTube-Background',
        'TS_VCSC_YouTube_Background',
        'TS-VCSC-Google-Charts',
        'TS_VCSC_Google_Charts',
        'TS-VCSC-Google-Maps',
        'TS_VCSC_Google_Maps',
        'TS-VCSC-Google-Trends',
        'TS_VCSC_Google_Trends',
        'TS-VCSC-Google-Docs',
        'TS_VCSC_Google_Docs',
        'TS-VCSC-Textillate',
        'TS_VCSC_Textillate',
        'TS-VCSC-Icon-Title',
        'TS_VCSC_Icon_Title',
        'TS-VCSC-Timeline',
        'TS_VCSC_Timeline',
        'TS-VCSC-Divider',
        'TS_VCSC_Divider',
        'TS-VCSC-Spacer',
        'TS_VCSC_Spacer',
        // Original VC Elements
        'vc_row',
        'vc_gallery',
        'vc_images_carousel',
        'vc_carousel',
        'vc_posts_slider',
        'vc_accordion',
        'vc_tour',
        'vc_tabs',
        'vc_empty_space',
        'vc_pie',
        // WooCommerce Elements
        'woocommerce_cart',
        'woocommerce_checkout',
        'woocommerce_order_tracking',
        'woocommerce_my_account',
        'add_to_cart',
        'add_to_cart_url',
        'product_page',
        'related_products',
        // Other Element
        'layerslider',
        'rev_slider',
    );
    
    $this->TS_VCSC_WooCommerce_Elements = array(
        "Recent Products"						=> array("setting" => "RecentProducts",		    "file" => "recent",             		"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Featured Products"						=> array("setting" => "FeaturedProducts",	    "file" => "featured",             		"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Products on Sale"						=> array("setting" => "SaleProducts",		    "file" => "saleproducts",				"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Top Rated Products"					=> array("setting" => "TopRatedProducts",	    "file" => "toprated",             		"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Top Selling Products"					=> array("setting" => "TopSellingProducts",	    "file" => "topselling",             	"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Related Products"						=> array("setting" => "RelatedProducts",	    "file" => "related",             		"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Single Product Preview"				=> array("setting" => "SingleProduct",		    "file" => "singleproduct",             	"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Single Product Page"					=> array("setting" => "SingleProductPage",	    "file" => "singleproductpage",			"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Multiple Products"						=> array("setting" => "MultipleProducts",	    "file" => "multipleproducts",			"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Add To Cart Button"					=> array("setting" => "AddToCartButton",	    "file" => "addtocartbutton",			"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Add To Cart URL"						=> array("setting" => "AddToCartURL",		    "file" => "addtocarturl",				"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Products by Attribute"					=> array("setting" => "AttributeProducts",	    "file" => "attributes",             	"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Products in Single Category"			=> array("setting" => "SingleCategory",		    "file" => "singlecategory",				"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Product Categories"					=> array("setting" => "MultipleCategories",	    "file" => "multiplecategories",			"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Page - Cart"							=> array("setting" => "ShowCart",			    "file" => "showcart",					"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Page - Checkout"						=> array("setting" => "ShowCheckout",		    "file" => "showcheckout",				"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Page - My Account"						=> array("setting" => "ShowAccount",		    "file" => "showaccount",				"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Page - Order Tracking"					=> array("setting" => "OrderTracking",		    "file" => "tracking",             		"type" => "internal",	    "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Basic Products Slider"				    => array("setting" => "SliderBasic",		    "file" => "custom_slider",             	"type" => "class",	        "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Basic Products Grid"				    => array("setting" => "GridBasic",		        "file" => "custom_grid",             	"type" => "class",	        "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Basic Products Ticker"				    => array("setting" => "TickerBasic",		    "file" => "custom_ticker",             	"type" => "class",	        "default" => "true",		"active" => "true",		"deprecated" => "false"),
        "Single Product Rating"				    => array("setting" => "RatingBasic",		    "file" => "custom_rating",             	"type" => "class",	        "default" => "true",		"active" => "true",		"deprecated" => "false"),
    );
?>