<?php

/** @var \App\Core\Router $router */

$router->add('GET', '/', [App\Controllers\HomeController::class, 'index']);
$router->add('GET', '/about', [App\Controllers\HomeController::class, 'about']);
$router->add('GET', '/privacy-policy', [App\Controllers\HomeController::class, 'privacy']);
$router->add('GET', '/terms-and-conditions', [App\Controllers\HomeController::class, 'terms']);
$router->add('GET', '/refund-policy', [App\Controllers\HomeController::class, 'refund']);
$router->add('GET', '/beneficiaries', [App\Controllers\HomeController::class, 'beneficiaries']);
$router->add('GET', '/contact', [App\Controllers\HomeController::class, 'contact']);
$router->add('POST', '/contact/submit', [App\Controllers\HomeController::class, 'submitContact']);
$router->add('GET', '/gallery', [App\Controllers\HomeController::class, 'gallery']);
$router->add('GET', '/certificates', [App\Controllers\HomeController::class, 'certificates']);
$router->add('GET', '/donate', [App\Controllers\HomeController::class, 'donate']);
$router->add('POST', '/donate', [App\Controllers\HomeController::class, 'submitDonation']);
$router->add('POST', '/donate/razorpay-order', [App\Controllers\HomeController::class, 'createRazorpayOrder']);
$router->add('POST', '/donate/razorpay-verify', [App\Controllers\HomeController::class, 'verifyRazorpayPayment']);
$router->add('POST', '/donate/razorpay-fail', [App\Controllers\HomeController::class, 'markRazorpayFailed']);
$router->add('GET', '/donate/receipt/{uuid}', [App\Controllers\HomeController::class, 'receipt']);

$router->add('GET', '/achievements', [App\Controllers\HomeController::class, 'achievements']);
$router->add('GET', '/news', [App\Controllers\HomeController::class, 'news']);
$router->add('GET', '/news/{slug}', [App\Controllers\HomeController::class, 'newsDetail']);
$router->add('GET', '/projects', [App\Controllers\HomeController::class, 'projects']);
$router->add('GET', '/projects/{slug}', [App\Controllers\HomeController::class, 'projectDetail']);
$router->add('GET', '/campaigns', [App\Controllers\HomeController::class, 'campaigns']);
$router->add('GET', '/campaigns/{slug}', [App\Controllers\HomeController::class, 'campaignDetail']);
$router->add('GET', '/careers', [App\Controllers\HomeController::class, 'careers']);
$router->add('GET', '/careers/{slug}', [App\Controllers\HomeController::class, 'careerDetail']);
$router->add('GET', '/careers/apply/{slug}', [App\Controllers\HomeController::class, 'showApplicationForm']);
$router->add('POST', '/careers/apply/{slug}', [App\Controllers\HomeController::class, 'submitApplication']);
$router->add('GET', '/members', [App\Controllers\HomeController::class, 'members']);
$router->add('GET', '/members/register', [App\Controllers\HomeController::class, 'showRegister']);
$router->add('POST', '/members/register', [App\Controllers\HomeController::class, 'register']);
$router->add('GET', '/members/verify-email/{token}', [App\Controllers\HomeController::class, 'verifyEmail']);

// Auth Routes
$router->add('GET', '/auth', [App\Controllers\AuthController::class, 'showLogin']);
$router->add('POST', '/auth', [App\Controllers\AuthController::class, 'login']);
$router->add('POST', '/logout', [App\Controllers\AuthController::class, 'logout']);

// Member Panel Routes
$router->add('GET', '/member/dashboard', [App\Controllers\MemberDashboardController::class, 'dashboard']);
$router->add('GET', '/member/profile', [App\Controllers\MemberDashboardController::class, 'profile']);
$router->add('POST', '/member/logout', [App\Controllers\MemberDashboardController::class, 'logout']);
$router->add('GET', '/member/donate', [App\Controllers\MemberDashboardController::class, 'donate']);
$router->add('POST', '/member/donate/submit', [App\Controllers\MemberDashboardController::class, 'submitDonation']);
$router->add('GET', '/member/donations', [App\Controllers\MemberDashboardController::class, 'donationHistory']);
$router->add('GET', '/member/fees', [App\Controllers\MemberDashboardController::class, 'fees']);
$router->add('GET', '/member/id-card', [App\Controllers\MemberDashboardController::class, 'idCard']);
$router->add('GET', '/member/offer-letter', [App\Controllers\MemberDashboardController::class, 'offerLetter']);
$router->add('GET', '/member/notices', [App\Controllers\MemberDashboardController::class, 'notices']);

// Admin Routes
$router->add('GET', '/admin/dashboard', [App\Controllers\AdminController::class, 'dashboard']);
$router->add('GET', '/admin/profile', [App\Controllers\AdminController::class, 'profile']);
$router->add('POST', '/admin/profile', [App\Controllers\AdminController::class, 'updateProfile']);

// CMS Routes
$router->add('GET', '/admin/cms/about', [App\Controllers\CMSController::class, 'about']);
$router->add('GET', '/admin/cms/slider', [App\Controllers\CMSController::class, 'slider']);
$router->add('GET', '/admin/cms/gallery', [App\Controllers\CMSController::class, 'gallery']);
$router->add('GET', '/admin/cms/certificates', [App\Controllers\CMSController::class, 'certificates']);
$router->add('GET', '/admin/cms/achievements', [App\Controllers\CMSController::class, 'achievements']);
$router->add('GET', '/admin/cms/policies', [App\Controllers\CMSController::class, 'policies']);
$router->add('POST', '/admin/cms/update-policies', [App\Controllers\CMSController::class, 'updatePolicies']);
$router->add('POST', '/admin/cms/update-page', [App\Controllers\CMSController::class, 'updatePage']);
$router->add('POST', '/admin/cms/store-media', [App\Controllers\CMSController::class, 'storeMedia']);
$router->add('POST', '/admin/cms/delete-media/{id}', [App\Controllers\CMSController::class, 'deleteMedia']);
$router->add('POST', '/admin/cms/toggle-media-status/{id}', [App\Controllers\CMSController::class, 'toggleMediaStatus']);

// News Routes
$router->add('GET', '/admin/news', [App\Controllers\NewsController::class, 'index']);
$router->add('POST', '/admin/news/store', [App\Controllers\NewsController::class, 'store']);
$router->add('GET', '/admin/news/edit/{id}', [App\Controllers\NewsController::class, 'edit']);
$router->add('POST', '/admin/news/update/{id}', [App\Controllers\NewsController::class, 'update']);
$router->add('POST', '/admin/news/delete/{id}', [App\Controllers\NewsController::class, 'delete']);

// Notices Routes
$router->add('GET', '/admin/notices', [App\Controllers\NoticeController::class, 'index']);
$router->add('POST', '/admin/notices/store', [App\Controllers\NoticeController::class, 'store']);
$router->add('GET', '/admin/notices/edit/{id}', [App\Controllers\NoticeController::class, 'edit']);
$router->add('POST', '/admin/notices/update/{id}', [App\Controllers\NoticeController::class, 'update']);
$router->add('POST', '/admin/notices/delete/{id}', [App\Controllers\NoticeController::class, 'delete']);

// Projects
$router->add('GET', '/admin/projects', [App\Controllers\ProjectController::class, 'index']);
$router->add('POST', '/admin/projects/store', [App\Controllers\ProjectController::class, 'store']);
$router->add('GET', '/admin/projects/edit/{id}', [App\Controllers\ProjectController::class, 'edit']);
$router->add('POST', '/admin/projects/update/{id}', [App\Controllers\ProjectController::class, 'update']);
$router->add('POST', '/admin/projects/delete-gallery/{id}', [App\Controllers\ProjectController::class, 'deleteGalleryImage']);
$router->add('POST', '/admin/projects/delete/{id}', [App\Controllers\ProjectController::class, 'delete']);

// Campaigns Routes
$router->add('GET', '/admin/campaigns', [App\Controllers\CampaignController::class, 'index']);
$router->add('POST', '/admin/campaigns/store', [App\Controllers\CampaignController::class, 'store']);
$router->add('GET', '/admin/campaigns/edit/{id}', [App\Controllers\CampaignController::class, 'edit']);
$router->add('POST', '/admin/campaigns/update/{id}', [App\Controllers\CampaignController::class, 'update']);
$router->add('POST', '/admin/campaigns/delete/{id}', [App\Controllers\CampaignController::class, 'delete']);

// Careers Routes
$router->add('GET', '/admin/careers', [App\Controllers\CareerController::class, 'index']);
$router->add('POST', '/admin/careers/store', [App\Controllers\CareerController::class, 'store']);
$router->add('GET', '/admin/careers/edit/{id}', [App\Controllers\CareerController::class, 'edit']);
$router->add('POST', '/admin/careers/update/{id}', [App\Controllers\CareerController::class, 'update']);
$router->add('POST', '/admin/careers/delete/{id}', [App\Controllers\CareerController::class, 'delete']);
$router->add('GET', '/admin/careers/interns', [App\Controllers\CareerController::class, 'interns']);
$router->add('POST', '/admin/careers/interns/store', [App\Controllers\CareerController::class, 'storeIntern']);
$router->add('GET', '/admin/careers/employees', [App\Controllers\CareerController::class, 'employees']);
$router->add('POST', '/admin/careers/employees/store', [App\Controllers\CareerController::class, 'storeEmployee']);
$router->add('GET', '/admin/careers/applications', [App\Controllers\CareerController::class, 'applications']);
$router->add('GET', '/admin/careers/application/{id}', [App\Controllers\CareerController::class, 'applicationDetail']);
$router->add('GET', '/admin/careers/application-json/{id}', [App\Controllers\CareerController::class, 'applicationJson']);
$router->add('POST', '/admin/careers/application-update-status/{id}', [App\Controllers\CareerController::class, 'updateApplicationStatus']);
$router->add('POST', '/admin/careers/application-update-fields/{id}', [App\Controllers\CareerController::class, 'updateApplicationFields']);
$router->add('POST', '/admin/careers/complete-internship/{id}', [App\Controllers\CareerController::class, 'completeInternship']);
$router->add('GET', '/admin/careers/certificate/{id}', [App\Controllers\CareerController::class, 'certificate']);
$router->add('GET', '/admin/careers/download-certificate/{id}', [App\Controllers\CareerController::class, 'downloadCertificate']);
$router->add('POST', '/admin/careers/send-certificate/{id}', [App\Controllers\CareerController::class, 'sendCertificate']);
$router->add('POST', '/admin/careers/send-offer-letter/{id}', [App\Controllers\CareerController::class, 'sendOfferLetter']);

// Finance Routes
$router->add('GET', '/admin/finance/donations', [App\Controllers\FinanceController::class, 'donations']);
$router->add('POST', '/admin/finance/donations/store', [App\Controllers\FinanceController::class, 'storeDonation']);
$router->add('POST', '/admin/finance/donations/approve/{id}', [App\Controllers\FinanceController::class, 'approveDonation']);
$router->add('POST', '/admin/finance/donations/update-status/{id}', [App\Controllers\FinanceController::class, 'updateDonationStatus']);
$router->add('POST', '/admin/finance/delete-donation/{id}', [App\Controllers\FinanceController::class, 'deleteDonation']);
$router->add('POST', '/admin/finance/donations/send-receipt/{id}', [App\Controllers\FinanceController::class, 'sendReceiptMail']);
$router->add('GET', '/admin/finance/donations/receipt/{id}', [App\Controllers\FinanceController::class, 'donationReceipt']);
$router->add('GET', '/admin/finance/expenses', [App\Controllers\FinanceController::class, 'expenses']);
$router->add('POST', '/admin/finance/expenses/store', [App\Controllers\FinanceController::class, 'storeExpense']);
$router->add('POST', '/admin/finance/expenses/store-category', [App\Controllers\FinanceController::class, 'storeCategory']);
$router->add('POST', '/admin/finance/expenses/delete-category/{id}', [App\Controllers\FinanceController::class, 'deleteCategory']);
$router->add('POST', '/admin/finance/expenses/update', [App\Controllers\FinanceController::class, 'updateExpense']);
$router->add('GET', '/admin/finance/expenses/view/{id}', [App\Controllers\FinanceController::class, 'viewExpense']);
$router->add('POST', '/admin/finance/delete-expense/{id}', [App\Controllers\FinanceController::class, 'deleteExpense']);
$router->add('POST', '/admin/finance/delete-donation/{id}', [App\Controllers\FinanceController::class, 'deleteDonation']);
$router->add('GET', '/admin/finance/reports', [App\Controllers\FinanceController::class, 'reports']);

// Members Routes
$router->add('GET', '/admin/members', [App\Controllers\MemberController::class, 'index']);
$router->add('POST', '/admin/members/store', [App\Controllers\MemberController::class, 'store']);
$router->add('GET', '/admin/members/get/{id}', [App\Controllers\MemberController::class, 'get']);
$router->add('GET', '/admin/members/edit/{id}', [App\Controllers\MemberController::class, 'edit']);
$router->add('POST', '/admin/members/update/{id}', [App\Controllers\MemberController::class, 'update']);
$router->add('POST', '/admin/members/delete/{id}', [App\Controllers\MemberController::class, 'delete']);
$router->add('POST', '/admin/members/approve/{id}', [App\Controllers\MemberController::class, 'approve']);
$router->add('GET', '/admin/members/id-card/{id}', [App\Controllers\MemberController::class, 'idCard']);
$router->add('GET', '/admin/members/offer-letter/{id}', [App\Controllers\MemberController::class, 'offerLetter']);
$router->add('POST', '/admin/members/generate-password/{id}', [App\Controllers\MemberController::class, 'generatePassword']);
$router->add('GET', '/admin/members/fees', [App\Controllers\MemberController::class, 'fees']);
$router->add('GET', '/admin/members/fees/{id}', [App\Controllers\MemberController::class, 'memberFees']);

// Legacy PDF Generation Routes (Maintained for backwards compatibility)
$router->add('GET', '/offer_letter_pdf.php', [App\Controllers\CareerController::class, 'legacyOfferLetterPdf']);
$router->add('GET', '/certificate_pdf.php', [App\Controllers\CareerController::class, 'legacyCertificatePdf']);


// Designation Routes
$router->add('GET', '/admin/designations', [App\Controllers\DesignationController::class, 'index']);
$router->add('POST', '/admin/designations/store', [App\Controllers\DesignationController::class, 'store']);
$router->add('GET', '/admin/designations/edit/{id}', [App\Controllers\DesignationController::class, 'edit']);
$router->add('POST', '/admin/designations/update/{id}', [App\Controllers\DesignationController::class, 'update']);
$router->add('POST', '/admin/designations/toggle-status/{id}', [App\Controllers\DesignationController::class, 'toggleStatus']);
$router->add('POST', '/admin/designations/delete/{id}', [App\Controllers\DesignationController::class, 'delete']);

// Donors Routes
$router->add('GET', '/admin/donors', [App\Controllers\DonorController::class, 'index']);
$router->add('POST', '/admin/donors/store', [App\Controllers\DonorController::class, 'store']);
$router->add('POST', '/admin/donors/update/{id}', [App\Controllers\DonorController::class, 'update']);
$router->add('POST', '/admin/donors/delete/{id}', [App\Controllers\DonorController::class, 'delete']);

// Beneficiaries Routes
$router->add('GET', '/admin/beneficiaries', [App\Controllers\BeneficiaryController::class, 'index']);
$router->add('POST', '/admin/beneficiaries/store', [App\Controllers\BeneficiaryController::class, 'store']);
$router->add('GET', '/admin/beneficiaries/edit/{id}', [App\Controllers\BeneficiaryController::class, 'edit']);
$router->add('POST', '/admin/beneficiaries/update/{id}', [App\Controllers\BeneficiaryController::class, 'update']);
$router->add('POST', '/admin/beneficiaries/store-assistance-type', [App\Controllers\BeneficiaryController::class, 'storeAssistanceType']);
$router->add('POST', '/admin/beneficiaries/delete-assistance-type/{id}', [App\Controllers\BeneficiaryController::class, 'deleteAssistanceType']);
$router->add('POST', '/admin/beneficiaries/delete/{id}', [App\Controllers\BeneficiaryController::class, 'delete']);

// User Management Routes
$router->add('GET', '/admin/users', [App\Controllers\UserController::class, 'index']);
$router->add('POST', '/admin/users/store', [App\Controllers\UserController::class, 'store']);
$router->add('GET', '/admin/users/get/{id}', [App\Controllers\UserController::class, 'get']);
$router->add('POST', '/admin/users/update/{id}', [App\Controllers\UserController::class, 'update']);
$router->add('POST', '/admin/users/delete/{id}', [App\Controllers\UserController::class, 'delete']);
$router->add('POST', '/admin/users/toggle-status/{id}', [App\Controllers\UserController::class, 'toggleStatus']);
$router->add('POST', '/admin/users/store-role', [App\Controllers\UserController::class, 'storeRole']);
$router->add('POST', '/admin/users/delete-role/{id}', [App\Controllers\UserController::class, 'deleteRole']);

// Contact Inquiries Routes
$router->add('GET', '/admin/contacts', [App\Controllers\ContactController::class, 'index']);
$router->add('GET', '/admin/contacts/show/{id}', [App\Controllers\ContactController::class, 'show']);
$router->add('POST', '/admin/contacts/delete/{id}', [App\Controllers\ContactController::class, 'delete']);

$router->add('GET', '/admin/settings/organization', [App\Controllers\SettingsController::class, 'organization']);
$router->add('POST', '/admin/settings/organization', [App\Controllers\SettingsController::class, 'update']);

$router->add('GET', '/admin/settings/update', [App\Controllers\SystemUpdateController::class, 'index']);
$router->add('POST', '/admin/settings/update/run', [App\Controllers\SystemUpdateController::class, 'update']);

$router->add('GET', '/admin/settings/smtp', [App\Controllers\SettingsController::class, 'smtp']);
$router->add('POST', '/admin/settings/smtp', [App\Controllers\SettingsController::class, 'update']);
$router->add('POST', '/admin/settings/smtp/test', [App\Controllers\SettingsController::class, 'testSmtp']);

$router->add('GET', '/admin/settings/payments', [App\Controllers\SettingsController::class, 'payments']);
$router->add('POST', '/admin/settings/payments', [App\Controllers\SettingsController::class, 'update']);

$router->add('GET', '/admin/settings/templates', [App\Controllers\SettingsController::class, 'templates']);
$router->add('POST', '/admin/settings/templates', [App\Controllers\SettingsController::class, 'update']);

// Partners Routes
$router->add('GET', '/admin/partners', [App\Controllers\PartnerController::class, 'index']);
$router->add('POST', '/admin/partners/store', [App\Controllers\PartnerController::class, 'store']);
$router->add('GET', '/admin/partners/edit/{id}', [App\Controllers\PartnerController::class, 'edit']);
$router->add('POST', '/admin/partners/update/{id}', [App\Controllers\PartnerController::class, 'update']);
$router->add('POST', '/admin/partners/delete/{id}', [App\Controllers\PartnerController::class, 'delete']);

// Security Audit Route (Super Admin Only)
$router->add('GET', '/admin/security-audit', [App\Controllers\SecurityAuditController::class, 'index']);

