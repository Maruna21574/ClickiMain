<?php
/**
 * Legal texts (EN): privacy policy and cookies.
 */
return [
    'privacy' => [
        'meta_title' => 'Privacy policy (GDPR) — Clicki',
        'meta_desc' => 'How Clicki processes personal data: what we collect, why, how long we keep it and what rights you have.',
        'eyebrow' => 'Legal information',
        'title' => 'Privacy policy',
        'intro' => 'We take your privacy seriously. This page explains what personal data we process, why we do it, how long we keep it and how you can exercise your rights under the GDPR.',
        'updated_label' => 'Last updated',
        'updated' => '10 October 2026',
        'sections' => [
            [
                'h' => '1. Who is the controller',
                'p' => ['The controller of personal data is Clicki (“we”). You can contact us at any time at the e-mail address below.'],
            ],
            [
                'h' => '2. What data we process and why',
                'p' => ['We only process data we need for a specific purpose:'],
                'ul' => [
                    '**Contact form and quote request:** name, e-mail, phone (optional), website address (optional), budget (optional) and your message. We use them to reply and prepare a quote. Legal basis: steps taken at your request prior to entering into a contract and our legitimate interest in answering enquiries.',
                    '**Quote configurator:** the options you choose together with the contact details you enter. They are used to prepare a quote.',
                    '**E-mail communication:** the content and data of correspondence with us so that we can handle your request.',
                    '**Technical data:** when you visit the website the server automatically records technical data (e.g. IP address, browser type, time of access). We use it for security and for the correct operation of the site.',
                ],
            ],
            [
                'h' => '3. How long we keep data',
                'ul' => [
                    'Data from enquiries is kept for as long as needed to handle the enquiry and then for up to 3 years if no contract arises.',
                    'If you become our client, we keep data for the duration of the cooperation and then for the period required by law (e.g. accounting documents).',
                    'Server technical logs are kept only for as long as necessary.',
                ],
            ],
            [
                'h' => '4. Who we share data with',
                'p' => ['We do not sell your data. It may only be processed by our suppliers who help us run the service, under contract and to the necessary extent:'],
                'ul' => [
                    'hosting and e-mail service provider,',
                    'web font provider (Google Fonts), which receives your IP address when the page loads,',
                    'accountants or other advisers, where necessary to meet our legal obligations.',
                ],
            ],
            [
                'h' => '5. Cookies',
                'p' => ['The website uses only strictly necessary cookies needed for it to work. Details are on the Cookies page.'],
            ],
            [
                'h' => '6. Your rights',
                'p' => ['Under the GDPR you have the right to:'],
                'ul' => [
                    'request access to your data and obtain a copy,',
                    'request correction of inaccurate data,',
                    'request erasure of data (“right to be forgotten”) where we have no reason to process it,',
                    'request restriction of processing or object to processing based on legitimate interest,',
                    'request data portability,',
                    'withdraw consent at any time where processing is based on consent,',
                    'lodge a complaint with the supervisory authority, the Office for Personal Data Protection of the Slovak Republic (dataprotection.gov.sk).',
                ],
                'p2' => ['You can exercise your rights by e-mail at the address below. We will reply without undue delay and within one month at the latest.'],
            ],
            [
                'h' => '7. Data security',
                'p' => ['We protect data with appropriate technical and organisational measures: encrypted connection (HTTPS), restricted access and regular system updates.'],
            ],
            [
                'h' => '8. Changes to this document',
                'p' => ['We may update this document if the way we process data or the legislation changes. The current version is always published on this page.'],
            ],
        ],
        'contact_h' => 'Data protection contact',
        'contact_p' => 'For questions about personal data protection you can reach us at:',
        'operator_h' => 'Controller details',
    ],

    'cookies' => [
        'meta_title' => 'Cookies — Clicki',
        'meta_desc' => 'Which cookies the Clicki website uses, what they are for, how long they are kept and how you can manage them.',
        'eyebrow' => 'Legal information',
        'title' => 'Cookies',
        'intro' => 'Cookies are small text files that a website stores in your browser. Some are necessary for the site to work, others are used, for example, to measure traffic. Here is an overview of what we use.',
        'updated_label' => 'Last updated',
        'updated' => '10 October 2026',
        'sections' => [
            [
                'h' => 'What we use',
                'p' => ['We use **only strictly necessary cookies**. We do not use any analytics, advertising or third-party tracking cookies. If this changes in the future, we will ask for your consent first.'],
            ],
            [
                'h' => 'What are necessary cookies',
                'p' => ['The site would not work properly without these cookies, so no consent is required for them. They are used, for example, to remember your chosen language and to protect forms against abuse.'],
            ],
            [
                'h' => 'How to manage cookies',
                'p' => ['You can delete or block cookies at any time in your browser settings. If you block necessary cookies, some parts of the site (such as sending forms or switching language) may not work properly.', 'You can change your choice in the cookie bar at any time using the button further down this page.'],
            ],
            [
                'h' => 'External resources',
                'p' => ['To display fonts we load files from Google Fonts. Your IP address is transferred to the service provider when they load. These are not cookies, but we want to be transparent about it from a data protection point of view. Details are in the privacy policy.'],
            ],
        ],
        'table_h' => 'List of cookies',
        'table_cols' => ['Name', 'Purpose', 'Type', 'Duration'],
        'table' => [
            ['PHPSESSID', 'Keeps your visit together: remembers the chosen language and protects forms against abuse (security token).', 'Necessary', 'Until the browser is closed'],
            ['clicki_consent', 'Stores your choice in the cookie bar so that it is not shown on every visit.', 'Necessary', '12 months'],
        ],
        'settings_h' => 'Settings',
        'settings_p' => 'You can change your choice at any time:',
        'settings_btn' => 'Change cookie settings',
        'privacy_link' => 'Privacy policy',
    ],
];
