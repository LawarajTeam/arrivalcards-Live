<?php
/**
 * Frequently Asked Questions (FAQ) Page
 * Comprehensive guide to visa questions and travel documentation
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/analytics_functions.php';

$pageTitle = 'Frequently Asked Questions (FAQ) - Visa & Travel Help | Arrival Cards';
$pageDescription = 'Get answers to common questions about visas, entry requirements, passports, eVisas, visa-on-arrival, and international travel documentation. Comprehensive FAQ guide for travelers.';
$pageKeywords = 'visa faq, visa questions, travel questions, passport questions, evisa help, visa on arrival faq, entry requirements help, travel documentation questions';

// Track page view
trackPageView(null, 'FAQ');

include __DIR__ . '/includes/header.php'; 
?>

<style>
.faq-category {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.faq-category h2 {
    color: #1e3a8a;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #3b82f6;
}

.faq-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.faq-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.faq-question {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e3a8a;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.faq-question::before {
    content: "Q:";
    color: #3b82f6;
    font-weight: bold;
    flex-shrink: 0;
}

.faq-answer {
    line-height: 1.8;
    color: var(--text-secondary);
    margin-left: 1.75rem;
}

.faq-answer ul, .faq-answer ol {
    margin: 0.75rem 0;
    padding-left: 1.5rem;
}

.faq-answer li {
    margin: 0.5rem 0;
}

.faq-highlight {
    background: #f0f9ff;
    border-left: 4px solid #3b82f6;
    padding: 1rem 1.5rem;
    border-radius: 4px;
    margin: 1rem 0;
}

.faq-toc {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 3rem;
}

.faq-toc h2 {
    color: #1e3a8a;
    margin-bottom: 1rem;
}

.faq-toc ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.faq-toc li {
    margin: 0.75rem 0;
}

.faq-toc a {
    color: #3b82f6;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.faq-toc a:hover {
    text-decoration: underline;
}
</style>

<section class="countries-section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1>Frequently Asked Questions</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 3rem;">
                Find answers to common questions about visas, passports, and international travel requirements
            </p>
            
            <!-- Table of Contents -->
            <div class="faq-toc">
                <h2>Quick Navigation</h2>
                <ul>
                    <li><a href="#general">📋 General Questions</a></li>
                    <li><a href="#visa-types">🔖 Understanding Visa Types</a></li>
                    <li><a href="#application">📝 Visa Applications</a></li>
                    <li><a href="#passport">📘 Passport Requirements</a></li>
                    <li><a href="#arrival">✈️ Arrival & Entry</a></li>
                    <li><a href="#website">💻 Using This Website</a></li>
                </ul>
            </div>
            
            <!-- General Questions -->
            <div class="faq-category" id="general">
                <h2>📋 General Questions</h2>
                
                <div class="faq-item">
                    <div class="faq-question">What is Arrival Cards?</div>
                    <div class="faq-answer">
                        Arrival Cards is a comprehensive, free information resource providing visa requirements and entry documentation details for all 196 countries worldwide. We help travelers understand what documents they need before international travel. Our service is available in 7 languages to serve global travelers.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Is Arrival Cards affiliated with any government?</div>
                    <div class="faq-answer">
                        No, Arrival Cards is an independent information resource and is NOT affiliated with any government, embassy, consulate, or official visa processing agency. We compile and present publicly available information to help travelers, but we do not process visa applications or make official decisions about entry requirements.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Is the information on this website accurate?</div>
                    <div class="faq-answer">
                        We work hard to maintain accurate and current information by regularly monitoring official sources. However, visa requirements can change quickly and without notice. <strong>Always verify information with official embassy websites or consulates before making travel arrangements.</strong> We recommend checking within 30 days of your planned travel.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Is this service free?</div>
                    <div class="faq-answer">
                        Yes, 100% free! There are no subscriptions, hidden fees, or paywalls. We believe travel information should be accessible to everyone.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Can you help me apply for a visa?</div>
                    <div class="faq-answer">
                        No, we do not process visa applications. We are an informational resource only. We provide links to official visa application portals where you can apply directly, or we recommend contacting embassies and consulates for application assistance.
                    </div>
                </div>
            </div>
            
            <!-- Understanding Visa Types -->
            <div class="faq-category" id="visa-types">
                <h2>🔖 Understanding Visa Types</h2>
                
                <div class="faq-item">
                    <div class="faq-question">What does "Visa Free" mean?</div>
                    <div class="faq-answer">
                        "Visa Free" means citizens of certain countries can enter without obtaining a visa in advance. You can simply arrive at the border or airport with your valid passport. However, there are usually restrictions:
                        <ul>
                            <li>Limited stay duration (commonly 30, 60, or 90 days)</li>
                            <li>Travel must be for tourism or business only</li>
                            <li>Your passport must meet validity requirements</li>
                            <li>You may need proof of onward travel</li>
                            <li>Sufficient funds may be required</li>
                        </ul>
                        Even though it's "visa free," you still need a valid passport and must meet entry requirements.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What is "Visa on Arrival"?</div>
                    <div class="faq-answer">
                        Visa on Arrival (VoA) means you can obtain your visa when you arrive at the airport or border crossing in the destination country. Key points:
                        <ul>
                            <li>No need to visit an embassy beforehand</li>
                            <li>Usually requires a fee (cash or card)</li>
                            <li>Processing happens at the airport</li>
                            <li>Bring passport photos and required documents</li>
                            <li>May require proof of accommodation and return ticket</li>
                            <li>Processing time varies (15 minutes to 2 hours)</li>
                        </ul>
                        <div class="faq-highlight">
                            <strong>Tip:</strong> Always research the specific requirements and have exact cash ready, as some countries don't accept cards or have limited ATMs before immigration.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What is an eVisa?</div>
                    <div class="faq-answer">
                        An eVisa (electronic visa) is a digital visa you apply for and receive online before traveling. The process:
                        <ol>
                            <li>Complete an online application form</li>
                            <li>Upload required documents (passport scan, photo)</li>
                            <li>Pay the visa fee online</li>
                            <li>Receive approval via email (PDF)</li>
                            <li>Print the eVisa and present it at the border</li>
                        </ol>
                        <strong>Advantages:</strong> No embassy visits, faster processing (usually 1-7 days), convenient online process.
                        <br><br>
                        <strong>Important:</strong> Some countries require you to print the eVisa, while others can retrieve it electronically using your passport number.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What does "Visa Required" mean?</div>
                    <div class="faq-answer">
                        "Visa Required" means you must obtain a visa from an embassy or consulate before traveling. This typically involves:
                        <ul>
                            <li>Submitting a formal application</li>
                            <li>Providing extensive documentation</li>
                            <li>Possibly attending an in-person interview</li>
                            <li>Paying visa fees</li>
                            <li>Waiting for processing (can be weeks or months)</li>
                        </ul>
                        Start this process well in advance of your planned travel, as processing times can be lengthy and unpredictable.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Can visa types change?</div>
                    <div class="faq-answer">
                        Yes! Countries frequently update their visa policies. Changes can include:
                        <ul>
                            <li>Adding countries to visa-free lists</li>
                            <li>Introducing new eVisa systems</li>
                            <li>Removing visa exemptions</li>
                            <li>Changing stay durations</li>
                            <li>Adding new requirements (travel authorization, vaccinations)</li>
                        </ul>
                        Always check current requirements within 30 days of travel, even for countries you've visited before.
                    </div>
                </div>
            </div>
            
            <!-- Visa Applications -->
            <div class="faq-category" id="application">
                <h2>📝 Visa Applications</h2>
                
                <div class="faq-item">
                    <div class="faq-question">How long does visa processing take?</div>
                    <div class="faq-answer">
                        Processing times vary significantly by country and visa type:
                        <ul>
                            <li><strong>eVisas:</strong> 1-7 days (some within hours)</li>
                            <li><strong>Tourist Visas:</strong> 3-15 business days</li>
                            <li><strong>Business Visas:</strong> 5-20 business days</li>
                            <li><strong>Work/Student Visas:</strong> 4-12 weeks or longer</li>
                        </ul>
                        <div class="faq-highlight">
                            <strong>Important:</strong> These are estimates. Always apply as early as possible, ideally 2-3 months before travel. Some embassies offer expedited processing for an additional fee.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What documents do I need for a visa application?</div>
                    <div class="faq-answer">
                        Common requirements include:
                        <ul>
                            <li>Valid passport (6+ months validity)</li>
                            <li>Completed application form</li>
                            <li>Passport-sized photos (specific size requirements)</li>
                            <li>Proof of accommodation (hotel bookings)</li>
                            <li>Proof of funds (bank statements)</li>
                            <li>Return flight ticket or travel itinerary</li>
                            <li>Travel insurance (for some countries)</li>
                            <li>Invitation letter (for business/family visits)</li>
                            <li>Employment letter or proof of studies</li>
                        </ul>
                        Requirements vary by country and visa type. Check the specific country page on our website for details.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">How much do visas cost?</div>
                    <div class="faq-answer">
                        Visa fees vary widely:
                        <ul>
                            <li><strong>Free:</strong> Some tourist visas and visa waivers</li>
                            <li><strong>$10-$50:</strong> Many eVisas and visa-on-arrival</li>
                            <li><strong>$50-$160:</strong> Standard tourist/business visas</li>
                            <li><strong>$160+:</strong> US, Canada, Australia, UK, Schengen visas</li>
                            <li><strong>$200-$1000+:</strong> Work, investment, or long-term visas</li>
                        </ul>
                        Fees are typically non-refundable, even if your application is denied. Check our country pages for current fee information.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Can my visa application be rejected?</div>
                    <div class="faq-answer">
                        Yes. Common reasons for visa rejection include:
                        <ul>
                            <li>Incomplete or incorrect application</li>
                            <li>Insufficient proof of funds</li>
                            <li>Lack of ties to home country</li>
                            <li>Previous immigration violations</li>
                            <li>Criminal record</li>
                            <li>Security concerns</li>
                            <li>Questionable travel purpose</li>
                            <li>Invalid passport or missing documentation</li>
                        </ul>
                        <strong>Tips to avoid rejection:</strong>
                        <ul>
                            <li>Provide complete, accurate information</li>
                            <li>Submit all required documents</li>
                            <li>Demonstrate strong ties to your home country</li>
                            <li>Show sufficient financial resources</li>
                            <li>Be honest about travel purpose</li>
                            <li>Apply with adequate time before travel</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Passport Requirements -->
            <div class="faq-category" id="passport">
                <h2>📘 Passport Requirements</h2>
                
                <div class="faq-item">
                    <div class="faq-question">What is the "6-month passport validity rule"?</div>
                    <div class="faq-answer">
                        Most countries require your passport to be valid for at least 6 months beyond your intended stay. This rule exists because:
                        <ul>
                            <li>You might extend your stay</li>
                            <li>Unexpected delays could occur</li>
                            <li>It's an international standard for entry</li>
                        </ul>
                        <div class="faq-highlight">
                            <strong>Example:</strong> If you're traveling in March and your passport expires in July, many countries will deny entry even though your passport is technically valid during your trip.
                        </div>
                        Some countries have 3-month rules, others 6 months. Always check specific requirements for your destination.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Do I need blank pages in my passport?</div>
                    <div class="faq-answer">
                        Yes, most countries require at least 2-3 blank pages in your passport for entry stamps and visas. Some countries are strict about this:
                        <ul>
                            <li><strong>2 blank pages:</strong> Minimum for most countries</li>
                            <li><strong>1 full page:</strong> For visa stickers</li>
                            <li><strong>Consecutive pages:</strong> Some require pages to be next to each other</li>
                        </ul>
                        If you're running low on pages, consider getting additional pages added or renewing your passport before traveling.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What if my passport is damaged?</div>
                    <div class="faq-answer">
                        A damaged passport can cause serious travel problems. You should replace it if:
                        <ul>
                            <li>Pages are torn or missing</li>
                            <li>The photo or personal information is damaged</li>
                            <li>The biometric chip is not working</li>
                            <li>Water damage has occurred</li>
                            <li>Significant wear makes information unreadable</li>
                        </ul>
                        Immigration officials have discretion to deny entry with a damaged passport. Don't risk it - get a replacement.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Can I travel with dual citizenship/two passports?</div>
                    <div class="faq-answer">
                        Yes, if your countries allow dual citizenship. Traveling with two passports:
                        <ul>
                            <li>Use the passport that gives you better visa access</li>
                            <li>Some countries require you to enter/exit with the same passport</li>
                            <li>Declare both nationalities when applying for visas if required</li>
                            <li>Some countries (e.g., China, India) have strict rules about dual nationality</li>
                            <li>Always use the same passport consistently within one country</li>
                        </ul>
                        <div class="faq-highlight">
                            <strong>Important:</strong> Some countries don't recognize dual citizenship. Research the policies of countries you're visiting.
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Arrival & Entry -->
            <div class="faq-category" id="arrival">
                <h2>✈️ Arrival & Entry</h2>
                
                <div class="faq-item">
                    <div class="faq-question">What is an arrival card?</div>
                    <div class="faq-answer">
                        An arrival card (also called disembarkation card or landing card) is a form you fill out before or upon arriving in a foreign country. It typically asks for:
                        <ul>
                            <li>Personal information (name, passport number)</li>
                            <li>Flight details</li>
                            <li>Accommodation address</li>
                            <li>Purpose of visit</li>
                            <li>Length of stay</li>
                            <li>Declaration of goods/cash</li>
                        </ul>
                        Some countries provide these on the plane, others require you to fill them out at the airport. Some have moved to fully digital systems.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What questions will immigration ask me?</div>
                    <div class="faq-answer">
                        Common immigration questions include:
                        <ul>
                            <li>"What is the purpose of your visit?" - Answer: Tourism, business, visiting family, etc.</li>
                            <li>"How long will you stay?" - Have specific dates ready</li>
                            <li>"Where will you be staying?" - Provide hotel name or address</li>
                            <li>"What is your occupation?" - Answer honestly</li>
                            <li>"Do you have a return ticket?" - Be ready to show it</li>
                            <li>"Have you been to [country] before?" - Answer truthfully</li>
                        </ul>
                        <strong>Tips:</strong>
                        <ul>
                            <li>Answer briefly and truthfully</li>
                            <li>Stay calm and polite</li>
                            <li>Have documents ready (hotel confirmations, return tickets)</li>
                            <li>Don't volunteer unnecessary information</li>
                        </ul>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Can I be denied entry even with a valid visa?</div>
                    <div class="faq-answer">
                        Yes. A visa doesn't guarantee entry - it only grants permission to travel to a country and request entry. You can be denied if:
                        <ul>
                            <li>Immigration officials suspect your intentions</li>
                            <li>You can't provide proof of accommodation or return travel</li>
                            <li>You appear intoxicated or unruly</li>
                            <li>New security concerns arise</li>
                            <li>Your passport is damaged</li>
                            <li>You have insufficient funds</li>
                            <li>You provide false information</li>
                        </ul>
                        Immigration officers have significant discretion. Always be prepared with proper documentation and honest responses.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">What should I do if I lose my passport while traveling?</div>
                    <div class="faq-answer">
                        If you lose your passport abroad:
                        <ol>
                            <li><strong>Report to local police immediately</strong> - Get a police report (required for replacement)</li>
                            <li><strong>Contact your embassy or consulate</strong> - They can issue emergency travel documents</li>
                            <li><strong>Bring documentation:</strong>
                                <ul>
                                    <li>Police report</li>
                                    <li>Passport photos</li>
                                    <li>Proof of citizenship (if available)</li>
                                    <li>Copy of passport (if you made one)</li>
                                </ul>
                            </li>
                            <li><strong>Apply for emergency passport</strong> - Processing usually takes 2-7 days</li>
                            <li><strong>Update visa status if needed</strong> - Your visa may need to be transferred</li>
                        </ol>
                        <div class="faq-highlight">
                            <strong>Prevention tip:</strong> Always keep digital and physical copies of your passport separate from the original. Email yourself a copy too.
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Using This Website -->
            <div class="faq-category" id="website">
                <h2>💻 Using This Website</h2>
                
                <div class="faq-item">
                    <div class="faq-question">How do I search for a specific country?</div>
                    <div class="faq-answer">
                        Multiple ways to find countries:
                        <ul>
                            <li><strong>Search bar:</strong> Type the country name (available on homepage)</li>
                            <li><strong>Filter by region:</strong> Browse by continent (Africa, Asia, Europe, etc.)</li>
                            <li><strong>Filter by visa type:</strong> Find all visa-free or eVisa countries</li>
                            <li><strong>Combined filters:</strong> Use region + visa type together</li>
                        </ul>
                        The search works in all 7 supported languages!
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">How do I change the language?</div>
                    <div class="faq-answer">
                        Look for the language selector in the top navigation bar. We currently support:
                        <ul>
                            <li>English</li>
                            <li>Español (Spanish)</li>
                            <li>中文 (Chinese Simplified)</li>
                            <li>Français (French)</li>
                            <li>Deutsch (German)</li>
                            <li>日本語 (Japanese)</li>
                            <li>العربية (Arabic)</li>
                        </ul>
                        Your language preference is saved for future visits.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Can I suggest corrections or updates?</div>
                    <div class="faq-answer">
                        Absolutely! We appreciate user feedback. If you notice outdated information or errors:
                        <ul>
                            <li>Use our <a href="<?php echo APP_URL; ?>/contact.php" style="color: #3b82f6;">contact form</a></li>
                            <li>Provide specific details (country, incorrect information, source)</li>
                            <li>Include links to official sources if possible</li>
                        </ul>
                        We review all submissions and update information promptly.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Do you have a mobile app?</div>
                    <div class="faq-answer">
                        Not yet, but our website is fully mobile-responsive and works great on all smartphones and tablets. You can:
                        <ul>
                            <li>Access it from any mobile browser</li>
                            <li>Add it to your home screen for quick access</li>
                            <li>Use it offline (once loaded)</li>
                        </ul>
                        A dedicated mobile app may come in the future based on user demand.
                    </div>
                </div>
            </div>
            
            <!-- Still Have Questions -->
            <div style="text-align: center; margin: 3rem 0 2rem; padding: 2.5rem; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); border-radius: 12px; color: white;">
                <h2 style="color: white; margin-bottom: 1rem;">Still Have Questions?</h2>
                <p style="margin-bottom: 1.5rem; font-size: 1.05rem; opacity: 0.95;">
                    Didn't find the answer you're looking for? We're here to help!
                </p>
                <a href="<?php echo APP_URL; ?>/contact.php" class="btn" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; background: white; color: #1e3a8a; padding: 1rem 2rem; border-radius: 8px; font-weight: 600;">
                    Contact Us
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
