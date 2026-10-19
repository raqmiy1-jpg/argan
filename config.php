<?php
/**
 * مناحل الأطلس | من خيرات الطبيعة
 * ملف الإعدادات والوظائف المساعدة للعمل على Hostinger بـ PHP
 */

// بدء الجلسة للأدمن إذا لم تكن مفعلة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// مسار ملف قاعدة البيانات
define('DB_FILE', __DIR__ . '/data/db.json');

// ضبط التوقيت الافتراضي (المغرب)
date_default_timezone_set('Africa/Casablanca');

/**
 * جلب بيانات المتجر من ملف JSON
 */
function get_db_data() {
    if (!file_exists(DB_FILE)) {
        return [
            'products' => [],
            'bundles' => [],
            'cities' => [],
            'orders' => [],
            'settings' => [
                'storeName' => 'مناحل الأطلس',
                'brandTagline' => 'من خيرات الطبيعة المغربية',
                'phone' => '+212 661 234 567',
                'whatsapp' => '212661234567',
                'email' => 'contact@manahil-atlas.ma',
                'address' => 'جبال الأطلس المتوسط، جهة فاس مكناس، المملكة المغربية',
                'announcementText' => 'عسل طبيعي من خيرات الطبيعة المغربية – الدفع عند الاستلام متاح والتوصيل لجميع المدن المغربية',
                'showAnnouncement' => true,
                'freeShippingThreshold' => 500,
                'adminPassword' => 'atlas'
            ]
        ];
    }

    $content = file_get_contents(DB_FILE);
    $data = json_decode($content, true);

    if (!is_array($data)) {
        return [
            'products' => [],
            'bundles' => [],
            'cities' => [],
            'orders' => [],
            'settings' => []
        ];
    }

    return $data;
}

/**
 * حفظ البيانات في ملف JSON بأمان
 */
function save_db_data($data) {
    $dir = dirname(DB_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(DB_FILE, $json, LOCK_EX) !== false;
}

/**
 * إرسال رد JSON وترميز UTF-8
 */
function send_json($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * فحص صحة رقم الهاتف المغربي (06, 07, 05, +212)
 */
function is_valid_moroccan_phone($phone) {
    $clean = preg_replace('/[\s\-]/', '', $phone);
    return preg_match('/^(0|\+212|00212)[567]\d{8}$/', $clean);
}

/**
 * تنظيف وتحسين رقم الهاتف للواتساب
 */
function format_phone_for_whatsapp($phone) {
    $clean = preg_replace('/[^0-9]/', '', $phone);
    if (strpos($clean, '0') === 0) {
        $clean = '212' . substr($clean, 1);
    }
    return $clean;
}

/**
 * فحص تسجيل دخول مدير المتجر
 */
function is_admin_authenticated() {
    return isset($_SESSION['atlas_admin_logged']) && $_SESSION['atlas_admin_logged'] === true;
}

/**
 * حماية مسارات الإدارة
 */
function require_admin_auth() {
    if (!is_admin_authenticated()) {
        send_json(['error' => 'غير مصرح بالدخول، يرجى تسجيل الدخول'], 401);
    }
}
