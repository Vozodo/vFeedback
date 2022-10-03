<?php

//[Database]
define("D_HOST", "localhost");
define("D_NAME", "vFeedback");
define("D_USER", "vfeedback");
define("D_PASSWORD", "");

//IPHub - https://iphub.info/
define("IPHUB_APIKEY", "dc887ef845a4d2f643afa101ba7413bb");


//General
define("G_SITENAME", "vFeedback");
define("G_OWNER", "Vozodo");
define("G_OWNERMAIL", "admin@example.tld");

//Config
define("C_VPN_CHECK", true);
define("C_BLOCK_MULTIPLE_INSERTS", true);
define("C_WORDS_VALIDATION", true);

//Regex
define("R_ALLOWED_CHARS", "/[^(_|-|;|^|:|Ö|ö|Ü|ü|Ä|ä|>|<|\\@|,|°|~|+|*|'|#|.|•|♦|!|\"|§|\\$|%|&|\/|\\(|\\)|=|\\?|\\w|\\d|\\s)]/");

//Forbitten Words TODO
define("R_FORBITTEN_WORDS", array()); 


//Admin Credentials
define("ADMIN_USERNAME", "Administrator");
define("ADMIN_PASSWORD", '$2y$10$N2nkysK08VJ2CMdmgn1i4eBmog2XPiOCwnRbDLdNWPC.Dckxudbq.'); //For hashing Password: https://phppasswordhash.com/ | Default Password: vFeedback

//hcaptcha
define("HC_ENABLE", true);
define("HC_SITEKEY", "a20da15e08242a4b63f8727d82975b23");
define("HC_SECRETKEY", "a20da15e08242a4b63f8727d82975b23");


//E-Mail
define("EM_NOTIFY", false); // Get E-Mail Notification when Feedback is successful
define("EM_NOTIFY_FALIATURE", false); // Get E-Mail Notification when Feedback has VPN, Badwors or Multiple insert

//E-Mail Server Settings
define("EM_EMAIL", "feedback@example.tld");
define("EM_HOST", "mail.example.tdl");
define("EM_SMTP", true);
define("EM_USERNAME", "username");
define("EM_PASSWORD", "80aac04a441d76d57c2180857e83e467");
define("EM_PORT", 587);

?>
