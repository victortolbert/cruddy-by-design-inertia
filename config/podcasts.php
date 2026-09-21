<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cover Image Disk
    |--------------------------------------------------------------------------
    |
    | Podcast cover art is written to this filesystem disk. The public disk
    | works for Herd/Sail; point it at object storage once that is provisioned.
    |
    */

    'cover_disk' => env('PODCASTS_COVER_DISK', 'public'),

];
