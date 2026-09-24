<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Member Self-Registration
    |--------------------------------------------------------------------------
    |
    | When this is off, /register is not routed at all and the sign-in screen
    | points people at the contact form instead. Members are then created by
    | staff in the CMS under /webcms/members.
    |
    | Turn it back on with MEMBER_REGISTRATION_ENABLED=true — the registration
    | screen, controller, and tests are all still in place.
    |
    */

    'member_registration' => (bool) env('MEMBER_REGISTRATION_ENABLED', false),

];
