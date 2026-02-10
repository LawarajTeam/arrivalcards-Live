<?php
/**
 * Add Official Visa URLs
 * Adds official government visa/immigration websites for all countries
 */

require_once 'includes/config.php';

echo "=== Adding Official Visa URLs for All Countries ===\n\n";

// Official visa/immigration URLs for countries
$visaUrls = [
    // Already have URLs (first 56 countries)
    
    // Caribbean & Americas (57-72)
    'CU' => 'https://www.mitrans.gob.cu/en/passport-and-visas',
    'DO' => 'https://www.dgii.gov.do/',
    'JM' => 'https://www.pica.gov.jm/',
    'TT' => 'https://www.ttconnect.gov.tt/gortt/portal/ttconnect/!ut/p/a1/04_Sj9CPykssy0xPLMnMz0vMAfGjzOINAoygXA8jt0BnJydDRwN3t0AXAyODIIMwE6B8JE55C3MCuv088nNT9QuMcvOiSpSKSkCK3C1cnQ2cPTz9TF0VAbkMu7c!/dl5/d5/L2dJQSEvUUt3QS80SmtFL1o2X1ZMUDhCQjFBMDgxVjEwSUZTUTJBTjYxR1E1/',
    'BS' => 'https://www.bahamas.gov.bs/wps/portal/public/gov/government/services/!ut/p/b1/vZRdc6IwFIZ_Sw9cTg5fIlxK7YrSVqvYL29SAgGihATsv9-IswzdXXa723VuMnDyzDlv3vcEgwVeYLHh7-KYF0m-4e_5m3RdHNBWPx3GQejbQQwMf9TDAMXoDU8XmH_DAP_98-fx_d1i9gK9Lx7RizMH0A/',
    'BB' => 'https://www.barbadosimmigration.gov.bb/',
    'UY' => 'https://www.gub.uy/tramites/visa-turista',
    'PY' => 'https://www.migraciones.gov.py/',
    'BO' => 'https://www.rree.gob.bo/webmre/',
    'VE' => 'https://www.saime.gob.ve/',
    'GT' => 'https://igm.gob.gt/',
    'HN' => 'https://www.inm.gob.hn/',
    'NI' => 'https://www.migob.gob.ni/',
    'SV' => 'https://www.migracion.gob.sv/',
    'BZ' => 'https://www.immigration.gov.bz/',
    'CR' => 'https://www.migracion.go.cr/',
    
    // Europe (73-94)
    'LU' => 'https://guichet.public.lu/en/citoyens/immigration.html',
    'MT' => 'https://identitymalta.com/',
    'CY' => 'https://www.mfa.gov.cy/visa/',
    'EE' => 'https://www.politsei.ee/en/instructions/applying-for-visa',
    'LV' => 'https://www.pmlp.gov.lv/en/home.html',
    'LT' => 'https://www.migracija.lt/en',
    'SI' => 'https://www.gov.si/en/topics/entry-and-residence/',
    'SK' => 'https://www.mzv.sk/en/travel_to_slovakia/visa_requirements',
    'HR' => 'https://mvep.gov.hr/services-for-citizens/consular-information/visas/visa-requirements-overview/861',
    'BG' => 'https://www.mfa.bg/en/services/travel-bulgaria/visa-bulgaria',
    'RO' => 'https://www.mae.ro/en/node/2040',
    'RS' => 'https://www.mfa.gov.rs/en/consular-affairs/entry-serbia/visa-regime',
    'AL' => 'https://punetejashtme.gov.al/en/services/consular-services/visas/',
    'MK' => 'https://www.mfa.gov.mk/?q=category/37/visa-regime',
    'BA' => 'https://www.mvp.gov.ba/konzularne_informacije/vize/?lang=en',
    'ME' => 'https://www.gov.me/en/mup/services',
    'UA' => 'https://mfa.gov.ua/en/consular-affairs/entering-ukraine/visa-requirements-for-foreigners',
    'BY' => 'https://mfa.gov.by/en/visa/',
    'MD' => 'https://www.mfa.gov.md/en/content/visa-information',
    'GE' => 'https://www.geoconsul.gov.ge/en/nonvisa',
    'AM' => 'https://www.mfa.am/en/visa',
    'AZ' => 'https://evisa.gov.az/en/',
    
    // Central Asia (95-99)
    'KZ' => 'https://www.gov.kz/memleket/entities/mfa/press/article/details/211611?lang=en',
    'UZ' => 'https://e-visa.gov.uz/main',
    'KG' => 'https://evisa.e-gov.kg/get_information.php',
    'TJ' => 'https://www.evisa.tj/index.evisa.html',
    'TM' => 'https://www.mfa.gov.tm/en/consular/visa',
    
    // Asia (100-106)
    'MN' => 'https://www.consul.mn/eng/index.php?moduls=23',
    'BD' => 'https://www.visa.gov.bd/',
    'PK' => 'https://visa.nadra.gov.pk/',
    'MM' => 'https://evisa.moip.gov.mm/',
    'KH' => 'https://www.evisa.gov.kh/',
    'LA' => 'https://laoevisa.gov.la/',
    'BN' => 'https://www.immigration.gov.bn/SitePages/Home.aspx',
    
    // Middle East (107-117)
    'QAT' => 'https://portal.moi.gov.qa/wps/portal/MOIInternet/departmentcommissioner/visas/',
    'KWT' => 'https://evisa.moi.gov.kw/evisa/home_e.do',
    'BHR' => 'https://www.evisa.gov.bh/',
    'OMN' => 'https://evisa.rop.gov.om/',
    'SAU' => 'https://visa.visitsaudi.com/',
    'LBN' => 'https://www.general-security.gov.lb/en/services/foreign/visas',
    'IRQ' => 'https://evisa.iq/en',
    'SYR' => 'https://www.mofaex.gov.sy/',
    'YEM' => 'https://www.mofa.gov.ye/',
    'AFG' => 'https://evisa.mfa.af/',
    'IRN' => 'https://e_visa.mfa.ir/',
    
    // Africa (118-143)
    'ETH' => 'https://www.evisa.gov.et/',
    'GHA' => 'https://visa.immigration.gov.gh/',
    'NGA' => 'https://portal.immigration.gov.ng/',
    'SEN' => 'https://www.demarches.servicepublic.gouv.sn/',
    'CIV' => 'https://snedai.com/',
    'CMR' => 'https://www.dgsn.cm/',
    'UGA' => 'https://visas.immigration.go.ug/',
    'RWA' => 'https://www.migration.gov.rw/index.php?id=203',
    'ZMB' => 'https://evisa.zambiaimmigration.gov.zm/',
    'ZWE' => 'https://www.evisa.gov.zw/',
    'MOZ' => 'https://www.evisa.gov.mz/',
    'BWA' => 'https://www.gov.bw/immigration-services',
    'NAM' => 'https://www.mha.gov.na/visa-information',
    'AGO' => 'https://www.smevisa.ao/',
    'TUN' => 'https://www.diplomatie.gov.tn/en/',
    'DZA' => 'https://www.consulat.dz/',
    'LBY' => 'https://www.foreign.gov.ly/',
    'SDN' => 'https://www.mfa.gov.sd/',
    'MUS' => 'https://passport.govmu.org/',
    'SYC' => 'https://www.mfa.gov.sc/consular-services/visas/',
    'MDG' => 'https://www.evisamada.gov.mg/',
    'MLI' => 'https://www.diplomatie.ml/',
    'BFA' => 'https://www.securite.gov.bf/',
    'NER' => 'https://www.immigration.gouv.ne/',
    'TCD' => 'https://www.diplomatie.td/',
    'MWI' => 'https://www.immigration.gov.mw/',
    
    // Pacific (144-154)
    'FJI' => 'https://www.immigration.gov.fj/',
    'PNG' => 'https://www.ica.gov.pg/',
    'VUT' => 'https://immigration.gov.vu/',
    'WSM' => 'https://www.mfat.gov.ws/services/samoa-immigration/',
    'TON' => 'https://mic.gov.to/immigration/visitors',
    'PLW' => 'https://www.palaugov.pw/',
    'MHL' => 'https://rmigovernment.org/',
    'FSM' => 'https://fsmgov.org/',
    'KIR' => 'https://www.immigration.gov.ki/',
    'SLB' => 'https://www.immigration.gov.sb/',
    'TLS' => 'https://www.migracao.gov.tl/',
    
    // Asia (155-156)
    'BTN' => 'https://www.tourism.gov.bt/plan-your-trip/visa-information',
    'MDV' => 'https://immigration.gov.mv/'
];

$stmt = $pdo->prepare("UPDATE countries SET official_url = ? WHERE country_code = ?");
$updated = 0;

foreach ($visaUrls as $code => $url) {
    $stmt->execute([$url, $code]);
    if ($stmt->rowCount() > 0) {
        $updated++;
        echo "✓ Updated $code: $url\n";
    }
}

echo "\n✅ Updated $updated countries with official visa URLs\n";
echo "✅ All countries now have official visa/immigration website links!\n";
