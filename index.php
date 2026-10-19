<?php
/**
 * مناحل الأطلس | من خيرات الطبيعة
 * الواجهة الرئيسية للمتجر الإلكتروني - متوافقة 100% مع Hostinger و PHP
 */
require_once __DIR__ . '/config.php';
$db = get_db_data();
$products = $db['products'] ?? [];
$bundles = $db['bundles'] ?? [];
$cities = $db['cities'] ?? [];
$settings = $db['settings'] ?? [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['storeName'] ?? 'مناحل الأطلس'); ?> | عسل طبيعي مغربي أصيل - الدفع عند الاستلام</title>
    <meta name="description" content="متجر مناحل الأطلس لبيع أجود أنواع العسل الطبيعي المغربي (الزعتر، الدغموس، السدر، الأوكالبتوس). نضمن لكم النقاء والشفافية مع خدمة الدفع عند الاستلام والتوصيل السريع لجميع مدن المغرب.">
    
    <!-- OpenGraph Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($settings['storeName'] ?? 'مناحل الأطلس'); ?> | عسل طبيعي مغربي أصيل">
    <meta property="og:description" content="عسل طبيعي من خيرات الطبيعة المغربية مع الدفع عند الاستلام وتوصيل لباب منزلك في جميع المدن المغربية.">
    <meta property="og:locale" content="ar_MA">

    <!-- Fonts: Cairo & Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for instant Hostinger zero-build runtime) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            DEFAULT: '#D49B28',
                            light: '#E5B552',
                            dark: '#8C6016'
                        },
                        amberbg: '#FAF7F2',
                        darkbrown: '#2C1E14',
                        olive: '#4A5D3E'
                    },
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                        tajawal: ['Tajawal', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #FAF7F2; color: #2C1E14; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Cairo', 'Tajawal', sans-serif; }
        .tabular-nums { font-variant-numeric: tabular-nums; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #F4EFE6; }
        ::-webkit-scrollbar-thumb { background: #D49B28; border-radius: 4px; }
    </style>
</head>
<body class="selection:bg-amber-200 selection:text-amber-900">

    <!-- 1. Top Announcement Bar -->
    <?php if (!empty($settings['showAnnouncement'])): ?>
    <div class="bg-[#2C1E14] text-[#E5B552] text-xs py-2 px-4 text-center font-medium border-b border-[#3E2B1E] flex items-center justify-center gap-2">
        <span>🛡️</span>
        <span class="truncate"><?php echo htmlspecialchars($settings['announcementText'] ?? 'عسل طبيعي من خيرات الطبيعة المغربية – الدفع عند الاستلام متاح'); ?></span>
        <span class="hidden sm:inline text-white/50">|</span>
        <span class="hidden sm:inline-flex items-center gap-1 text-white/80" dir="ltr">
            📞 <?php echo htmlspecialchars($settings['phone'] ?? '+212 661 234 567'); ?>
        </span>
    </div>
    <?php endif; ?>

    <!-- 2. Header -->
    <header class="sticky top-0 z-40 w-full bg-[#FAF7F2]/95 backdrop-blur-md border-b border-[#E8DFD0] py-3.5 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-8">
                
                <!-- Zone 1: Logo -->
                <a href="#hero" class="flex items-center gap-3 text-right group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#E5B552] via-[#D49B28] to-[#996D19] flex items-center justify-center shadow-sm text-white font-bold text-lg">
                        🍯
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl sm:text-2xl font-extrabold text-[#2C1E14] font-cairo group-hover:text-[#B87F17] transition-colors whitespace-nowrap">
                            <?php echo htmlspecialchars($settings['storeName'] ?? 'مناحل الأطلس'); ?>
                        </span>
                        <span class="text-[11px] text-[#6E5947] tracking-wider whitespace-nowrap hidden sm:inline">
                            <?php echo htmlspecialchars($settings['brandTagline'] ?? 'من خيرات الطبيعة المغربية'); ?>
                        </span>
                    </div>
                </a>

                <!-- Zone 2: Navigation Links -->
                <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-[#4A3B2C]">
                    <a href="#hero" class="hover:text-[#B87F17] transition-colors">الرئيسية</a>
                    <a href="#catalog" class="hover:text-[#B87F17] transition-colors">متجر العسل</a>
                    <a href="#bundles" class="hover:text-[#B87F17] transition-colors">باقات العسل</a>
                    <a href="#why-us" class="hover:text-[#B87F17] transition-colors">لماذا تختار عسلنا؟</a>
                    <a href="#story" class="hover:text-[#B87F17] transition-colors">من نحن</a>
                    <a href="#faq" class="hover:text-[#B87F17] transition-colors">الأسئلة الشائعة</a>
                    <a href="#contact" class="hover:text-[#B87F17] transition-colors">اتصل بنا</a>
                </nav>

                <!-- Zone 3: Actions -->
                <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                    <button onclick="toggleCartDrawer(true)" class="relative p-2.5 rounded-full text-[#2C1E14] hover:bg-[#F0E8DA] transition-colors" aria-label="السلة">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span id="cartCountBadge" class="absolute -top-1 -right-1 w-5 h-5 bg-[#D49B28] text-white text-[11px] font-bold rounded-full flex items-center justify-center shadow-xs">0</span>
                    </button>

                    <a href="#catalog" class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-gradient-to-r from-[#D49B28] to-[#B87F17] hover:from-[#B87F17] hover:to-[#996D19] text-white text-sm font-semibold shadow-xs hover:shadow-md transition-all active:scale-95 whitespace-nowrap">
                        اطلب الآن
                    </a>

                    <button onclick="toggleMobileMenu()" class="p-2 lg:hidden text-[#2C1E14] hover:bg-[#F0E8DA] rounded-lg" aria-label="القائمة">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-[#FAF7F2] border-b border-[#E8DFD0] px-6 py-4 space-y-3">
            <a href="#hero" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">الرئيسية</a>
            <a href="#catalog" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">متجر العسل وأنواعه</a>
            <a href="#bundles" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">باقات العسل والعروض</a>
            <a href="#why-us" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">لماذا تختار عسلنا؟</a>
            <a href="#story" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">قصة المناحل</a>
            <a href="#faq" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">الأسئلة الشائعة</a>
            <a href="#contact" onclick="toggleMobileMenu()" class="block py-2 text-right border-b border-[#F0E8DA]">اتصل بنا</a>
            <a href="admin.php" class="block py-2 text-right text-stone-500 text-sm">لوحة تحكم الإدارة 🔐</a>
        </div>
    </header>

    <!-- 3. Hero Section -->
    <section id="hero" class="relative overflow-hidden bg-gradient-to-b from-[#FAF7F2] via-[#F4EFE6] to-[#FAF7F2] pt-8 pb-12 lg:pt-12 lg:pb-16 border-b border-[#E8DFD0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6 text-right">
                    <div class="inline-flex items-center gap-2 text-xs font-semibold text-[#8C6016] bg-[#E5B552]/15 px-3 py-1 rounded-md border border-[#D49B28]/25">
                        <span>✨</span>
                        <span>جني طبيعي موسمي من جبال الأطلس المغربي</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#2C1E14] leading-[1.25] font-cairo">
                        عسل طبيعي بمذاق أصيل <br class="hidden sm:inline">
                        <span class="text-[#B87F17] underline decoration-[#E5B552]/40 decoration-wavy decoration-2">من خيرات المغرب</span>
                    </h1>

                    <p class="text-base sm:text-lg text-[#554333] leading-relaxed max-w-2xl font-normal">
                        اكتشف تشكيلة من أنواع العسل بنكهات طبيعية مميزة، واختر ما يناسب ذوقك واستمتع بطلب سهل مع خدمة الدفع عند الاستلام حتى باب منزلك.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-2">
                        <a href="#catalog" class="px-7 py-3.5 rounded-lg bg-gradient-to-r from-[#D49B28] to-[#B87F17] hover:from-[#B87F17] text-white font-bold text-base shadow-md flex items-center justify-center gap-2">
                            <span>تسوق الآن</span>
                            <span>←</span>
                        </a>
                        <a href="#bundles" class="px-6 py-3.5 rounded-lg bg-white hover:bg-[#F8F4EB] text-[#2C1E14] border border-[#DDD3C1] font-semibold text-base flex items-center justify-center">
                            اكتشف باقات التوفير
                        </a>
                    </div>

                    <div class="pt-3 border-t border-[#E8DFD0] flex flex-wrap items-center gap-6 text-xs text-[#6B5744]">
                        <div class="flex items-center gap-1.5 font-medium">
                            <span>🛡️</span>
                            <span>دفع نقداً عند المعاينة والتسليم</span>
                        </div>
                        <div class="flex items-center gap-1.5 font-medium">
                            <span>🚚</span>
                            <span>توصيل لجميع المدن المغربية</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white bg-[#EDE5D8]">
                        <img src="assets/images/hero_atlas_honey_1792267326345.jpg" alt="مرطبان عسل الأطلس المغربي الذهبي الطبيعي" class="w-full h-[360px] sm:h-[420px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2C1E14]/75 via-transparent to-transparent flex flex-col justify-end p-6 text-white text-right">
                            <span class="text-xs font-semibold text-[#E5B552] mb-1">إنتاج جبلي أصيل</span>
                            <h3 class="text-lg font-bold font-cairo">عسل طبيعي غير مبستر من المناحل المغربية</h3>
                            <p class="text-xs text-white/80 mt-1">مرطبانات زجاجية معقمة تحافظ على نقاء ونكهة العسل الطبيعي</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Trust strip -->
            <div class="mt-12 pt-8 border-t border-[#E8DFD0] grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <div class="p-4 rounded-xl bg-white/70 border border-[#EBE4D8] text-right space-y-1">
                    <div class="w-8 h-8 rounded-lg bg-[#E5B552]/20 text-[#B87F17] flex items-center justify-center mb-2 font-bold">🏆</div>
                    <h4 class="text-sm font-bold text-[#2C1E14]">مصدر طبيعي موثق</h4>
                    <p class="text-xs text-[#705D4C]">مقطوف من خلايا نحل في أعالي الأطلس</p>
                </div>
                <div class="p-4 rounded-xl bg-white/70 border border-[#EBE4D8] text-right space-y-1">
                    <div class="w-8 h-8 rounded-lg bg-[#4A5D3E]/15 text-[#4A5D3E] flex items-center justify-center mb-2 font-bold">💵</div>
                    <h4 class="text-sm font-bold text-[#2C1E14]">الدفع عند الاستلام</h4>
                    <p class="text-xs text-[#705D4C]">عاين طردك وادفع نقداً لمندوب التوصيل</p>
                </div>
                <div class="p-4 rounded-xl bg-white/70 border border-[#EBE4D8] text-right space-y-1">
                    <div class="w-8 h-8 rounded-lg bg-[#D49B28]/20 text-[#B87F17] flex items-center justify-center mb-2 font-bold">📦</div>
                    <h4 class="text-sm font-bold text-[#2C1E14]">توصيل سريع وآمن</h4>
                    <p class="text-xs text-[#705D4C]">تغليف مضاد للصدمات لجميع المدن</p>
                </div>
                <div class="p-4 rounded-xl bg-white/70 border border-[#EBE4D8] text-right space-y-1">
                    <div class="w-8 h-8 rounded-lg bg-[#8C6016]/15 text-[#8C6016] flex items-center justify-center mb-2 font-bold">🌿</div>
                    <h4 class="text-sm font-bold text-[#2C1E14]">تنوع النكهات الأصيلة</h4>
                    <p class="text-xs text-[#705D4C]">زعتر، دغموس، سدر، وأوكالبتوس</p>
                </div>
            </div>

        </div>
    </section>

    <!-- 4. Honey Catalog -->
    <section id="catalog" class="py-16 bg-[#FAF7F2] border-b border-[#E8DFD0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
                <span class="text-xs font-semibold tracking-wider text-[#B87F17] uppercase">تشكيلة فاخرة من المناحل المغربية</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#2C1E14] font-cairo">اكتشف أنواع العسل المغربي الطبيعي</h2>
                <p class="text-sm sm:text-base text-[#614F3F]">أنواع عسل أصيلة مستخلصة بعناية من رحيق أزهار الجبال والمروج والصحاري المغربية. اختر وزنك المفضل واطلب مباشرة مع الدفع عند الاستلام.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($products as $idx => $prod): 
                    $firstWeight = $prod['weights'][0] ?? ['weight' => 250, 'unit' => 'g', 'price' => 140];
                    $imgUrl = str_replace('/src/', '', $prod['image'] ?? '');
                ?>
                <div class="bg-white rounded-2xl border border-[#E8DFD0] overflow-hidden shadow-xs hover:shadow-lg transition-all flex flex-col justify-between" id="card-<?php echo htmlspecialchars($prod['id']); ?>">
                    <div class="relative aspect-[4/3] bg-[#F7F2EA] cursor-pointer" onclick="openProductModal('<?php echo htmlspecialchars($prod['id']); ?>')">
                        <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="w-full h-full object-cover">
                        <div class="absolute top-3 right-3 bg-[#2C1E14]/85 text-white text-[11px] font-medium px-2.5 py-1 rounded-md">
                            📍 <?php echo htmlspecialchars(explode('(', $prod['sourceRegion'] ?? '')[0]); ?>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4 text-right">
                        <div class="space-y-2">
                            <h3 class="text-lg font-bold text-[#2C1E14] font-cairo hover:text-[#B87F17] cursor-pointer" onclick="openProductModal('<?php echo htmlspecialchars($prod['id']); ?>')">
                                <?php echo htmlspecialchars($prod['name']); ?>
                            </h3>
                            <p class="text-xs text-[#6B5744] line-clamp-2"><?php echo htmlspecialchars($prod['shortDescription']); ?></p>
                        </div>

                        <!-- Weights options buttons -->
                        <div class="space-y-1.5 pt-2 border-t border-[#F0E8DA]">
                            <div class="flex items-center justify-between text-xs text-[#705D4C]">
                                <span>الوزن الصافي:</span>
                                <span class="font-bold text-[#2C1E14]" id="weight-label-<?php echo htmlspecialchars($prod['id']); ?>">
                                    <?php echo $firstWeight['weight'] . ' ' . ($firstWeight['unit'] === 'g' ? 'غرام' : 'كلغ'); ?>
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <?php foreach ($prod['weights'] as $wIdx => $w): ?>
                                <button type="button" 
                                    onclick="selectCardWeight('<?php echo htmlspecialchars($prod['id']); ?>', <?php echo $wIdx; ?>, <?php echo $w['weight']; ?>, '<?php echo $w['unit']; ?>', <?php echo $w['price']; ?>)"
                                    class="weight-btn-<?php echo htmlspecialchars($prod['id']); ?> py-1.5 px-2 rounded-lg text-xs font-medium border text-center transition-all <?php echo $wIdx === 0 ? 'bg-[#2C1E14] text-[#E5B552] border-[#2C1E14] font-bold' : 'bg-[#FAF7F2] text-[#4A3B2C] border-[#DDD3C1]'; ?>"
                                    data-idx="<?php echo $wIdx; ?>">
                                    <?php echo $w['weight'] . ($w['unit'] === 'g' ? 'غ' : 'كلغ'); ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Price and Stepper -->
                        <div class="flex items-center justify-between pt-2">
                            <div class="text-right">
                                <span class="text-[11px] text-[#786452] block">السعر:</span>
                                <div class="flex items-baseline gap-1 text-[#2C1E14]">
                                    <span class="text-2xl font-extrabold tabular-nums font-cairo text-[#8C6016]" id="price-val-<?php echo htmlspecialchars($prod['id']); ?>">
                                        <?php echo $firstWeight['price']; ?>
                                    </span>
                                    <span class="text-xs font-bold">درهم</span>
                                </div>
                            </div>

                            <div class="flex items-center border border-[#DDD3C1] rounded-lg overflow-hidden bg-[#FAF7F2]">
                                <button onclick="changeCardQty('<?php echo htmlspecialchars($prod['id']); ?>', 1)" class="px-2.5 py-1 text-sm font-bold hover:bg-[#EBE2D3]">+</button>
                                <span id="qty-<?php echo htmlspecialchars($prod['id']); ?>" class="px-2.5 text-xs font-bold tabular-nums">1</span>
                                <button onclick="changeCardQty('<?php echo htmlspecialchars($prod['id']); ?>', -1)" class="px-2.5 py-1 text-sm font-bold hover:bg-[#EBE2D3]">-</button>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 text-[11px] text-[#4A5D3E]">
                            <span>✓</span>
                            <span>متوفر في المخزون (تسليم سريع)</span>
                        </div>

                        <!-- Buttons -->
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button onclick="addCardToCart('<?php echo htmlspecialchars($prod['id']); ?>')" class="py-2.5 px-3 rounded-lg border border-[#D49B28] text-[#8C6016] hover:bg-[#FAF3E5] font-semibold text-xs flex items-center justify-center gap-1">
                                <span>🛒</span>
                                <span>أضف للسلة</span>
                            </button>
                            <button onclick="buyCardNow('<?php echo htmlspecialchars($prod['id']); ?>')" class="py-2.5 px-3 rounded-lg bg-gradient-to-r from-[#D49B28] to-[#B87F17] text-white font-bold text-xs shadow-xs hover:shadow-md flex items-center justify-center gap-1">
                                <span>اطلب الآن</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 5. Honey Bundles Section -->
    <section id="bundles" class="py-16 bg-[#F4EFE6] border-b border-[#E8DFD0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                <div class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-wider text-[#8C6016] bg-[#E5B552]/20 px-3 py-1 rounded-md">
                    <span>🎁</span>
                    <span>باقات توفير وتذوق خاصة</span>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#2C1E14] font-cairo">باقات العسل المغربي المختارة</h2>
                <p class="text-sm sm:text-base text-[#614F3F]">تشكيلات مميزة تجمع بين أندر وأجود أنواع العسل، مع هدايا خشبية وتوصيل مجاني عند باب منزلك مع خدمة الدفع عند الاستلام.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($bundles as $bundle): 
                    $bImgUrl = str_replace('/src/', '', $bundle['image'] ?? '');
                ?>
                <div class="bg-white rounded-2xl border border-[#E3D8C6] overflow-hidden shadow-xs hover:shadow-xl transition-all flex flex-col justify-between">
                    <div class="relative aspect-[4/3] bg-[#EFE9DD]">
                        <img src="<?php echo htmlspecialchars($bImgUrl); ?>" alt="<?php echo htmlspecialchars($bundle['name']); ?>" class="w-full h-full object-cover">
                        <?php if (!empty($bundle['freeShipping'])): ?>
                        <div class="absolute top-3 right-3 bg-[#4A5D3E] text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                            🚚 توصيل مجاني
                        </div>
                        <?php endif; ?>
                        <div class="absolute bottom-3 left-3 bg-[#2C1E14]/85 text-white text-[11px] font-medium px-2 py-0.5 rounded-sm">
                            <?php echo $bundle['jarsCount']; ?> مرطبانات · <?php echo $bundle['totalWeightGrams']; ?>غ
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4 text-right">
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-[#2C1E14] font-cairo"><?php echo htmlspecialchars($bundle['name']); ?></h3>
                            <p class="text-xs text-[#6B5744] line-clamp-2"><?php echo htmlspecialchars($bundle['description']); ?></p>
                        </div>

                        <div class="pt-2 border-t border-[#F0E8DA] space-y-1.5 text-xs text-[#524131]">
                            <span class="font-bold text-[11px] text-[#8C6016] block">المحتويات:</span>
                            <?php foreach (array_slice($bundle['contents'] ?? [], 0, 3) as $cItem): ?>
                            <div class="flex items-center gap-1.5 text-[11px] text-[#6B5744]">
                                <span class="text-[#4A5D3E]">✓</span>
                                <span class="truncate"><?php echo htmlspecialchars($cItem); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pt-2 border-t border-[#F0E8DA] flex items-center justify-between">
                            <span class="text-xs text-stone-500">السعر:</span>
                            <div class="flex items-baseline gap-1">
                                <?php if (!empty($bundle['originalPrice'])): ?>
                                <span class="text-xs text-stone-400 line-through tabular-nums"><?php echo $bundle['originalPrice']; ?> د.م</span>
                                <?php endif; ?>
                                <span class="text-xl font-extrabold text-[#8C6016] font-cairo tabular-nums"><?php echo $bundle['price']; ?></span>
                                <span class="text-xs font-bold">درهم</span>
                            </div>
                        </div>

                        <div class="space-y-2 pt-1">
                            <button onclick="buyBundleNow('<?php echo htmlspecialchars($bundle['id']); ?>')" class="w-full py-2.5 px-3 rounded-lg bg-gradient-to-r from-[#D49B28] to-[#B87F17] text-white font-bold text-xs shadow-xs hover:shadow-md flex items-center justify-center gap-1.5">
                                <span>طلب الباقة (الدفع عند الاستلام)</span>
                            </button>
                            <button onclick="addBundleToCart('<?php echo htmlspecialchars($bundle['id']); ?>')" class="w-full py-2 px-3 rounded-lg border border-[#D49B28] text-[#8C6016] hover:bg-[#FAF3E5] font-semibold text-xs flex items-center justify-center gap-1.5">
                                <span>إضافة إلى السلة</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 6. Why Choose Us -->
    <section id="why-us" class="py-16 bg-[#FAF7F2] border-b border-[#E8DFD0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                <span class="text-xs font-semibold tracking-wider text-[#B87F17] uppercase">معايير الجودة والشفافية</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#2C1E14] font-cairo">لماذا يفضل زبناؤنا مناحل الأطلس؟</h2>
                <p class="text-sm sm:text-base text-[#614F3F]">نلتزم بتقديم العسل الطبيعي كما أنتجته النحلة في بيئتها البرية، بتغليف يحفظ الجودة وتجربة تسوق مبنية على الصدق والوضوح.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-[#E8DFD0] shadow-xs text-right space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-[#FAF3E5] text-[#B87F17] flex items-center justify-center text-xl font-bold">🧭</div>
                    <span class="text-[11px] font-semibold text-[#4A5D3E] block">جغرافيا مغربية أصيلة</span>
                    <h3 class="text-lg font-bold text-[#2C1E14] font-cairo">مصدر موثق بدقة</h3>
                    <p class="text-xs text-[#6B5744] leading-relaxed">نحدد بدقة منطقة جني كل صنف، سواء من مرتفعات الأطلس المتوسط، أو سفوح سوس ماسة، أو غابات المعمورة الشاسعة.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-[#E8DFD0] shadow-xs text-right space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-[#FAF3E5] text-[#B87F17] flex items-center justify-center text-xl font-bold">⚖️</div>
                    <span class="text-[11px] font-semibold text-[#4A5D3E] block">شفافية كاملة</span>
                    <h3 class="text-lg font-bold text-[#2C1E14] font-cairo">أوزان صافية وحقيقية</h3>
                    <p class="text-xs text-[#6B5744] leading-relaxed">أوزان حقيقية وصافية تبدأ من 250 غرام وحتى الكيلوغرام، مع ذكر موسم القطف والخصائص الطبيعية لقوام وطعم كل نوع.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-[#E8DFD0] shadow-xs text-right space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-[#FAF3E5] text-[#B87F17] flex items-center justify-center text-xl font-bold">📦</div>
                    <span class="text-[11px] font-semibold text-[#4A5D3E] block">تغليف محكم</span>
                    <h3 class="text-lg font-bold text-[#2C1E14] font-cairo">تغليف آمن ضد الكسر</h3>
                    <p class="text-xs text-[#6B5744] leading-relaxed">مرطبانات زجاجية صحية معزولة بأغلفة خاصة ومبطنة لحمايتها بالكامل من الكسر أثناء الشحن عبر جميع المدن المغربية.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-[#E8DFD0] shadow-xs text-right space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-[#FAF3E5] text-[#B87F17] flex items-center justify-center text-xl font-bold">💵</div>
                    <span class="text-[11px] font-semibold text-[#4A5D3E] block">ثقة واطمئنان</span>
                    <h3 class="text-lg font-bold text-[#2C1E14] font-cairo">دفع نقداً بعد المعاينة</h3>
                    <p class="text-xs text-[#6B5744] leading-relaxed">لا تحتاج لبطاقة بنكية. سجّل طلبك في أقل من دقيقة، وادفع نقداً لمندوب التوصيل بعد استلام ومعاينة طردك بكل راحة.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Story Section -->
    <section id="story" class="py-16 bg-[#FAF7F2] border-b border-[#E8DFD0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-5 relative">
                    <div class="rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-[#EDE5D8]">
                        <img src="assets/images/honey_jar_thyme_1792267326022.jpg" alt="عسل الزعتر الجبلي" class="w-full h-[380px] object-cover">
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-5 text-right">
                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#8C6016] bg-[#E5B552]/20 px-3 py-1 rounded-md">
                        <span>🏔️</span>
                        <span>أصالة وتقاليد النحالة المغربية</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#2C1E14] font-cairo">من الطبيعة المغربية إلى مائدتك</h2>

                    <p class="text-sm sm:text-base text-[#574535] leading-relaxed">
                        انطلقت فكرة <strong class="text-[#2C1E14]">«مناحل الأطلس»</strong> من شغف عميق بتراث تربية النحل في مرتفعات وسفوح جبال الأطلس المغربية. حيث تمتاز هذه المناطق بتنوع نباتي فريد يضم شجيرات الزعتر البري، ونبات الدغموس الشوكي النادر، وغابات الأوكالبتوس العطرة، وأشجار السدر العتيقة.
                    </p>
                    <p class="text-sm sm:text-base text-[#574535] leading-relaxed">
                        نعتمد على ممارسات النحالة المستدامة التي تراعي صحة خلايا النحل وتوازن النظام البيئي الجبلي. نترك للعسل وقته الطبيعي ليكتمل نضجه دون تدخل صناعي أو تسخين يفقده خصائصه الحيوية وقوامه الأصيل.
                    </p>

                    <div class="p-3.5 bg-[#F4EFE6] rounded-xl border border-[#E3D9C9] text-xs text-[#7A6653]">
                        📍 <strong>موقع المناحل:</strong> <?php echo htmlspecialchars($settings['address'] ?? 'جبال الأطلس المتوسط، المملكة المغربية'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FAQ Section -->
    <section id="faq" class="py-16 bg-[#F4EFE6] border-b border-[#E8DFD0]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <div class="text-center mb-12 space-y-3">
                <span class="text-xs font-semibold tracking-wider text-[#8C6016] bg-[#E5B552]/20 px-3 py-1 rounded-md">❓ إجابات واضحة وشفافة</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2C1E14] font-cairo">الأسئلة الشائعة</h2>
                <p class="text-sm text-[#614F3F]">كل ما تود معرفته عن أنواع العسل، الشحن، وطريقة الدفع عند الاستلام.</p>
            </div>

            <div class="space-y-3">
                <details class="bg-white rounded-xl border border-[#E3D8C6] p-4 sm:p-5 group cursor-pointer" open>
                    <summary class="font-bold text-[#2C1E14] list-none flex justify-between items-center font-cairo text-sm sm:text-base">
                        <span>ما هي أنواع العسل المغربي المتوفرة لديكم؟</span>
                        <span class="text-[#B87F17] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-[#554333] pt-3 border-t border-[#F0E8DA] mt-3 leading-relaxed">
                        نوفر عسل الزعتر الجبلي الأصيل، عسل الدغموس الحر، عسل السدر الملكي، عسل الأوكالبتوس، عسل زهور الأطلس، وعسل الخروب، بالإضافة إلى باقات التذوق والإهداء.
                    </p>
                </details>

                <details class="bg-white rounded-xl border border-[#E3D8C6] p-4 sm:p-5 group cursor-pointer">
                    <summary class="font-bold text-[#2C1E14] list-none flex justify-between items-center font-cairo text-sm sm:text-base">
                        <span>ما هي الأوزان المتاحة لكل نوع؟</span>
                        <span class="text-[#B87F17] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-[#554333] pt-3 border-t border-[#F0E8DA] mt-3 leading-relaxed">
                        تتوفر أعسالنا بمرطبانات زجاجية معقمة بأوزان: 250 غرام (ربع كيلو)، 500 غرام (نصف كيلو)، و 1000 غرام (كيلوغرام كامل)، بالإضافة إلى باقات التذوق بوزن 750غ و 2 كلغ.
                    </p>
                </details>

                <details class="bg-white rounded-xl border border-[#E3D8C6] p-4 sm:p-5 group cursor-pointer">
                    <summary class="font-bold text-[#2C1E14] list-none flex justify-between items-center font-cairo text-sm sm:text-base">
                        <span>كيف يمكنني طلب العسل؟</span>
                        <span class="text-[#B87F17] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-[#554333] pt-3 border-t border-[#F0E8DA] mt-3 leading-relaxed">
                        اضغط على زر «اطلب الآن» عند أي منتج، واملأ اسمك، رقم هاتفك، ومدينتك وعنوانك بالتفصيل، ثم اضغط على «تأكيد الطلب». سيتصل بك فريقنا لتأكيد التسليم.
                    </p>
                </details>

                <details class="bg-white rounded-xl border border-[#E3D8C6] p-4 sm:p-5 group cursor-pointer">
                    <summary class="font-bold text-[#2C1E14] list-none flex justify-between items-center font-cairo text-sm sm:text-base">
                        <span>هل يمكنني الدفع عند الاستلام (COD)؟</span>
                        <span class="text-[#B87F17] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-[#554333] pt-3 border-t border-[#F0E8DA] mt-3 leading-relaxed">
                        نعم بكل تأكيد! الدفع عند الاستلام هو وسيلتنا الأساسية. لا تدفع أي درهم حتى يصلك المندوب وتستلم طردك وتتفحصه بنفسك.
                    </p>
                </details>

                <details class="bg-white rounded-xl border border-[#E3D8C6] p-4 sm:p-5 group cursor-pointer">
                    <summary class="font-bold text-[#2C1E14] list-none flex justify-between items-center font-cairo text-sm sm:text-base">
                        <span>كم تستغرق مدة التوصيل؟</span>
                        <span class="text-[#B87F17] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-[#554333] pt-3 border-t border-[#F0E8DA] mt-3 leading-relaxed">
                        تستغرق ما بين 24 إلى 48 ساعة في المدن الكبرى (الدار البيضاء، الرباط، مراكش، طنجة، فاس، أكادير، مكناس، القنيطرة)، وما بين 48 إلى 72 ساعة في باقي المناطق.
                    </p>
                </details>

                <details class="bg-white rounded-xl border border-[#E3D8C6] p-4 sm:p-5 group cursor-pointer">
                    <summary class="font-bold text-[#2C1E14] list-none flex justify-between items-center font-cairo text-sm sm:text-base">
                        <span>كيف أحافظ على العسل؟</span>
                        <span class="text-[#B87F17] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="text-xs sm:text-sm text-[#554333] pt-3 border-t border-[#F0E8DA] mt-3 leading-relaxed">
                        يُحفظ في مرطبانه الزجاجي في درجة حرارة الغرفة (18-24°C) في مكان جاف ومظلم بعيداً عن الرطوبة والشمس المباشرة. لا تضعه في الثلاجة وتجنب الملاعق المبللة.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- 9. Footer -->
    <footer id="contact" class="bg-[#211710] text-[#EDE5D8] border-t border-[#36271A] pt-14 pb-10 text-right">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-5 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#E5B552] to-[#B87F17] flex items-center justify-center text-white text-lg font-bold">🍯</div>
                        <div>
                            <span class="text-xl font-bold text-white font-cairo block"><?php echo htmlspecialchars($settings['storeName'] ?? 'مناحل الأطلس'); ?></span>
                            <span class="text-xs text-[#C5A376]"><?php echo htmlspecialchars($settings['brandTagline'] ?? 'من خيرات الطبيعة المغربية'); ?></span>
                        </div>
                    </div>
                    <p class="text-xs text-stone-300 leading-relaxed">
                        علامة تجارية مغربية متخصصة في إنتاج وتوزيع أجود أنواع العسل الطبيعي المستخرج من المناحل الجبلية. نلتزم بأعلى معايير الأمانة ونوفر خدمة الدفع عند الاستلام بعد معاينة الطرد.
                    </p>
                    <div class="text-xs text-[#E5B552]">🛡️ الدفع عند الاستلام متاح لجميع المدن المغربية</div>
                </div>

                <div class="lg:col-span-3 space-y-2">
                    <h4 class="text-sm font-bold text-white font-cairo border-b border-[#36271A] pb-2">روابط سريعة</h4>
                    <ul class="space-y-1.5 text-xs text-stone-300">
                        <li><a href="#catalog" class="hover:text-[#E5B552]">متجر العسل المغربي</a></li>
                        <li><a href="#bundles" class="hover:text-[#E5B552]">باقات التذوق والعروض</a></li>
                        <li><a href="#why-us" class="hover:text-[#E5B552]">لماذا تختار عسلنا؟</a></li>
                        <li><a href="#faq" class="hover:text-[#E5B552]">الأسئلة الشائعة</a></li>
                        <li><a href="admin.php" class="text-stone-400 hover:text-white pt-2 block">لوحة تحكم الإدارة 🔐</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-4 space-y-2">
                    <h4 class="text-sm font-bold text-white font-cairo border-b border-[#36271A] pb-2">بيانات الاتصال</h4>
                    <div class="space-y-2 text-xs text-stone-300">
                        <div>📍 <?php echo htmlspecialchars($settings['address'] ?? 'المملكة المغربية'); ?></div>
                        <div dir="ltr" class="text-right">📞 <?php echo htmlspecialchars($settings['phone'] ?? '+212 661 234 567'); ?></div>
                        <div>✉️ <?php echo htmlspecialchars($settings['email'] ?? 'contact@manahil-atlas.ma'); ?></div>
                        <div class="pt-2">
                            <a href="https://wa.me/<?php echo htmlspecialchars($settings['whatsapp'] ?? '212661234567'); ?>" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-[#25D366] text-white text-xs font-bold hover:bg-[#20BD5A]">
                                <span>💬 مراسلة عبر واتساب</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-[#36271A] flex flex-col sm:flex-row items-center justify-between text-xs text-stone-400 gap-3">
                <p>جميع الحقوق محفوظة © <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['storeName'] ?? 'مناحل الأطلس'); ?> · المملكة المغربية</p>
                <p>عسل طبيعي أصيل مع الدفع عند الاستلام</p>
            </div>
        </div>
    </footer>

    <!-- 10. Floating WhatsApp Button -->
    <a href="https://wa.me/<?php echo htmlspecialchars($settings['whatsapp'] ?? '212661234567'); ?>?text=<?php echo urlencode('السلام عليكم مناحل الأطلس، أود الاستفسار حول توفر العسل وخدمة الدفع عند الاستلام.'); ?>" target="_blank" class="fixed bottom-6 left-6 z-40 flex items-center gap-2 bg-[#25D366] hover:bg-[#20BD5A] text-white py-3 px-4 rounded-full shadow-xl transition-all active:scale-95">
        <span class="text-lg">💬</span>
        <span class="text-xs font-bold font-cairo hidden sm:inline">طلب أو استفسار عبر واتساب</span>
    </a>

    <!-- 11. Cart Drawer Modal -->
    <div id="cartDrawer" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs" onclick="toggleCartDrawer(false)"></div>
        <div class="relative w-full max-w-md bg-[#FAF7F2] h-full shadow-2xl flex flex-col justify-between mr-auto z-10 text-right">
            <div class="p-5 border-b border-[#E8DFD0] bg-white flex items-center justify-between">
                <button onclick="toggleCartDrawer(false)" class="p-1.5 text-stone-400 hover:text-stone-800 text-lg">✕</button>
                <h3 class="text-lg font-bold text-[#2C1E14] font-cairo">سلة التسوق (<span id="cartCountHeader">0</span>)</h3>
            </div>

            <div id="cartItemsList" class="flex-1 overflow-y-auto p-5 space-y-3">
                <!-- Cart items rendered by JavaScript -->
            </div>

            <div id="cartFooter" class="p-5 border-t border-[#E8DFD0] bg-white space-y-3">
                <div class="flex justify-between text-xs text-stone-600">
                    <span>المجموع الفرعي:</span>
                    <span id="cartSubtotalVal" class="font-bold text-[#2C1E14] tabular-nums">0 درهم</span>
                </div>
                <button onclick="openCheckoutFromCart()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#D49B28] to-[#B87F17] text-white font-bold text-sm shadow-md flex items-center justify-center gap-2">
                    <span>متابعة الطلب والدفع عند الاستلام</span>
                    <span>←</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 12. Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 z-50 hidden overflow-y-auto p-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="toggleCheckoutModal(false)"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#E8DFD0] z-10 text-right">
            <div class="bg-[#2C1E14] text-white p-5 flex items-center justify-between">
                <button onclick="toggleCheckoutModal(false)" class="text-stone-300 hover:text-white text-lg">✕</button>
                <div>
                    <h3 class="text-lg font-bold font-cairo text-[#E5B552]">إتمام الطلب – الدفع عند الاستلام</h3>
                    <p class="text-xs text-stone-300">أدخل بياناتك البسيطة لتأكيد الإرسال لباب بيتك</p>
                </div>
            </div>

            <div class="p-6 max-h-[80vh] overflow-y-auto space-y-5">
                <div class="p-3 bg-[#FAF7F2] rounded-xl border border-[#E8DFD0] text-xs space-y-2">
                    <span class="font-bold text-[#8C6016] block">ملخص طلبك:</span>
                    <div id="checkoutSummaryItems" class="space-y-1 text-stone-700"></div>
                    <div class="pt-2 border-t border-[#E8DFD0] flex justify-between font-bold text-sm text-[#2C1E14]">
                        <span>المبلغ الإجمالي عند الاستلام:</span>
                        <span id="checkoutGrandTotal" class="text-[#8C6016] font-cairo text-lg tabular-nums">0 د.م</span>
                    </div>
                </div>

                <div id="checkoutError" class="hidden p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl"></div>

                <form id="checkoutForm" onsubmit="submitCheckoutOrder(event)" class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-[#2C1E14] block mb-1">الاسم الكامل *</label>
                        <input type="text" id="custName" required placeholder="مثال: يوسف الإدريسي" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-sm focus:outline-hidden focus:ring-2 focus:ring-[#D49B28]">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#2C1E14] block mb-1">رقم الهاتف المغربي (للاتصال والتأكيد) *</label>
                        <input type="tel" id="custPhone" dir="ltr" required placeholder="06XXXXXXXX أو 07XXXXXXXX" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-sm text-left focus:outline-hidden focus:ring-2 focus:ring-[#D49B28]">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#2C1E14] block mb-1">المدينة أو الإقليم *</label>
                        <select id="custCity" onchange="updateShippingCalc()" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-sm focus:outline-hidden focus:ring-2 focus:ring-[#D49B28]">
                            <?php foreach ($cities as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['name']); ?>" data-rate="<?php echo $c['rate']; ?>">
                                <?php echo htmlspecialchars($c['name']); ?> — شحن: <?php echo $c['rate']; ?> درهم
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#2C1E14] block mb-1">العنوان بالتفصيل *</label>
                        <textarea id="custAddress" rows="2" required placeholder="الحي، اسم الشارع، رقم العمارة / المنزل..." class="w-full px-3.5 py-2.5 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-sm focus:outline-hidden focus:ring-2 focus:ring-[#D49B28]"></textarea>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-stone-600 block mb-1">ملاحظات للموزع (اختياري)</label>
                        <input type="text" id="custNotes" placeholder="مثال: الاتصال قبل الوصول" class="w-full px-3.5 py-2 rounded-xl border border-[#DDD3C1] bg-[#FAF7F2] text-xs">
                    </div>

                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs flex items-center justify-between">
                        <span>💵 الدفع نقداً عند الاستلام (COD) بعد المعاينة</span>
                        <span class="text-[#4A5D3E] font-bold">متاح ومضمون</span>
                    </div>

                    <button type="submit" id="btnSubmitOrder" class="w-full py-4 rounded-2xl bg-gradient-to-r from-[#D49B28] to-[#B87F17] hover:from-[#B87F17] text-white font-extrabold text-base shadow-lg transition-all active:scale-95">
                        تأكيد الطلب الآن (الدفع عند الاستلام)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 13. Order Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto p-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#E8DFD0] z-10 text-center">
            <div class="bg-[#2C1E14] text-white p-6 space-y-2">
                <div class="w-14 h-14 rounded-full bg-[#4A5D3E] text-white mx-auto flex items-center justify-center text-2xl font-bold">✓</div>
                <h3 class="text-2xl font-bold font-cairo text-[#E5B552]">تم تسجيل طلبك بنجاح!</h3>
                <div class="inline-block bg-white/10 px-3 py-1 rounded-full text-xs font-mono">
                    رقم الطلب: <strong id="confirmedOrderId" class="text-white"></strong>
                </div>
                <p class="text-xs text-stone-300">سيتصل بك فريقنا هاتفياً خلال ساعات لتأكيد الموعد والتسليم.</p>
            </div>

            <div class="p-6 text-right space-y-4">
                <div class="p-3 bg-[#FAF7F2] rounded-xl border border-[#E8DFD0] text-xs space-y-1.5" id="confirmedOrderDetails"></div>

                <a id="btnWhatsAppConfirm" href="#" target="_blank" class="w-full py-3 rounded-xl bg-[#25D366] text-white text-xs font-bold flex items-center justify-center gap-2">
                    <span>💬 تواصل معنا عبر واتساب بخصوص هذا الطلب</span>
                </a>

                <button onclick="toggleConfirmModal(false)" class="w-full py-2.5 rounded-xl bg-[#2C1E14] text-[#E5B552] text-xs font-bold">
                    العودة للمتجر ومتابعة التصفح
                </button>
            </div>
        </div>
    </div>

    <!-- 14. Product Detail Modal -->
    <div id="productDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto p-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="closeProductModal()"></div>
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#E8DFD0] z-10 text-right p-6 max-h-[90vh] overflow-y-auto space-y-6" id="productDetailBody">
            <!-- Rendered by JS -->
        </div>
    </div>

    <!-- Data Injection for Client Interactions -->
    <script>
        const STORE_PRODUCTS = <?php echo json_encode($products, JSON_UNESCAPED_UNICODE); ?>;
        const STORE_BUNDLES = <?php echo json_encode($bundles, JSON_UNESCAPED_UNICODE); ?>;
        const STORE_SETTINGS = <?php echo json_encode($settings, JSON_UNESCAPED_UNICODE); ?>;

        let cart = [];
        try {
            const saved = localStorage.getItem('manahil_cart');
            if (saved) cart = JSON.parse(saved);
        } catch(e) {}

        let cardSelections = {};
        STORE_PRODUCTS.forEach(p => {
            cardSelections[p.id] = { weightIdx: 0, qty: 1 };
        });

        function saveCart() {
            try { localStorage.setItem('manahil_cart', JSON.stringify(cart)); } catch(e) {}
            updateCartUI();
        }

        function updateCartUI() {
            const count = cart.reduce((sum, i) => sum + i.quantity, 0);
            const badge = document.getElementById('cartCountBadge');
            const headerCount = document.getElementById('cartCountHeader');
            if (badge) badge.innerText = count;
            if (headerCount) headerCount.innerText = count;

            const list = document.getElementById('cartItemsList');
            const footer = document.getElementById('cartFooter');
            const subtotalVal = document.getElementById('cartSubtotalVal');

            if (!list) return;

            if (cart.length === 0) {
                list.innerHTML = `
                    <div class="h-48 flex flex-col items-center justify-center text-center space-y-2 text-stone-400">
                        <span class="text-4xl">🍯</span>
                        <p class="text-sm font-bold text-[#2C1E14]">سلة التسوق فارغة</p>
                        <p class="text-xs">اختر عسلك المفضل وأضفه للسلة.</p>
                    </div>`;
                if (footer) footer.classList.add('hidden');
            } else {
                if (footer) footer.classList.remove('hidden');
                let subtotal = 0;
                list.innerHTML = cart.map((item, idx) => {
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    return `
                        <div class="bg-white p-3 rounded-xl border border-[#E8DFD0] flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5">
                                <img src="${item.image.replace('/src/', '')}" class="w-12 h-12 rounded-lg object-cover bg-stone-100">
                                <div>
                                    <strong class="text-[#2C1E14] block">${item.name}</strong>
                                    <span class="text-stone-500 text-[11px]">${item.weightLabel}</span>
                                    <div class="text-[#8C6016] font-bold tabular-nums">${item.price} د.م × ${item.quantity} = ${itemTotal} د.م</div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5">
                                <button onclick="removeFromCart(${idx})" class="text-stone-400 hover:text-red-500 text-xs">✕</button>
                                <div class="flex items-center border border-[#DDD3C1] rounded bg-[#FAF7F2]">
                                    <button onclick="changeCartQty(${idx}, 1)" class="px-2 py-0.5 text-xs font-bold">+</button>
                                    <span class="px-2 text-xs font-bold">${item.quantity}</span>
                                    <button onclick="changeCartQty(${idx}, -1)" class="px-2 py-0.5 text-xs font-bold">-</button>
                                </div>
                            </div>
                        </div>`;
                }).join('');
                if (subtotalVal) subtotalVal.innerText = subtotal + ' درهم';
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        function toggleCartDrawer(open) {
            const d = document.getElementById('cartDrawer');
            if (d) d.classList.toggle('hidden', !open);
            if (open) updateCartUI();
        }

        function selectCardWeight(prodId, wIdx, weight, unit, price) {
            cardSelections[prodId].weightIdx = wIdx;
            const lbl = document.getElementById(`weight-label-${prodId}`);
            const priceVal = document.getElementById(`price-val-${prodId}`);
            if (lbl) lbl.innerText = `${weight} ${unit === 'g' ? 'غرام' : 'كلغ'}`;
            if (priceVal) priceVal.innerText = price;

            document.querySelectorAll(`.weight-btn-${prodId}`).forEach(btn => {
                if (parseInt(btn.getAttribute('data-idx')) === wIdx) {
                    btn.className = `weight-btn-${prodId} py-1.5 px-2 rounded-lg text-xs font-bold border text-center bg-[#2C1E14] text-[#E5B552] border-[#2C1E14]`;
                } else {
                    btn.className = `weight-btn-${prodId} py-1.5 px-2 rounded-lg text-xs font-medium border text-center bg-[#FAF7F2] text-[#4A3B2C] border-[#DDD3C1]`;
                }
            });
        }

        function changeCardQty(prodId, delta) {
            const current = cardSelections[prodId]?.qty || 1;
            const next = Math.max(1, current + delta);
            cardSelections[prodId].qty = next;
            const el = document.getElementById(`qty-${prodId}`);
            if (el) el.innerText = next;
        }

        function addCardToCart(prodId) {
            const prod = STORE_PRODUCTS.find(p => p.id === prodId);
            if (!prod) return;
            const sel = cardSelections[prodId] || { weightIdx: 0, qty: 1 };
            const wOpt = prod.weights[sel.weightIdx] || prod.weights[0];

            const itemId = `${prod.id}-${wOpt.weight}${wOpt.unit}`;
            const existing = cart.find(i => i.itemId === itemId);
            if (existing) {
                existing.quantity += sel.qty;
            } else {
                cart.push({
                    itemId,
                    id: prod.id,
                    type: 'product',
                    name: prod.name,
                    weightLabel: `${wOpt.weight} ${wOpt.unit === 'g' ? 'غرام' : 'كلغ'}`,
                    weightGrams: wOpt.weight,
                    price: wOpt.price,
                    quantity: sel.qty,
                    image: prod.image
                });
            }
            saveCart();
            toggleCartDrawer(true);
        }

        function buyCardNow(prodId) {
            addCardToCart(prodId);
            toggleCartDrawer(false);
            openCheckout();
        }

        function addBundleToCart(bundleId) {
            const bundle = STORE_BUNDLES.find(b => b.id === bundleId);
            if (!bundle) return;
            const itemId = `bundle-${bundle.id}`;
            const existing = cart.find(i => i.itemId === itemId);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    itemId,
                    id: bundle.id,
                    type: 'bundle',
                    name: bundle.name,
                    weightLabel: `${bundle.totalWeightGrams} غرام (${bundle.jarsCount} مرطبانات)`,
                    price: bundle.price,
                    quantity: 1,
                    image: bundle.image
                });
            }
            saveCart();
            toggleCartDrawer(true);
        }

        function buyBundleNow(bundleId) {
            addBundleToCart(bundleId);
            toggleCartDrawer(false);
            openCheckout();
        }

        function removeFromCart(idx) {
            cart.splice(idx, 1);
            saveCart();
        }

        function changeCartQty(idx, delta) {
            if (!cart[idx]) return;
            cart[idx].quantity += delta;
            if (cart[idx].quantity <= 0) {
                cart.splice(idx, 1);
            }
            saveCart();
        }

        function openCheckoutFromCart() {
            toggleCartDrawer(false);
            openCheckout();
        }

        function toggleCheckoutModal(open) {
            const m = document.getElementById('checkoutModal');
            if (m) m.classList.toggle('hidden', !open);
        }

        function openCheckout() {
            if (cart.length === 0) {
                alert('سلة التسوق فارغة، يرجى اختيار منتج أولاً.');
                return;
            }
            toggleCheckoutModal(true);
            updateShippingCalc();
        }

        function updateShippingCalc() {
            const summaryEl = document.getElementById('checkoutSummaryItems');
            const totalEl = document.getElementById('checkoutGrandTotal');
            const citySelect = document.getElementById('custCity');
            if (!summaryEl || !totalEl || !citySelect) return;

            let subtotal = 0;
            summaryEl.innerHTML = cart.map(i => {
                const lineTotal = i.price * i.quantity;
                subtotal += lineTotal;
                return `<div class="flex justify-between"><span>• ${i.name} (${i.weightLabel}) × ${i.quantity}</span><span>${lineTotal} د.م</span></div>`;
            }).join('');

            const selectedOption = citySelect.options[citySelect.selectedIndex];
            const rate = parseFloat(selectedOption?.getAttribute('data-rate') || 30);
            const freeThreshold = parseFloat(STORE_SETTINGS.freeShippingThreshold || 500);

            const shippingFee = (freeThreshold > 0 && subtotal >= freeThreshold) ? 0 : rate;
            const grandTotal = subtotal + shippingFee;

            summaryEl.innerHTML += `
                <div class="pt-1.5 border-t border-stone-200 flex justify-between text-stone-500 text-[11px]">
                    <span>مصاريف التوصيل:</span>
                    <span>${shippingFee === 0 ? 'مجاني 0 درهم' : shippingFee + ' درهم'}</span>
                </div>`;

            totalEl.innerText = `${grandTotal} درهم مغربي`;
        }

        async function submitCheckoutOrder(e) {
            e.preventDefault();
            const errBox = document.getElementById('checkoutError');
            const btn = document.getElementById('btnSubmitOrder');
            errBox.classList.add('hidden');

            const customerName = document.getElementById('custName').value.trim();
            const phone = document.getElementById('custPhone').value.trim();
            const city = document.getElementById('custCity').value;
            const address = document.getElementById('custAddress').value.trim();
            const notes = document.getElementById('custNotes').value.trim();

            if (!/^(0|\+212|00212)[567]\d{8}$/.test(phone.replace(/[\s\-]/g, ''))) {
                errBox.innerText = 'يرجى إدخال رقم هاتف مغربي صحيح (مثال: 0661234567)';
                errBox.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.innerText = 'جاري تسجيل طلبك...';

            try {
                const res = await fetch('api/orders.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        customerName,
                        phone,
                        city,
                        address,
                        notes,
                        items: cart
                    })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'حدث خطأ أثناء الطلب');

                cart = [];
                saveCart();
                toggleCheckoutModal(false);

                // Show Confirmation
                showConfirmation(data.order);
            } catch (err) {
                errBox.innerText = err.message || 'فشل الاتصال بالخادم، يرجى المحاولة ثانية';
                errBox.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerText = 'تأكيد الطلب الآن (الدفع عند الاستلام)';
            }
        }

        function showConfirmation(order) {
            document.getElementById('confirmedOrderId').innerText = order.id;
            const details = document.getElementById('confirmedOrderDetails');
            if (details) {
                details.innerHTML = `
                    <div><strong>العميل:</strong> ${order.customerName}</div>
                    <div dir="ltr" class="text-right"><strong>الهاتف:</strong> ${order.phone}</div>
                    <div><strong>المدينة والعنوان:</strong> ${order.city} - ${order.address}</div>
                    <div><strong>طريقة الدفع:</strong> الدفع عند الاستلام نقداً (COD)</div>
                    <div class="pt-2 border-t border-stone-200 font-bold text-sm text-[#8C6016]">
                        المجموع الإجمالي: ${order.total} درهم مغربي
                    </div>`;
            }

            const waBtn = document.getElementById('btnWhatsAppConfirm');
            if (waBtn) {
                const waPhone = STORE_SETTINGS.whatsapp || '212661234567';
                const msg = encodeURIComponent(`السلام عليكم مناحل الأطلس، أود الاستفسار بخصوص طلبيتي رقم: ${order.id} باسم: ${order.customerName}`);
                waBtn.href = `https://wa.me/${waPhone}?text=${msg}`;
            }

            toggleConfirmModal(true);
        }

        function toggleConfirmModal(open) {
            const m = document.getElementById('confirmModal');
            if (m) m.classList.toggle('hidden', !open);
        }

        function openProductModal(prodId) {
            const prod = STORE_PRODUCTS.find(p => p.id === prodId);
            if (!prod) return;
            const body = document.getElementById('productDetailBody');
            const img = prod.image.replace('/src/', '');
            body.innerHTML = `
                <div class="flex justify-between items-start">
                    <button onclick="closeProductModal()" class="text-stone-400 hover:text-stone-800 text-lg">✕</button>
                    <div>
                        <span class="text-xs bg-[#E5B552]/20 text-[#8C6016] px-2.5 py-1 rounded font-bold">${prod.sourceRegion}</span>
                        <h2 class="text-2xl font-bold font-cairo text-[#2C1E14] mt-1">${prod.name}</h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <img src="${img}" class="rounded-2xl w-full h-64 object-cover">
                    <div class="space-y-3">
                        <p class="text-xs text-stone-600 leading-relaxed">${prod.detailedDescription}</p>
                        <div class="text-xs space-y-1 bg-[#FAF7F2] p-3 rounded-xl border border-[#E8DFD0]">
                            <div><strong>موسم القطف:</strong> ${prod.harvestSeason}</div>
                            <div><strong>المذاق والقوام:</strong> ${prod.textureAndTaste}</div>
                        </div>
                        <div class="p-3 bg-amber-50 border border-amber-200 text-amber-900 text-[11px] rounded-xl">
                            ⚠️ <strong>تنبيه صحي:</strong> العسل الطبيعي غير مناسب للأطفال الرضع دون سن 12 شهراً.
                        </div>
                        <button onclick="buyCardNow('${prod.id}'); closeProductModal();" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#D49B28] to-[#B87F17] text-white font-bold text-xs shadow-md">
                            اطلب الآن والدفع عند الاستلام
                        </button>
                    </div>
                </div>`;
            document.getElementById('productDetailModal').classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('productDetailModal').classList.add('hidden');
        }

        // Initialize UI
        updateCartUI();
    </script>
</body>
</html>
