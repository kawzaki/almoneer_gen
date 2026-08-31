<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Kalema Legacy IDs
    |--------------------------------------------------------------------------
    |
    | These IDs correspond to critical categories in the imported legacy database.
    | Changing these will affect how the navigation and indexes are generated.
    |
    */

    'ids' => [
        'magazine_root' => 1,           // The root category for issues
        'recent_publications' => 5,      // Secondary publications section
        'subject_index' => 7,            // The "Topic Index" (تصنيف المواضيع)
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar Configuration
    |--------------------------------------------------------------------------
    |
    | Defines common limits for sidebar widgets.
    |
    */
    
    'sidebar' => [
        'most_read_limit' => 5,
        'latest_albums_limit' => 4,
    ],

];
