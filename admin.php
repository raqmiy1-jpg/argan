<?php
/**
 * مناحل الأطلس - لوحة تحكم الإدارة (PHP / Hostinger)
 */
require_once __DIR__ . '/config.php';

$loginError = null;
$notice = null;

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $pass = $_POST['password'] ?? '';
    $db = get_db_data();
    $correct = $db['settings']['adminPassword'] ?? 'atlas';

    if ($pass === $correct) {
        $_SESSION['atlas_admin_logged'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $loginError = 'كلمة المرور غير صحيحة';
    }
}

// معالجة تسجيل الخروج
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION['atlas_admin_logged'] = false;
    session_destroy();
    header('Location: admin.php');
    exit;
}

// فحص الجلسة
$isLogged = is_admin_authenticated();

if ($isLogged) {
    $db = get_db_data();

    // معالجة تحديث حالة الطلب
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
        $oId = $_POST['order_id'] ?? '';
        $newStatus = $_POST['new_status'] ?? '';

        foreach ($db['orders'] as &$ord) {
            if ($ord['id'] === $oId) {
                $ord['status'] = $newStatus;
                $ord['updatedAt'] = date('c');
                break;
            }
        }
        save_db_data($db);
        $notice = "تم تحديث حالة الطلب $oId بنجاح إلى: $newStatus";
    }

    // معالجة تحديث تكاليف المدن
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_cities') {
        if (isset($_POST['city_rates']) && is_array($_POST['city_rates'])) {
            foreach ($db['cities'] as &$c) {
                if (isset($_POST['city_rates'][$c['id']])) {
                    $c['rate'] = floatval($_POST['city_rates'][$c['id']]);
                }
            }
            save_db_data($db);
            $notice = "تم حفظ أسعار التوصيل الجديدة لجميع المدن بنجاح!";
        }
    }

    // معالجة تحديث الإعدادات
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_settings') {
        $db['settings']['storeName'] = trim($_POST['storeName'] ?? $db['settings']['storeName']);
        $db['settings']['whatsapp'] = preg_replace('/[^0-9]/', '', $_POST['whatsapp'] ?? $db['settings']['whatsapp']);
        $db['settings']['phone'] = trim($_POST['phone'] ?? $db['settings']['phone']);
        $db['settings']['freeShippingThreshold'] = floatval($_POST['freeShippingThreshold'] ?? 500);
        $db['settings']['announcementText'] = trim($_POST['announcementText'] ?? $db['settings']['announcementText']);
        if (!empty($_POST['newPassword'])) {
            $db['settings']['adminPassword'] = trim($_POST['newPassword']);
        }
        save_db_data($db);
        $notice = "تم حفظ إعدادات المتجر وبيانات الاتصال بنجاح!";
    }

    $orders = $db['orders'] ?? [];
    $totalSales = 0;
    $pendingCount = 0;
    $deliveredCount = 0;

    foreach ($orders as $ord) {
        if (($ord['status'] ?? '') !== 'cancelled') {
            $totalSales += floatval($ord['total'] ?? 0);
        }
        if (in_array($ord['status'] ?? '', ['new', 'confirming'])) {
            $pendingCount++;
        }
        if (($ord['status'] ?? '') === 'delivered') {
            $deliveredCount++;
        }
    }

    // فلترة وبحث الطلبات
    $search = trim($_GET['search'] ?? '');
    $filterStatus = trim($_GET['status'] ?? 'all');

    if (!empty($search)) {
        $orders = array_filter($orders, function($o) use ($search) {
            return (stripos($o['id'], $search) !== false) ||
                   (stripos($o['customerName'], $search) !== false) ||
                   (stripos($o['phone'], $search) !== false) ||
                   (stripos($o['city'], $search) !== false);
        });
    }

    if ($filterStatus !== 'all' && !empty($filterStatus)) {
        $orders = array_filter($orders, function($o) use ($filterStatus) {
            return ($o['status'] ?? '') === $filterStatus;
        });
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم مناحل الأطلس | إدارة الطلبات والمبيعات</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #FAF7F2; color: #2C1E14; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Cairo', 'Tajawal', sans-serif; }
    </style>
</head>
<body class="pb-16 text-right">

<?php if (!$isLogged): ?>
    <!-- شاشة تسجيل الدخول -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-[#E8DFD0] shadow-xl space-y-6">
            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-2xl bg-[#2C1E14] text-[#E5B552] mx-auto flex items-center justify-center text-3xl shadow-md">🍯</div>
                <h2 class="text-2xl font-bold font-cairo text-[#2C1E14]">لوحة إدارة مناحل الأطلس</h2>
                <p class="text-xs text-stone-500">منطقة مخصصة لمدير المتجر على Hostinger</p>
            </div>

            <?php if ($loginError): ?>
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
                ⚠️ <?php echo htmlspecialchars($loginError); ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="login">
                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">كلمة المرور:</label>
                    <input type="password" name="password" required placeholder="كلمة المرور (الافتراضية: atlas)" class="w-full px-4 py-3 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-sm focus:outline-hidden focus:ring-2 focus:ring-[#D49B28]">
                </div>
                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#D49B28] to-[#B87F17] text-white font-bold text-sm shadow-md transition-all active:scale-95">
                    دخول إلى لوحة التحكم
                </button>
            </form>

            <div class="text-center pt-2">
                <a href="index.php" class="text-xs text-[#8C6016] hover:underline">← العودة لمتجر العسل</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- شاشة الإدارة الرئيسية -->
    <header class="bg-[#2C1E14] text-white border-b border-[#3E2B1E] sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="index.php" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                    <span>← زيارة المتجر</span>
                </a>
                <a href="admin.php?action=logout" class="px-3 py-1.5 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-200 text-xs font-semibold">
                    تسجيل الخروج
                </a>
            </div>

            <div class="text-right">
                <h1 class="text-base font-extrabold text-[#E5B552] font-cairo">لوحة تحكم مناحل الأطلس</h1>
                <span class="text-[10px] text-stone-400">إدارة الطلبات، المدن، والإعدادات على Hostinger</span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">
        
        <?php if ($notice): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold">
            ✓ <?php echo htmlspecialchars($notice); ?>
        </div>
        <?php endif; ?>

        <!-- KPI Strip -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-[#E8DFD0] shadow-xs space-y-1">
                <span class="text-[11px] text-stone-500 block">إجمالي مبيعات الدفع عند الاستلام:</span>
                <div class="text-2xl font-extrabold text-[#8C6016] font-cairo tabular-nums">
                    <?php echo $totalSales; ?> <span class="text-xs text-[#2C1E14]">د.م</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-[#E8DFD0] shadow-xs space-y-1">
                <span class="text-[11px] text-stone-500 block">إجمالي الطلبات المسجلة:</span>
                <div class="text-2xl font-extrabold text-[#2C1E14] font-cairo tabular-nums">
                    <?php echo count($db['orders'] ?? []); ?>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-[#E8DFD0] shadow-xs space-y-1">
                <span class="text-[11px] text-stone-500 block">طلبات جديدة / قيد التأكيد:</span>
                <div class="text-2xl font-extrabold text-amber-600 font-cairo tabular-nums">
                    <?php echo $pendingCount; ?>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-[#E8DFD0] shadow-xs space-y-1">
                <span class="text-[11px] text-stone-500 block">تم تسليمها بنجاح:</span>
                <div class="text-2xl font-extrabold text-emerald-600 font-cairo tabular-nums">
                    <?php echo $deliveredCount; ?>
                </div>
            </div>
        </div>

        <!-- 1. Orders Management -->
        <div class="bg-white rounded-2xl border border-[#E8DFD0] p-6 space-y-4 shadow-xs">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <h3 class="text-lg font-bold font-cairo text-[#2C1E14]">إدارة الطلبات (<?php echo count($orders); ?>)</h3>

                <form method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="بحث برقم الطلب، الاسم، أو الهاتف..." class="px-3.5 py-2 rounded-xl border border-[#DDD3C1] text-xs text-[#2C1E14] w-64">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-[#DDD3C1] text-xs bg-white">
                        <option value="all" <?php echo $filterStatus === 'all' ? 'selected' : ''; ?>>جميع الحالات</option>
                        <option value="new" <?php echo $filterStatus === 'new' ? 'selected' : ''; ?>>جديد</option>
                        <option value="confirming" <?php echo $filterStatus === 'confirming' ? 'selected' : ''; ?>>قيد التأكيد</option>
                        <option value="confirmed" <?php echo $filterStatus === 'confirmed' ? 'selected' : ''; ?>>مؤكد</option>
                        <option value="shipping" <?php echo $filterStatus === 'shipping' ? 'selected' : ''; ?>>قيد التوصيل</option>
                        <option value="delivered" <?php echo $filterStatus === 'delivered' ? 'selected' : ''; ?>>تم التسليم</option>
                        <option value="cancelled" <?php echo $filterStatus === 'cancelled' ? 'selected' : ''; ?>>ملغى</option>
                    </select>
                    <button type="submit" class="px-3 py-2 bg-[#2C1E14] text-[#E5B552] text-xs rounded-xl font-bold">بحث</button>
                    <?php if (!empty($search) || $filterStatus !== 'all'): ?>
                    <a href="admin.php" class="text-xs text-stone-500 hover:underline">إلغاء الفلتر</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-[#FAF7F2] text-[#554333] border-b border-[#E8DFD0] font-bold">
                        <tr>
                            <th class="p-3 whitespace-nowrap">رقم الطلب</th>
                            <th class="p-3 whitespace-nowrap">العميل والهاتف</th>
                            <th class="p-3 whitespace-nowrap">المدينة والعنوان</th>
                            <th class="p-3 whitespace-nowrap">المنتجات المطلوبة</th>
                            <th class="p-3 whitespace-nowrap">المبلغ الإجمالي</th>
                            <th class="p-3 whitespace-nowrap">حالة الطلب</th>
                            <th class="p-3 whitespace-nowrap">واتساب والموزع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFE7D8]">
                        <?php if (empty($orders)): ?>
                        <tr><td colspan="7" class="p-8 text-center text-stone-400">لا توجد طلبات مطابقة حالياً.</td></tr>
                        <?php else: ?>
                        <?php foreach ($orders as $ord): 
                            $rawPhone = $ord['phone'] ?? '';
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            if (strpos($cleanPhone, '0') === 0) $cleanPhone = '212' . substr($cleanPhone, 1);
                            $waText = urlencode("السلام عليكم سي {$ord['customerName']}، معكم مناحل الأطلس بخصوص طلبيتكم رقم {$ord['id']}. هل العنوان مؤكد للتسليم؟");
                        ?>
                        <tr class="hover:bg-[#FAF7F2]/60">
                            <td class="p-3 font-mono font-bold whitespace-nowrap">
                                <span class="bg-[#FAF3E5] px-2 py-0.5 rounded text-[#8C6016] border border-[#EADDC5]"><?php echo htmlspecialchars($ord['id']); ?></span>
                                <span class="block text-[10px] text-stone-400 font-normal mt-1"><?php echo date('Y/m/d H:i', strtotime($ord['createdAt'])); ?></span>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <strong class="text-[#2C1E14] block"><?php echo htmlspecialchars($ord['customerName']); ?></strong>
                                <a href="tel:<?php echo htmlspecialchars($ord['phone']); ?>" class="text-stone-500 font-mono" dir="ltr"><?php echo htmlspecialchars($ord['phone']); ?></a>
                            </td>
                            <td class="p-3 max-w-xs">
                                <strong class="block text-[#2C1E14]"><?php echo htmlspecialchars($ord['city']); ?></strong>
                                <span class="text-stone-500 text-[11px] block truncate"><?php echo htmlspecialchars($ord['address']); ?></span>
                                <?php if (!empty($ord['notes'])): ?>
                                <span class="text-[10px] text-amber-700 bg-amber-50 px-1 py-0.5 rounded mt-0.5 inline-block">ملاحظة: <?php echo htmlspecialchars($ord['notes']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 max-w-xs">
                                <?php foreach ($ord['items'] ?? [] as $it): ?>
                                <div class="text-[11px] text-stone-700">• <?php echo htmlspecialchars($it['name']); ?> (<?php echo htmlspecialchars($it['weightLabel']); ?>) × <?php echo $it['quantity']; ?></div>
                                <?php endforeach; ?>
                            </td>
                            <td class="p-3 font-bold text-[#8C6016] tabular-nums whitespace-nowrap">
                                <?php echo $ord['total']; ?> د.م
                                <span class="block text-[10px] text-stone-400 font-normal">توصيل: <?php echo $ord['shippingFee']; ?> د.م</span>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="action" value="update_order_status">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($ord['id']); ?>">
                                    <select name="new_status" onchange="this.form.submit()" class="px-2 py-1 rounded text-xs font-bold border cursor-pointer <?php 
                                        echo $ord['status'] === 'delivered' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 
                                            ($ord['status'] === 'shipping' ? 'bg-purple-50 text-purple-800 border-purple-300' : 
                                            ($ord['status'] === 'cancelled' ? 'bg-red-50 text-red-800 border-red-300' : 'bg-amber-50 text-amber-800 border-amber-300')); 
                                    ?>">
                                        <option value="new" <?php echo $ord['status'] === 'new' ? 'selected' : ''; ?>>جديد</option>
                                        <option value="confirming" <?php echo $ord['status'] === 'confirming' ? 'selected' : ''; ?>>قيد التأكيد</option>
                                        <option value="confirmed" <?php echo $ord['status'] === 'confirmed' ? 'selected' : ''; ?>>مؤكد</option>
                                        <option value="shipping" <?php echo $ord['status'] === 'shipping' ? 'selected' : ''; ?>>قيد التوصيل</option>
                                        <option value="delivered" <?php echo $ord['status'] === 'delivered' ? 'selected' : ''; ?>>تم التسليم</option>
                                        <option value="cancelled" <?php echo $ord['status'] === 'cancelled' ? 'selected' : ''; ?>>ملغى</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <a href="https://wa.me/<?php echo $cleanPhone; ?>?text=<?php echo $waText; ?>" target="_blank" class="px-2 py-1 bg-[#25D366] text-white rounded text-xs font-bold inline-flex items-center gap-1 hover:bg-[#20BD5A]">
                                    <span>💬 واتساب</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Cities & Shipping Rates -->
        <div class="bg-white rounded-2xl border border-[#E8DFD0] p-6 space-y-4 shadow-xs">
            <h3 class="text-lg font-bold font-cairo text-[#2C1E14]">جدول تكاليف التوصيل للمدن المغربية</h3>
            <p class="text-xs text-stone-500">عدل سعر التوصيل بالدرهم لكل مدينة وسيتم تطبيقه فوراً للزبون عند الطلب:</p>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="save_cities">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    <?php foreach ($db['cities'] ?? [] as $city): ?>
                    <div class="p-2.5 rounded-xl border border-[#EDE5D8] bg-[#FAF7F2] space-y-1">
                        <span class="font-bold text-xs text-[#2C1E14] block truncate"><?php echo htmlspecialchars($city['name']); ?></span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] text-stone-500">التوصيل:</span>
                            <input type="number" name="city_rates[<?php echo htmlspecialchars($city['id']); ?>]" value="<?php echo $city['rate']; ?>" class="w-16 px-1.5 py-0.5 rounded border border-[#DDD3C1] text-xs font-bold text-[#8C6016] text-center bg-white">
                            <span class="text-xs">د.م</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#2C1E14] text-[#E5B552] text-xs font-bold hover:bg-[#3E2B1E] transition-all">
                    حفظ أسعار التوصيل في النظام
                </button>
            </form>
        </div>

        <!-- 3. Store Settings & WhatsApp -->
        <div class="bg-white rounded-2xl border border-[#E8DFD0] p-6 space-y-4 shadow-xs max-w-2xl">
            <h3 class="text-lg font-bold font-cairo text-[#2C1E14]">إعدادات المتجر ورقم الواتساب</h3>
            
            <form method="POST" class="space-y-3">
                <input type="hidden" name="action" value="save_settings">
                
                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">اسم المتجر:</label>
                    <input type="text" name="storeName" value="<?php echo htmlspecialchars($db['settings']['storeName'] ?? 'مناحل الأطلس'); ?>" class="w-full px-3 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs">
                </div>

                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">رقم واتساب المعتمد (بدون + أو مسافات، مثال: 212661234567):</label>
                    <input type="text" dir="ltr" name="whatsapp" value="<?php echo htmlspecialchars($db['settings']['whatsapp'] ?? '212661234567'); ?>" class="w-full px-3 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs text-left">
                </div>

                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">رقم الهاتف الظاهر في الموقع:</label>
                    <input type="text" dir="ltr" name="phone" value="<?php echo htmlspecialchars($db['settings']['phone'] ?? '+212 661 234 567'); ?>" class="w-full px-3 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs text-left">
                </div>

                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">حد الشحن المجاني (بالدرهم MAD):</label>
                    <input type="number" name="freeShippingThreshold" value="<?php echo htmlspecialchars($db['settings']['freeShippingThreshold'] ?? 500); ?>" class="w-full px-3 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs">
                </div>

                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">نص الشريط الإعلاني العلوي:</label>
                    <input type="text" name="announcementText" value="<?php echo htmlspecialchars($db['settings']['announcementText'] ?? 'عسل طبيعي من خيرات الطبيعة المغربية – الدفع عند الاستلام متاح'); ?>" class="w-full px-3 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs">
                </div>

                <div>
                    <label class="text-xs font-bold text-[#2C1E14] block mb-1">تغيير كلمة مرور الإدارة (اتركها فارغة للإبقاء على الحالية):</label>
                    <input type="password" name="newPassword" placeholder="كلمة مرور جديدة" class="w-full px-3 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#D49B28] to-[#B87F17] text-white font-bold text-xs shadow-md">
                    حفظ التعديلات في النظام
                </button>
            </form>
        </div>

    </main>
<?php endif; ?>

</body>
</html>
