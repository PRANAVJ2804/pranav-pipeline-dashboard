<?php
// Copy this file to config.php (same folder) and fill it in. NEVER commit config.php.
return array(
    // Full Bitrix24 inbound-webhook URL, including the last slash. Treat it like a password.
    // Give the webhook only the "crm" scope.
    'webhook_url' => 'PASTE_YOUR_BITRIX24_WEBHOOK_LINK_HERE',

    // Main admin login: sees ALL deals. Use a long, unique password.
    'username' => 'admin',
    'password' => 'PASTE_A_LONG_UNIQUE_PASSWORD',

    // Optional: more admins (also see everything). Delete the block if not needed.
    'extra_users' => array(
        // 'second_admin' => 'PASTE_ANOTHER_LONG_UNIQUE_PASSWORD',
    ),

    // Optional: team logins. Each one sees (and can edit) ONLY deals whose "Team Name" matches.
    'team_users' => array(
        // 'team_a_login' => array('password' => 'PASTE_A_LONG_UNIQUE_PASSWORD', 'team' => 'Team A'),
    ),

    // The Bitrix24 pipeline (Category ID) to show.
    'pipeline_id' => 46,

    // ---- optional settings (defaults shown) ----
    // 'allow_edit'     => true,                 // false = read-only dashboard
    // 'stale_days'     => 15,                   // "follow-up overdue" after this many days without a comment
    // 'closed_stages'  => array('C46:WON', 'C46:LOSE'),  // stages treated as closed (default: guessed from names)
    // 'editable_extra' => array('UF_CRM_XXXXXXXXXX'),    // extra custom fields allowed in the edit form
    // 'cache_dir'      => '/home/youruser/dashboard-cache', // keep the cache OUTSIDE public_html if you can
);
