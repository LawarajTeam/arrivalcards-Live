<?php
/**
 * Privacy Policy Page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy - Data Protection | Arrival Cards';
$pageDescription = 'Privacy policy for Arrival Cards. Learn how we protect your data while providing visa and travel information for 196 countries worldwide.';
$pageKeywords = 'privacy policy, data protection, user privacy, GDPR, travel data privacy, visa information privacy';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="countries-section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <h1>Privacy Policy</h1>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                <strong>Last Updated:</strong> February 5, 2026
            </p>
            
            <h2>Introduction</h2>
            <p>
                Welcome to Arrival Cards ("we," "our," or "us"). We are committed to protecting your privacy and ensuring you have a positive experience on our website. This policy outlines our practices concerning the collection, use, and disclosure of your information.
            </p>
            
            <h2>Information We Collect</h2>
            <h3>Information You Provide</h3>
            <p>
                When you use our contact form, we collect:
            </p>
            <ul>
                <li>Your name</li>
                <li>Your email address</li>
                <li>Your message content</li>
                <li>The date and time of submission</li>
            </ul>
            
            <h3>Automatically Collected Information</h3>
            <p>
                When you visit our website, we automatically collect:
            </p>
            <ul>
                <li>IP address</li>
                <li>Browser type and version</li>
                <li>Operating system</li>
                <li>Language preferences</li>
                <li>Pages visited and time spent on pages</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <p>We use the collected information to:</p>
            <ul>
                <li>Respond to your inquiries and provide customer support</li>
                <li>Improve our website and user experience</li>
                <li>Detect and prevent fraud or abuse</li>
                <li>Comply with legal obligations</li>
                <li>Provide you with the most relevant visa information based on your language preference</li>
            </ul>
            
            <h2>Cookies and Tracking</h2>
            <p>
                We use session cookies to:
            </p>
            <ul>
                <li>Remember your language preference</li>
                <li>Maintain security features</li>
                <li>Improve website functionality</li>
            </ul>
            <p>
                You can disable cookies in your browser settings, but this may affect website functionality.
            </p>
            
            <h2>Data Sharing and Disclosure</h2>
            <p>
                We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
            </p>
            <ul>
                <li><strong>Legal Requirements:</strong> When required by law or to protect our legal rights</li>
                <li><strong>Service Providers:</strong> With trusted service providers who assist in operating our website (under strict confidentiality agreements)</li>
                <li><strong>Business Transfers:</strong> In connection with any merger, sale, or acquisition of all or part of our business</li>
            </ul>
            
            <h2>Third-Party Links</h2>
            <p>
                Our website contains links to official government visa websites. We are not responsible for the privacy practices or content of these third-party sites. We recommend reviewing their privacy policies before providing any personal information.
            </p>
            
            <h2>Data Security</h2>
            <p>
                We implement appropriate technical and organizational security measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the internet is 100% secure.
            </p>
            
            <h2>Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li><strong>Access:</strong> Request a copy of your personal data we hold</li>
                <li><strong>Correction:</strong> Request correction of inaccurate or incomplete data</li>
                <li><strong>Deletion:</strong> Request deletion of your personal data</li>
                <li><strong>Objection:</strong> Object to the processing of your personal data</li>
                <li><strong>Portability:</strong> Request transfer of your data to another service</li>
            </ul>
            <p>
                To exercise these rights, please contact us at: <a href="mailto:<?php echo ADMIN_EMAIL; ?>"><?php echo ADMIN_EMAIL; ?></a>
            </p>
            
            <h2>Children's Privacy</h2>
            <p>
                Our website is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13. If you believe we have collected such information, please contact us immediately.
            </p>
            
            <h2>International Data Transfers</h2>
            <p>
                Your information may be transferred to and processed in countries other than your country of residence. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.
            </p>
            
            <h2>Changes to This Policy</h2>
            <p>
                We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last Updated" date. We encourage you to review this policy periodically.
            </p>
            
            <h2>Disclaimer</h2>
            <p>
                <strong>Important:</strong> The visa information provided on this website is for general guidance only and may not reflect the most current requirements. Always verify entry requirements with official government sources before traveling. We are not responsible for any consequences arising from reliance on information provided on this website.
            </p>
            
            <h2>Contact Us</h2>
            <p>
                If you have any questions about this Privacy Policy or our data practices, please contact us:
            </p>
            <p>
                <strong>Email:</strong> <a href="mailto:<?php echo ADMIN_EMAIL; ?>"><?php echo ADMIN_EMAIL; ?></a><br>
                <strong>Website:</strong> <a href="<?php echo APP_URL; ?>"><?php echo APP_URL; ?></a>
            </p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
