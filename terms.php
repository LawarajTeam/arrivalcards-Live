<?php
/**
 * Terms of Service Page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/analytics_functions.php';

$pageTitle = 'Terms of Service - User Agreement | Arrival Cards';
$pageDescription = 'Terms of Service for Arrival Cards. Read our user agreement, acceptable use policy, and terms governing the use of our visa information service.';
$pageKeywords = 'terms of service, user agreement, terms and conditions, website terms, acceptable use policy, visa information terms';

// Track page view
trackPageView(null, 'Terms of Service');

include __DIR__ . '/includes/header.php'; 
?>

<section class="countries-section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <h1>Terms of Service</h1>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                <strong>Last Updated:</strong> February 19, 2026
            </p>
            
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1.5rem; border-radius: 8px; margin: 2rem 0;">
                <p style="margin: 0; line-height: 1.7;">
                    <strong>Important:</strong> By accessing and using Arrival Cards, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our service.
                </p>
            </div>
            
            <h2>1. Acceptance of Terms</h2>
            <p>
                Welcome to Arrival Cards ("Website", "Service", "we", "us", or "our"). These Terms of Service ("Terms") govern your access to and use of our website, services, and content. By accessing or using our Service, you agree to comply with and be bound by these Terms and our Privacy Policy.
            </p>
            
            <h2>2. Description of Service</h2>
            <p>
                Arrival Cards provides informational resources regarding visa requirements, entry documentation, and arrival cards for international travel to 196 countries worldwide. Our services include:
            </p>
            <ul>
                <li>Visa requirement information and guidelines</li>
                <li>Entry documentation details</li>
                <li>Links to official government visa resources</li>
                <li>Multilingual content access (7 languages)</li>
                <li>Search and filtering tools</li>
                <li>Contact and feedback mechanisms</li>
            </ul>
            
            <h2>3. Information Accuracy and Disclaimer</h2>
            
            <h3>3.1 Informational Purposes Only</h3>
            <p>
                The information provided on Arrival Cards is for general informational purposes only. While we strive to maintain accurate and up-to-date information, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability, or availability of the information.
            </p>
            
            <h3>3.2 No Official Affiliation</h3>
            <p>
                Arrival Cards is NOT affiliated with, endorsed by, or connected to any government, embassy, consulate, or official visa processing agency. We are an independent information resource.
            </p>
            
            <h3>3.3 Verification Required</h3>
            <p>
                Visa requirements and entry regulations change frequently and without notice. You MUST verify all information with official embassy websites, consulates, or authorized visa processing agencies before making any travel arrangements or decisions. We strongly recommend:
            </p>
            <ul>
                <li>Checking official embassy or consulate websites</li>
                <li>Contacting embassies directly for confirmation</li>
                <li>Consulting with licensed immigration attorneys for legal advice</li>
                <li>Verifying information within 30 days of your planned travel</li>
            </ul>
            
            <h3>3.4 Limitation of Liability</h3>
            <p>
                Arrival Cards, its owners, operators, employees, and affiliates shall not be liable for any direct, indirect, incidental, consequential, or punitive damages arising from:
            </p>
            <ul>
                <li>Your use of or reliance on information provided on this website</li>
                <li>Denied entry, visa rejection, or travel complications</li>
                <li>Inaccurate, incomplete, or outdated information</li>
                <li>Technical issues, errors, or interruptions in service</li>
                <li>Third-party actions or decisions based on our information</li>
            </ul>
            
            <h2>4. User Conduct and Acceptable Use</h2>
            
            <h3>4.1 Permitted Use</h3>
            <p>
                You may use our Service for lawful, personal, non-commercial purposes to research visa and travel requirements. You agree to:
            </p>
            <ul>
                <li>Use the Service in compliance with all applicable laws and regulations</li>
                <li>Provide accurate information when using contact forms</li>
                <li>Respect intellectual property rights</li>
                <li>Use information responsibly and verify with official sources</li>
            </ul>
            
            <h3>4.2 Prohibited Activities</h3>
            <p>
                You agree NOT to:
            </p>
            <ul>
                <li>Use the Service for any illegal or unauthorized purpose</li>
                <li>Attempt to gain unauthorized access to our systems or databases</li>
                <li>Interfere with or disrupt the Service or servers</li>
                <li>Use automated systems (bots, scrapers) to access the Service</li>
                <li>Reproduce, duplicate, copy, sell, or exploit any content without permission</li>
                <li>Transmit viruses, malware, or other malicious code</li>
                <li>Harass, abuse, or harm other users or our staff</li>
                <li>Submit false, misleading, or spam content</li>
                <li>Misrepresent affiliation with our Service</li>
                <li>Use the Service to provide visa consulting without proper licensing</li>
            </ul>
            
            <h2>5. Intellectual Property Rights</h2>
            
            <h3>5.1 Our Content</h3>
            <p>
                All content on Arrival Cards, including but not limited to text, graphics, logos, icons, images, data compilations, and software, is the property of Arrival Cards or its content suppliers and is protected by international copyright laws.
            </p>
            
            <h3>5.2 Limited License</h3>
            <p>
                We grant you a limited, non-exclusive, non-transferable license to access and use the Service for personal, non-commercial purposes. This license does not include:
            </p>
            <ul>
                <li>Resale or commercial use of the Service or its contents</li>
                <li>Collection and use of product listings, descriptions, or prices</li>
                <li>Derivative use of the Service or its contents</li>
                <li>Any downloading or copying of account information</li>
            </ul>
            
            <h3>5.3 Trademarks</h3>
            <p>
                "Arrival Cards" and related logos are trademarks. You may not use these trademarks without our prior written permission.
            </p>
            
            <h2>6. Third-Party Links</h2>
            <p>
                Our Service contains links to third-party websites, including official government visa portals and embassy websites. These links are provided for your convenience only. We do not:
            </p>
            <ul>
                <li>Control or endorse third-party websites</li>
                <li>Guarantee the accuracy of their content</li>
                <li>Assume responsibility for their privacy practices</li>
                <li>Bear liability for your interactions with third-party sites</li>
            </ul>
            <p>
                Your use of third-party websites is at your own risk and subject to their terms and conditions.
            </p>
            
            <h2>7. User-Generated Content</h2>
            
            <h3>7.1 Contact Form Submissions</h3>
            <p>
                By submitting content through our contact forms, you grant us a non-exclusive, worldwide, royalty-free license to use, reproduce, and respond to your submissions. You represent that:
            </p>
            <ul>
                <li>Your submissions are accurate and not misleading</li>
                <li>Your submissions do not violate any laws or third-party rights</li>
                <li>You own or have permission to submit the content</li>
            </ul>
            
            <h3>7.2 Feedback</h3>
            <p>
                When you provide feedback or suggestions, we may use them without compensation or attribution to improve our Service.
            </p>
            
            <h2>8. Privacy and Data Protection</h2>
            <p>
                Your use of the Service is also governed by our Privacy Policy, which is incorporated into these Terms by reference. Please review our <a href="<?php echo APP_URL; ?>/privacy.php" style="color: #3b82f6;">Privacy Policy</a> to understand our data practices.
            </p>
            
            <h2>9. Service Modifications and Availability</h2>
            
            <h3>9.1 Changes to Service</h3>
            <p>
                We reserve the right to:
            </p>
            <ul>
                <li>Modify, suspend, or discontinue the Service (or any part thereof) at any time</li>
                <li>Change these Terms at any time by posting updated terms</li>
                <li>Update or remove content without notice</li>
            </ul>
            
            <h3>9.2 No Warranty of Availability</h3>
            <p>
                We provide the Service on an "AS IS" and "AS AVAILABLE" basis. We do not guarantee that the Service will be:
            </p>
            <ul>
                <li>Uninterrupted or error-free</li>
                <li>Free from viruses or harmful components</li>
                <li>Available at all times or locations</li>
                <li>Secure from unauthorized access</li>
            </ul>
            
            <h2>10. Indemnification</h2>
            <p>
                You agree to indemnify, defend, and hold harmless Arrival Cards and its officers, directors, employees, and agents from any claims, liabilities, damages, losses, costs, or expenses (including reasonable attorneys' fees) arising from:
            </p>
            <ul>
                <li>Your use or misuse of the Service</li>
                <li>Your violation of these Terms</li>
                <li>Your violation of any third-party rights</li>
                <li>Any content you submit or transmit</li>
            </ul>
            
            <h2>11. Governing Law and Jurisdiction</h2>
            <p>
                These Terms shall be governed by and construed in accordance with applicable international laws. Any disputes arising from these Terms or your use of the Service shall be subject to the exclusive jurisdiction of the appropriate courts.
            </p>
            
            <h2>12. Dispute Resolution</h2>
            <p>
                If you have a dispute with us, please contact us first through our <a href="<?php echo APP_URL; ?>/contact.php" style="color: #3b82f6;">contact form</a>. We will attempt to resolve disputes amicably through good faith negotiations.
            </p>
            
            <h2>13. Severability</h2>
            <p>
                If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions shall continue in full force and effect. The invalid provision shall be modified to the minimum extent necessary to make it valid and enforceable.
            </p>
            
            <h2>14. Entire Agreement</h2>
            <p>
                These Terms, together with our Privacy Policy, constitute the entire agreement between you and Arrival Cards regarding your use of the Service and supersede all prior agreements and understandings.
            </p>
            
            <h2>15. No Waiver</h2>
            <p>
                Our failure to enforce any right or provision of these Terms shall not constitute a waiver of such right or provision. All waivers must be in writing.
            </p>
            
            <h2>16. Contact Information</h2>
            <p>
                If you have questions about these Terms of Service, please contact us:
            </p>
            <div style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0;">
                <p style="margin: 0 0 0.5rem;"><strong>Email:</strong> Via our <a href="<?php echo APP_URL; ?>/contact.php" style="color: #3b82f6;">contact form</a></p>
                <p style="margin: 0;"><strong>Website:</strong> <?php echo APP_URL; ?></p>
            </div>
            
            <h2>17. Changes to Terms</h2>
            <p>
                We reserve the right to update these Terms at any time. Changes will be effective immediately upon posting to this page with an updated "Last Updated" date. Your continued use of the Service after changes constitutes acceptance of the modified Terms.
            </p>
            
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1.5rem; border-radius: 8px; margin: 2rem 0;">
                <p style="margin: 0; line-height: 1.7;">
                    <strong>💡 Recommendation:</strong> Review these Terms periodically to stay informed of any changes. Your continued use of Arrival Cards signifies your acceptance of any modifications.
                </p>
            </div>
            
            <p style="text-align: center; margin: 3rem 0 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); color: var(--text-secondary);">
                By using Arrival Cards, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
            </p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
