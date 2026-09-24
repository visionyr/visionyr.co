<?php

/*
|--------------------------------------------------------------------------
| Legal Documents
|--------------------------------------------------------------------------
|
| Content for /privacy and /terms. Each section becomes a heading, an anchor,
| and an entry in the on-page contents list.
|
| A "body" is a list of blocks: a string is a paragraph, and ['list' => [...]]
| is a bullet list.
|
| NOTE: this is drafted as a starting point for a product of this shape. It has
| not been reviewed by a lawyer — have counsel check it before launch.
|
*/

$company = 'Visionyr';
$site = 'visionyr.co';
$email = 'hello@visionyr.co';
$effective = '22 September 2026';

return [

    'privacy' => [
        'slug' => 'privacy',
        'eyebrow' => 'Legal',
        'title' => 'Privacy Policy',
        'intro' => "How {$company} collects, uses, and protects the information you share with us.",
        'effective' => $effective,

        'sections' => [
            [
                'id' => 'introduction',
                'heading' => 'Introduction',
                'body' => [
                    "{$company} (\"we\", \"us\", \"our\") operates {$site} and the {$company} platform, an AI brand builder that helps founders create and grow brands. This policy explains what we collect when you use the service, why we collect it, and what you can do about it.",
                    'By using the service you agree to this policy. If you do not agree with it, please do not use the service.',
                ],
            ],
            [
                'id' => 'information-we-collect',
                'heading' => 'Information we collect',
                'body' => [
                    'We collect only what the service needs to work.',
                    ['list' => [
                        'Account information — your name, email address, and phone number when you register, plus a securely hashed password. We never store your password in readable form.',
                        'Brand inputs — the brand name, industry, target audience, price position, and brand vision you enter to generate a Brand Blueprint, together with the blueprint we generate from them.',
                        'Enquiries — the name, email address, phone number, and message you send through our contact form.',
                        'Technical data — IP address, browser type, device information, and pages visited, collected automatically so we can keep the service secure and working.',
                        'Payment data — if you subscribe to a paid plan, our payment processor handles your card details. We receive only the billing status and the last few digits of the card; we never see or store full card numbers.',
                    ]],
                ],
            ],
            [
                'id' => 'how-we-use-information',
                'heading' => 'How we use your information',
                'body' => [
                    'We use the information we collect to:',
                    ['list' => [
                        'Create and operate your account, and sign you in.',
                        'Generate, store, and display your Brand Blueprints so you can return to them.',
                        'Respond to enquiries you send us.',
                        'Take payment for paid plans and issue receipts.',
                        'Keep the service secure — detecting abuse, rate limiting sign-in attempts, and investigating incidents.',
                        'Understand which parts of the service are used, in aggregate, so we can improve them.',
                        'Send service messages about your account. We will only send marketing email if you have asked for it, and every marketing email has an unsubscribe link.',
                    ]],
                    'We do not sell your personal information, and we do not use your brand inputs to train models for anyone else.',
                ],
            ],
            [
                'id' => 'your-brand-inputs',
                'heading' => 'Your brand inputs',
                'body' => [
                    'The vision, audience, and positioning you describe are commercially sensitive, and we treat them that way. Your brand inputs and the blueprints generated from them belong to you.',
                    'We use them to produce your blueprint and to show it back to you. We do not publish them, share them with other users, or sell them. Where generation is performed by a third-party AI provider, we send only what is needed to produce your result, under contracts that prohibit that provider from using it to train their models.',
                    'A blueprint is reachable by its unique link. Anyone who has that link can view that blueprint, so share it only with people you intend to see it.',
                ],
            ],
            [
                'id' => 'sharing',
                'heading' => 'When we share information',
                'body' => [
                    'We share personal information only in these situations:',
                    ['list' => [
                        'Service providers — hosting, database, email delivery, payment processing, and AI generation providers who process data on our instructions and are bound to protect it.',
                        'Legal obligations — where we are required by law, regulation, or a valid legal request.',
                        'Protecting rights — where disclosure is reasonably necessary to protect the safety, rights, or property of our users, the public, or us.',
                        'Business transfers — if we are involved in a merger, acquisition, or sale of assets, in which case we will tell you before your information is transferred.',
                    ]],
                ],
            ],
            [
                'id' => 'retention',
                'heading' => 'How long we keep it',
                'body' => [
                    'We keep your account information and blueprints for as long as your account is open. If you close your account, we delete or anonymise your personal information within 90 days, except where we must keep records longer to meet legal, tax, or accounting obligations.',
                    'Contact enquiries are kept for up to 24 months so we can follow up and keep a record of correspondence.',
                ],
            ],
            [
                'id' => 'security',
                'heading' => 'How we protect it',
                'body' => [
                    'We use industry-standard measures to protect your information: encrypted connections, hashed passwords, access controls limiting who on our team can reach production data, and rate limiting on sign-in.',
                    'No system is perfectly secure. If a breach affects your personal information, we will notify you and the relevant authority as required by law.',
                ],
            ],
            [
                'id' => 'your-rights',
                'heading' => 'Your rights',
                'body' => [
                    'Depending on where you live, you may have the right to:',
                    ['list' => [
                        'Access the personal information we hold about you.',
                        'Correct information that is wrong or incomplete.',
                        'Delete your account and the personal information attached to it.',
                        'Export your information in a portable format.',
                        'Object to or restrict certain processing.',
                        'Withdraw consent where our processing relies on it.',
                    ]],
                    "To exercise any of these, email us at {$email}. We will respond within 30 days. We may need to verify your identity first.",
                ],
            ],
            [
                'id' => 'cookies',
                'heading' => 'Cookies',
                'body' => [
                    'We use a small number of cookies, and only for things the service needs:',
                    ['list' => [
                        'A session cookie that keeps you signed in.',
                        'A security token that protects forms against cross-site request forgery.',
                        'A preference cookie, if you choose "keep me signed in".',
                    ]],
                    'You can block cookies in your browser, but the service will not be able to sign you in without them.',
                ],
            ],
            [
                'id' => 'international-transfers',
                'heading' => 'International transfers',
                'body' => [
                    'We are based in Indonesia, and some of our service providers operate in other countries. Where your information is transferred outside your country, we take steps to ensure it remains protected by appropriate safeguards.',
                ],
            ],
            [
                'id' => 'children',
                'heading' => "Children's privacy",
                'body' => [
                    "The service is not intended for anyone under 18, and we do not knowingly collect information from children. If you believe a child has given us personal information, contact us at {$email} and we will delete it.",
                ],
            ],
            [
                'id' => 'changes',
                'heading' => 'Changes to this policy',
                'body' => [
                    'We may update this policy as the service changes. When we do, we will update the effective date at the top of this page, and for significant changes we will notify you by email or in the app before they take effect.',
                ],
            ],
            [
                'id' => 'contact',
                'heading' => 'Contact us',
                'body' => [
                    "Questions about this policy, or about your information? Email {$email}, or use the contact form on this site and we will get back to you.",
                ],
            ],
        ],
    ],

    'terms' => [
        'slug' => 'terms',
        'eyebrow' => 'Legal',
        'title' => 'Terms & Conditions',
        'intro' => "The agreement between you and {$company} when you use the platform.",
        'effective' => $effective,

        'sections' => [
            [
                'id' => 'agreement',
                'heading' => 'Agreement to these terms',
                'body' => [
                    "These terms are an agreement between you and {$company} (\"we\", \"us\", \"our\") governing your use of {$site} and the {$company} platform (the \"service\").",
                    'By creating an account, generating a Brand Blueprint, or otherwise using the service, you agree to these terms. If you are using the service for a company, you confirm you are authorised to accept these terms on its behalf.',
                ],
            ],
            [
                'id' => 'eligibility',
                'heading' => 'Eligibility and your account',
                'body' => [
                    'You must be at least 18 years old to use the service.',
                    ['list' => [
                        'Give accurate registration details and keep them up to date.',
                        'Keep your password confidential. You are responsible for everything that happens under your account.',
                        "Tell us promptly at {$email} if you believe your account has been accessed without your permission.",
                        'Do not share an account between people. Team access is available on the Studio plan.',
                    ]],
                ],
            ],
            [
                'id' => 'the-service',
                'heading' => 'What the service does',
                'body' => [
                    "{$company} generates brand strategy material from the information you provide: positioning, audience, personality, messaging, naming, visual direction, launch strategy, and related outputs, delivered as a Brand Blueprint and the surrounding Create and Accelerate tools.",
                    'We may add, change, or remove features as the product develops. If we remove something you depend on, we will give reasonable notice where we can.',
                ],
            ],
            [
                'id' => 'your-content',
                'heading' => 'Your content',
                'body' => [
                    'You keep all rights to the information you put into the service — your brand name, vision, audience description, and anything else you enter. We call this "your content".',
                    'You grant us a limited licence to host, process, and display your content solely to operate the service for you, including sending the necessary parts to the AI providers that produce your results. This licence ends when you delete the content or close your account.',
                    'You confirm that you have the right to submit your content and that it does not infringe anyone else\'s rights.',
                ],
            ],
            [
                'id' => 'outputs',
                'heading' => 'Blueprints and outputs',
                'body' => [
                    'Subject to your account being in good standing, you own the outputs the service generates for you and may use them commercially — including in your branding, marketing, and product materials.',
                    'Because outputs are generated from patterns in language, similar inputs from different users can produce similar outputs. We cannot and do not guarantee that any output is unique to you, and we do not grant exclusivity over it.',
                    'Before you commit to a name, tagline, or visual direction, run your own trademark, domain, and availability checks. Doing so is your responsibility.',
                ],
            ],
            [
                'id' => 'ai-outputs',
                'heading' => 'About AI-generated material',
                'body' => [
                    'The service produces suggestions, not professional advice. Outputs are generated automatically and may be inaccurate, incomplete, generic, or unsuitable for your situation.',
                    ['list' => [
                        'Treat every output as a starting point to review and adapt, not a finished deliverable.',
                        'Scores, percentages, and projections shown in the product are indicative, not measurements or forecasts of real performance.',
                        'Nothing the service produces is legal, financial, tax, or professional advice.',
                        'You are responsible for what you publish under your brand.',
                    ]],
                ],
            ],
            [
                'id' => 'acceptable-use',
                'heading' => 'Acceptable use',
                'body' => [
                    'You agree not to:',
                    ['list' => [
                        'Use the service for anything unlawful, or to build a brand for an unlawful product or service.',
                        'Impersonate another brand or person, or generate material designed to mislead people about who they are dealing with.',
                        'Infringe anyone\'s intellectual property, or submit content you do not have the right to use.',
                        'Attempt to access other users\' accounts, blueprints, or data.',
                        'Probe, scan, or test the security of the service, or interfere with its normal operation.',
                        'Scrape the service, or use bots or automated means to extract data at scale.',
                        'Resell or redistribute the service itself, or use it to operate a competing brand generation product.',
                        'Circumvent usage limits, rate limits, or plan restrictions.',
                    ]],
                ],
            ],
            [
                'id' => 'plans-and-billing',
                'heading' => 'Plans, billing, and cancellation',
                'body' => [
                    'The Explorer plan is free and offers limited previews. Builder and Studio are paid subscriptions billed monthly in advance, in Indonesian Rupiah, through our payment processor.',
                    ['list' => [
                        'Subscriptions renew automatically each month until you cancel.',
                        'You can cancel at any time from your account. Your plan stays active until the end of the period you have paid for, and is not renewed after that.',
                        'Payments already made are non-refundable except where required by law.',
                        'We may change our prices. We will give at least 30 days\' notice before a change affects your subscription, and you may cancel before it takes effect.',
                        'If a payment fails, we may suspend access to paid features until it is settled.',
                    ]],
                ],
            ],
            [
                'id' => 'intellectual-property',
                'heading' => 'Our intellectual property',
                'body' => [
                    "The service itself — the platform, software, design, and the {$company}, Brand Blueprint, Growth Blueprint, Create, and Accelerate names and marks — belongs to us and is protected by intellectual property law.",
                    'These terms give you a limited, non-exclusive, non-transferable right to use the service. They do not transfer ownership of it, and you may not copy, modify, reverse engineer, or create derivative works from the platform.',
                ],
            ],
            [
                'id' => 'third-parties',
                'heading' => 'Third-party services',
                'body' => [
                    'The service relies on third parties for hosting, payment processing, email delivery, and AI generation. Their availability and performance are outside our control, and their own terms apply to their part of the service.',
                    'Links to third-party sites are provided for convenience. We do not control or endorse them and are not responsible for their content.',
                ],
            ],
            [
                'id' => 'availability',
                'heading' => 'Availability',
                'body' => [
                    'We work to keep the service available, but we do not guarantee uninterrupted access. The service may be unavailable during maintenance, updates, or circumstances beyond our control.',
                    'We provide the service "as is" and "as available", without warranties of any kind, whether express or implied, including any implied warranties of merchantability, fitness for a particular purpose, and non-infringement.',
                ],
            ],
            [
                'id' => 'liability',
                'heading' => 'Limitation of liability',
                'body' => [
                    'To the fullest extent permitted by law, we are not liable for indirect, incidental, special, consequential, or punitive damages, or for lost profits, lost revenue, lost data, or lost business opportunities, arising from your use of the service.',
                    'Our total liability for any claim relating to the service is limited to the amount you paid us in the 12 months before the claim arose, or IDR 1,000,000 if you have not paid us anything.',
                    'Some jurisdictions do not allow these limitations. Where that is the case, these limits apply to the fullest extent the law permits.',
                ],
            ],
            [
                'id' => 'indemnity',
                'heading' => 'Indemnity',
                'body' => [
                    'You agree to indemnify and hold us harmless from claims, damages, and reasonable costs arising from your content, your use of the outputs, or your breach of these terms or of anyone else\'s rights.',
                ],
            ],
            [
                'id' => 'termination',
                'heading' => 'Suspension and termination',
                'body' => [
                    'You can close your account at any time. We may suspend or terminate an account that breaches these terms, that creates risk or legal exposure, or that has been inactive for an extended period.',
                    'When an account ends, your right to use the service stops immediately. Sections that by their nature should survive — ownership, disclaimers, liability, and indemnity — continue to apply.',
                ],
            ],
            [
                'id' => 'changes',
                'heading' => 'Changes to these terms',
                'body' => [
                    'We may update these terms as the service develops. We will update the effective date at the top of this page, and for material changes we will give notice by email or in the app before they take effect. Continuing to use the service after the change means you accept the updated terms.',
                ],
            ],
            [
                'id' => 'governing-law',
                'heading' => 'Governing law',
                'body' => [
                    'These terms are governed by the laws of the Republic of Indonesia. Any dispute that cannot be resolved between us will be subject to the exclusive jurisdiction of the courts of Jakarta, Indonesia.',
                    'Before starting formal proceedings, we ask that you contact us so we can try to resolve the matter directly.',
                ],
            ],
            [
                'id' => 'contact',
                'heading' => 'Contact us',
                'body' => [
                    "Questions about these terms? Email {$email}, or use the contact form on this site.",
                ],
            ],
        ],
    ],

];
