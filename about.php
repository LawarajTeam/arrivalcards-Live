<?php
/**
 * About Us Page
 * Provides information about the Arrival Cards service
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/analytics_functions.php';

$pageTitle = 'About Us - Your Trusted Travel Documentation Resource | Arrival Cards';
$pageDescription = 'Learn about Arrival Cards - your comprehensive guide to visa requirements, entry documentation, and arrival cards for 196 countries. Trusted by travelers worldwide since 2026.';
$pageKeywords = 'about arrival cards, visa information service, travel documentation guide, trusted visa resource, international travel help';

// Track page view
trackPageView(null, 'About Us');

include __DIR__ . '/includes/header.php'; 
?>

<style>
@media (max-width: 768px) {
    .about-mission { padding: 1.5rem !important; }
    .about-offer-grid { grid-template-columns: 1fr !important; }
    .about-stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .about-stat-value { font-size: 1.8rem !important; }
    .about-section-spacing { margin-bottom: 2rem !important; margin-top: 2rem !important; }
    .about-padded { padding: 1.5rem !important; }
    .about-cta { padding: 1.5rem !important; }
}
@media (max-width: 480px) {
    .about-stats-grid { grid-template-columns: 1fr !important; }
    .about-stat-value { font-size: 1.5rem !important; }
    .about-mission { padding: 1.25rem !important; }
    .about-padded { padding: 1.25rem !important; }
    .about-cta { padding: 1.25rem !important; }
}
</style>

<section class="countries-section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1>About Arrival Cards</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 3rem;">
                Your trusted companion for international travel documentation
            </p>
            
            <!-- Mission Section -->
            <div class="about-mission" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 2.5rem; border-radius: 12px; margin-bottom: 3rem; box-shadow: 0 4px 16px rgba(30, 58, 138, 0.2);">
                <h2 style="color: white; margin-bottom: 1rem; font-size: 1.8rem;">Our Mission</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; margin: 0;">
                    At Arrival Cards, we believe that international travel should be accessible and stress-free for everyone. Our mission is to provide clear, accurate, and up-to-date visa and entry requirement information for all 196 countries worldwide, helping millions of travelers prepare confidently for their journeys.
                </p>
            </div>
            
            <!-- What We Offer -->
            <h2 style="margin-top: 3rem;">What We Offer</h2>
            <div class="about-offer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin: 2rem 0 3rem;">
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6;">
                    <h3 style="color: #1e3a8a; margin-bottom: 0.75rem;">🌍 196 Countries</h3>
                    <p style="color: var(--text-secondary); margin: 0; line-height: 1.7;">
                        Comprehensive visa and entry requirement information for every country in the world, all in one convenient location.
                    </p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
                    <h3 style="color: #059669; margin-bottom: 0.75rem;">🗣️ 7 Languages</h3>
                    <p style="color: var(--text-secondary); margin: 0; line-height: 1.7;">
                        Access information in English, Spanish, Chinese (Simplified), French, German, Japanese, and Arabic to serve global travelers.
                    </p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
                    <h3 style="color: #d97706; margin-bottom: 0.75rem;">🔍 Smart Search</h3>
                    <p style="color: var(--text-secondary); margin: 0; line-height: 1.7;">
                        Find what you need instantly with our powerful search and filtering tools. Search by country, region, or visa type.
                    </p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #8b5cf6;">
                    <h3 style="color: #7c3aed; margin-bottom: 0.75rem;">✅ Verified Info</h3>
                    <p style="color: var(--text-secondary); margin: 0; line-height: 1.7;">
                        Our team regularly updates and verifies information to ensure you have the most current and accurate visa requirements.
                    </p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #ec4899;">
                    <h3 style="color: #db2777; margin-bottom: 0.75rem;">📱 Mobile-Friendly</h3>
                    <p style="color: var(--text-secondary); margin: 0; line-height: 1.7;">
                        Access visa information on any device - desktop, tablet, or smartphone. Travel information when you need it, where you need it.
                    </p>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #06b6d4;">
                    <h3 style="color: #0891b2; margin-bottom: 0.75rem;">🔗 Official Links</h3>
                    <p style="color: var(--text-secondary); margin: 0; line-height: 1.7;">
                        Direct links to official government visa application portals and embassy websites for secure, official processing.
                    </p>
                </div>
            </div>
            
            <!-- Why Choose Us -->
            <h2 style="margin-top: 3rem;">Why Choose Arrival Cards?</h2>
            <div class="about-padded" style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 2rem; border-radius: 8px; margin: 2rem 0;">
                <p style="line-height: 1.8; margin-bottom: 1rem;">
                    <strong>Comprehensive Coverage:</strong> Unlike scattered resources across the internet, we provide a centralized hub for visa information covering all 196 countries. No more jumping between multiple websites or government portals.
                </p>
                <p style="line-height: 1.8; margin-bottom: 1rem;">
                    <strong>Regularly Updated:</strong> Visa regulations change frequently. Our dedicated team monitors official sources and updates our database to reflect the latest requirements, ensuring you always have current information.
                </p>
                <p style="line-height: 1.8; margin-bottom: 1rem;">
                    <strong>Multilingual Support:</strong> Travel is global, and so are we. Access the same quality information in seven major languages, making international travel planning accessible to everyone.
                </p>
                <p style="line-height: 1.8; margin-bottom: 1rem;">
                    <strong>User-Focused Design:</strong> We've designed our platform with the traveler in mind. Clean, intuitive navigation helps you find exactly what you need in seconds, not minutes.
                </p>
                <p style="line-height: 1.8; margin: 0;">
                    <strong>100% Free:</strong> We believe travel information should be accessible to everyone. Our service is completely free - no subscriptions, no hidden fees, no paywalls.
                </p>
            </div>
            
            <!-- Our Story -->
            <h2 style="margin-top: 3rem;">Our Story</h2>
            <p style="line-height: 1.8; margin-bottom: 1rem;">
                Arrival Cards was born from a simple observation: planning international travel shouldn't be complicated. As frequent travelers ourselves, we experienced firsthand the frustration of searching through countless government websites, dealing with outdated information, and struggling with language barriers.
            </p>
            <p style="line-height: 1.8; margin-bottom: 1rem;">
                We created Arrival Cards to solve these problems. What started as a personal project to simplify our own travel planning has grown into a comprehensive resource serving travelers worldwide. Today, we're proud to offer reliable visa information for every country on Earth.
            </p>
            <p style="line-height: 1.8; margin-bottom: 2rem;">
                Our team of travel experts and researchers work tirelessly to maintain the accuracy and completeness of our database. We're committed to being your trusted partner in travel preparation, whether you're planning a quick business trip or a round-the-world adventure.
            </p>
            
            <!-- What We Cover -->
            <h2 style="margin-top: 3rem;">Information We Provide</h2>
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 2rem 0;">
                <ul style="line-height: 2; margin: 0; padding-left: 1.5rem;">
                    <li><strong>Visa Requirements:</strong> Whether you need a visa, can enter visa-free, or qualify for visa-on-arrival</li>
                    <li><strong>Application Procedures:</strong> Step-by-step guidance on how to apply for visas</li>
                    <li><strong>Processing Times:</strong> Expected timeframes for visa processing</li>
                    <li><strong>Required Documents:</strong> Complete lists of documents needed for applications</li>
                    <li><strong>Fees and Costs:</strong> Current visa fees and payment methods</li>
                    <li><strong>Passport Validity:</strong> Minimum passport validity requirements</li>
                    <li><strong>Arrival Cards:</strong> Information about arrival/departure cards where applicable</li>
                    <li><strong>Special Requirements:</strong> Additional requirements like vaccination certificates or travel insurance</li>
                    <li><strong>Official Resources:</strong> Direct links to embassy websites and official visa portals</li>
                    <li><strong>Entry Restrictions:</strong> Current travel restrictions or special entry conditions</li>
                </ul>
            </div>
            
            <!-- Commitment to Accuracy -->
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 2rem; border-radius: 8px; margin: 3rem 0;">
                <h3 style="color: #d97706; margin-top: 0;">⚠️ Important Disclaimer</h3>
                <p style="line-height: 1.8; margin: 0;">
                    While we strive to maintain the most accurate and up-to-date information, visa requirements can change quickly. We strongly recommend verifying all information with official embassy websites or consulates before making travel arrangements. Arrival Cards is an informational resource and is not affiliated with any government or embassy. We do not process visa applications.
                </p>
            </div>
            
            <!-- Contact CTA -->
            <div class="about-cta" style="text-align: center; margin: 3rem 0 2rem; padding: 2.5rem; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px;">
                <h2 style="color: #1e3a8a; margin-bottom: 1rem;">Questions or Feedback?</h2>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 1.05rem;">
                    We'd love to hear from you! Contact us with questions, corrections, or suggestions.
                </p>
                <a href="<?php echo APP_URL; ?>/contact.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    Contact Us
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                </a>
            </div>
            
            <!-- Stats -->
            <div class="about-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 3rem 0;">
                <div style="text-align: center; padding: 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="about-stat-value" style="font-size: 2.5rem; font-weight: bold; color: #3b82f6; margin-bottom: 0.5rem;">196</div>
                    <div style="color: var(--text-secondary);">Countries Covered</div>
                </div>
                <div style="text-align: center; padding: 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="about-stat-value" style="font-size: 2.5rem; font-weight: bold; color: #10b981; margin-bottom: 0.5rem;">7</div>
                    <div style="color: var(--text-secondary);">Languages Supported</div>
                </div>
                <div style="text-align: center; padding: 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="about-stat-value" style="font-size: 2.5rem; font-weight: bold; color: #f59e0b; margin-bottom: 0.5rem;">100%</div>
                    <div style="color: var(--text-secondary);">Free Service</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
