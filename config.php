<?php
/**
 * مناحل الأطلس | من خيرات الطبيعة
 * ملف الإعدادات والوظائف المساعدة للعمل على Hostinger بـ PHP
 */

// بدء الجلسة للأدمن إذا لم تكن مفعلة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ضبط ترويسات CORS لتمكين طلبات API من جميع الواجهات
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// مسار ملف قاعدة البيانات
define('DB_FILE', __DIR__ . '/data/db.json');

// ضبط التوقيت الافتراضي (المغرب)
date_default_timezone_set('Africa/Casablanca');

/**
 * البيانات الافتراضية الكاملة للمتجر (كتالوج العسل المغربي، الباقات، المدن، والإعدادات)
 */
function get_default_data() {
    return [
        'products' => [
            [
                'id' => 'prod-thyme',
                'name' => 'عسل الزعتر الجبلي الأصيل',
                'slug' => 'moroccan-thyme-honey',
                'shortDescription' => 'عسل نادر بنكهة عطرية قوية من أعالي جبال الأطلس المتوسط.',
                'detailedDescription' => 'يُجنى عسل الزعتر من شجيرات الزعتر البري المنتشرة على سفوح جبال الأطلس المتوسط على ارتفاع يتجاوز 1400 متر. يتميز بلونه العنبري الداكن، وقوامه الكثيف، ونكهته العشبية الحارة الفريدة المحبوبة في التقاليد المغربية الأصيلة.',
                'image' => 'assets/images/honey_thyme.jpg',
                'sourceRegion' => 'جبال الأطلس المتوسط (إيموزار مرموشة / خنيفرة)',
                'harvestSeason' => 'أواخر فصل الصيف (يوليوز - غشت)',
                'textureAndTaste' => 'قوام ثقيل ومخملي مع لمسة حارة عطرية ونهاية خشبية لطيفة',
                'color' => 'عنبري داكن يميل إلى البني النحاسي',
                'storageInstructions' => 'يُحفظ في مكان جاف ومظلم بعيداً عن الرطوبة وحرارة الشمس المباشرة بدرجة حرارة الغرفة (18-24°C).',
                'weights' => [
                    ['weight' => 250, 'unit' => 'g', 'price' => 140, 'inStock' => true, 'stockCount' => 45],
                    ['weight' => 500, 'unit' => 'g', 'price' => 260, 'inStock' => true, 'stockCount' => 30],
                    ['weight' => 1000, 'unit' => 'g', 'price' => 490, 'inStock' => true, 'stockCount' => 15]
                ],
                'featured' => true,
                'isAvailable' => true,
                'faq' => [
                    [
                        'question' => 'لماذا يُعتبر عسل الزعتر من أثمن الأنواع في المغرب؟',
                        'answer' => 'نظراً لقصر موسم إزهار الزعتر الجبلي في المرتفعات واعتماده على التساقطات المطرية الربيعية، بالإضافة إلى صعوبة جغرافية التضاريس.'
                    ],
                    [
                        'question' => 'هل يمكن أن يتبلور عسل الزعتر مع مرور الوقت؟',
                        'answer' => 'نعم، التبلور ظاهرة طبيعية تدل على نقاء العسل وعدم تعرضه للتسخين الصناعي المبستر. يمكن استعادة سيولته بوضعه في حمام ماء دافئ خفيف.'
                    ]
                ]
            ],
            [
                'id' => 'prod-daghmous',
                'name' => 'عسل الدغموس المغربي الحر',
                'slug' => 'moroccan-daghmous-honey',
                'shortDescription' => 'نكهة دافئة لاذعة فريدة تشتهر بها نبتة الدغموس الشوكية في الجنوب المغربي.',
                'detailedDescription' => 'يُستخلص هذا العسل الاستثنائي من أزهار نبات الدغموس (Euphorbia resinifera)، وهو نبات شوكي مستوطن في المغرب خاصة بمناطق سوس ماسة وحوض درعة. يتميز بإحساس حرارة ولذعة دافئة لطيفة في الحلق فور تذوقه.',
                'image' => 'assets/images/honey_daghmous.jpg',
                'sourceRegion' => 'سوس ماسة وسفوح الأطلس الصغير (تيزنيت / تارودانت)',
                'harvestSeason' => 'أوائل الصيف (ماي - يونيو)',
                'textureAndTaste' => 'قوام ناعم غني مع حرارة مميزة ومذاق لاذع يترك إحساساً دافئاً',
                'color' => 'ذهبي محمر إلى عنبري نحاسي براق',
                'storageInstructions' => 'يُحفظ بإحكام في مرطبانه الزجاجي الأصلي في درجة حرارة معتدلة بعيداً عن أي روائح قوية.',
                'weights' => [
                    ['weight' => 250, 'unit' => 'g', 'price' => 150, 'inStock' => true, 'stockCount' => 50],
                    ['weight' => 500, 'unit' => 'g', 'price' => 280, 'inStock' => true, 'stockCount' => 40],
                    ['weight' => 1000, 'unit' => 'g', 'price' => 530, 'inStock' => true, 'stockCount' => 20]
                ],
                'featured' => true,
                'isAvailable' => true,
                'faq' => [
                    [
                        'question' => 'ما هو سر الحرارة المميزة في عسل الدغموس؟',
                        'answer' => 'ترجع إلى المركبات الطبيعية الفريدة لرحيق نبتة الدغموس الشوكية المستوطنة في جبال الجنوب المغربي.'
                    ]
                ]
            ],
            [
                'id' => 'prod-sidr',
                'name' => 'عسل السدر الملكي الفاخر',
                'slug' => 'royal-moroccan-sidr-honey',
                'shortDescription' => 'قوام كثيف ومذاق كراميلي ساحر من أشجار السدر العتيقة.',
                'detailedDescription' => 'عسل السدر الملكي المقطوف من أشجار النبق البرية الموغلة في القدم على التلال المغربية المشمسة. يحظى بشهرة واسعة لمذاقه الهادئ الغني ونقائه الفائق ونكهته العسلية الرائعة التي ترضي أرقى الأذواق.',
                'image' => 'assets/images/honey_sidr.jpg',
                'sourceRegion' => 'سهول وتلال الشاوية وتادلة أزيلال',
                'harvestSeason' => 'فصل الخريف (شتنبر - أكتوبر)',
                'textureAndTaste' => 'كثافة عالية ولزوجة مخملية مع نكهة حلوة متوازنة بنفحات كراميلية',
                'color' => 'عسلي ذهبي كهرماني عميق',
                'storageInstructions' => 'يُحفظ في حرارة الغرفة في مكان جاف ومعتم. تجنب وضع ملاعق معدنية رطبة داخل المرطبان.',
                'weights' => [
                    ['weight' => 250, 'unit' => 'g', 'price' => 160, 'inStock' => true, 'stockCount' => 35],
                    ['weight' => 500, 'unit' => 'g', 'price' => 300, 'inStock' => true, 'stockCount' => 25],
                    ['weight' => 1000, 'unit' => 'g', 'price' => 570, 'inStock' => true, 'stockCount' => 12]
                ],
                'featured' => true,
                'isAvailable' => true,
                'faq' => [
                    [
                        'question' => 'كيف يمكن تمييز عسل السدر الطبيعي؟',
                        'answer' => 'يتميز برائحته العطرية الهادئة الشبيهة بأزهار السدر، وقوامه الثقيل الذي لا ينقطع بسهولة عند صبه.'
                    ]
                ]
            ],
            [
                'id' => 'prod-eucalyptus',
                'name' => 'عسل الأوكالبتوس الطبيعي',
                'slug' => 'moroccan-eucalyptus-honey',
                'shortDescription' => 'رائحة منعشة ومذاق معتدل مستخلص من غابات الكاليبتوس المغربية.',
                'detailedDescription' => 'يُجمع من غابات أشجار الأوكالبتوس الشاسعة في المغرب مثل غابة المعمورة ومنطقة الغرب. عسل مفضل للعائلات بفضل مذاقه المنعش ونفحاته الخفيفة التي تجمع بين الحلاوة والانتعاش الخفيف.',
                'image' => 'assets/images/honey_eucalyptus.jpg',
                'sourceRegion' => 'غابات المعمورة وحوض الغرب',
                'harvestSeason' => 'فصل الربيع وبداية الصيف (أبريل - يونيو)',
                'textureAndTaste' => 'قوام متوسط السيولة وسهل الدهن، بنكهة خشبية منعشة ومحببة',
                'color' => 'عنبري فاتح يميل إلى البني الفاتح المائل للرمادي الخفيف',
                'storageInstructions' => 'يُحفظ في وعاء محكم الإغلاق في مكان بارد وجاف.',
                'weights' => [
                    ['weight' => 250, 'unit' => 'g', 'price' => 95, 'inStock' => true, 'stockCount' => 60],
                    ['weight' => 500, 'unit' => 'g', 'price' => 180, 'inStock' => true, 'stockCount' => 45],
                    ['weight' => 1000, 'unit' => 'g', 'price' => 330, 'inStock' => true, 'stockCount' => 25]
                ],
                'featured' => false,
                'isAvailable' => true
            ],
            [
                'id' => 'prod-wildflower',
                'name' => 'عسل زهور الأطلس البرية',
                'slug' => 'atlas-mountain-wildflower-honey',
                'shortDescription' => 'توليفة زهرية متعددة المصادر من مروج ومراعي جبال الأطلس الخضراء.',
                'detailedDescription' => 'عسل متعدد الأزهار يجمعه النحل من باقة غنية من أزهار الجبل العفوية مثل الخزامى، إكليل الجبل، البابونج البري، وأزهار المروج الجبلية. يتميز بنكهة متوازنة ومحبوبة لدى الكبار والصغار.',
                'image' => 'assets/images/honey_wildflower.jpg',
                'sourceRegion' => 'جبال الأطلس الكبير وورزازات',
                'harvestSeason' => 'موسم الربيع (مارس - ماي)',
                'textureAndTaste' => 'ناعم وسلس بنكهة فواحة متوازنة غير حادة',
                'color' => 'ذهبي مشرق صافٍ',
                'storageInstructions' => 'يُحفظ بعيداً عن أشعة الشمس المباشرة في درجة حرارة الغرفة.',
                'weights' => [
                    ['weight' => 250, 'unit' => 'g', 'price' => 90, 'inStock' => true, 'stockCount' => 70],
                    ['weight' => 500, 'unit' => 'g', 'price' => 165, 'inStock' => true, 'stockCount' => 50],
                    ['weight' => 1000, 'unit' => 'g', 'price' => 290, 'inStock' => true, 'stockCount' => 30]
                ],
                'featured' => false,
                'isAvailable' => true
            ],
            [
                'id' => 'prod-carob',
                'name' => 'عسل الخروب المغربي التقليدي',
                'slug' => 'moroccan-carob-honey',
                'shortDescription' => 'عسل داكن ذو خصائص هضمية ممتازة ومذاق ترابي محلى راقٍ.',
                'detailedDescription' => 'يُقطف من أزهار شجرة الخروب المعمرة في سفوح جبال الريف والأطلس المتوسط. يتمتع بقيمة غذائية عالية ونكهة خاصة قريبة من حبوب الكاكاو الخفيفة والكراميل الطبيعي.',
                'image' => 'assets/images/honey_carob.jpg',
                'sourceRegion' => 'إقليم تاونات وسفوح مقدمة الريف',
                'harvestSeason' => 'فصل الخريف (أكتوبر - نونبر)',
                'textureAndTaste' => 'قوام سميك مخملي، مع حلاوة خفيفة غير طاغية',
                'color' => 'بني داكن غامق يشبه دبس التمر والشوكولاتة',
                'storageInstructions' => 'يُحفظ في مكان مظلم وبارد.',
                'weights' => [
                    ['weight' => 250, 'unit' => 'g', 'price' => 110, 'inStock' => true, 'stockCount' => 40],
                    ['weight' => 500, 'unit' => 'g', 'price' => 210, 'inStock' => true, 'stockCount' => 30],
                    ['weight' => 1000, 'unit' => 'g', 'price' => 390, 'inStock' => true, 'stockCount' => 15]
                ],
                'featured' => false,
                'isAvailable' => true
            ]
        ],
        'bundles' => [
            [
                'id' => 'bundle-trio-discovery',
                'name' => 'باقة التذوق والاستكشاف الثلاثية',
                'slug' => 'atlas-trio-discovery-pack',
                'description' => 'المجموعة المثالية للتعرف على أروع نكهات العسل المغربي. تحتوي على 3 مرطبانات فاخرة (250غ لكل مرطبان): عسل الزعتر الجبلي، عسل الدغموس الحر، وعسل السدر الملكي، مرفقة بملعقة عسل خشبية مصنوعة يدوياً.',
                'image' => 'assets/images/honey_bundle.jpg',
                'jarsCount' => 3,
                'totalWeightGrams' => 750,
                'price' => 390,
                'originalPrice' => 450,
                'inStock' => true,
                'freeShipping' => true,
                'contents' => [
                    'مرطبان عسل الزعتر الجبلي (250 غرام)',
                    'مرطبان عسل الدغموس الصحراوي (250 غرام)',
                    'مرطبان عسل السدر الملكي (250 غرام)',
                    'ملعقة خشبية يدوية لغرف العسل (هدية)',
                    'صندوق هدايا كرتوني صديق للبيئة'
                ]
            ],
            [
                'id' => 'bundle-immunity-duo',
                'name' => 'باقة مناعة الأطلس الثنائية',
                'slug' => 'atlas-immunity-duo-pack',
                'description' => 'مزيج القوة والأصالة المغربية. مرطبانان بوزن نصف كيلوغرام لكل منهما من أقوى نوعين: عسل الدغموس الحار وعسل الزعتر الجبلي المركز.',
                'image' => 'assets/images/honey_daghmous.jpg',
                'jarsCount' => 2,
                'totalWeightGrams' => 1000,
                'price' => 490,
                'originalPrice' => 540,
                'inStock' => true,
                'freeShipping' => true,
                'contents' => [
                    'مرطبان عسل الدغموس المغربي الحر (500 غرام)',
                    'مرطبان عسل الزعتر الجبلي الأصيل (500 غرام)',
                    'تغليف آمن ومحكم مضاد للصدمات'
                ]
            ],
            [
                'id' => 'bundle-family-pack',
                'name' => 'باقة العائلة اليومية (2 كيلوغرام)',
                'slug' => 'atlas-family-everyday-pack',
                'description' => 'العسل الطبيعي لكل أفراد العائلة لوجبات الإفطار والتحلية الطبيعية اليومية. مرطبانان كبيران بحجم 1 كيلوغرام لكل منهما: عسل زهور الأطلس وعسل الأوكالبتوس.',
                'image' => 'assets/images/honey_wildflower.jpg',
                'jarsCount' => 2,
                'totalWeightGrams' => 2000,
                'price' => 560,
                'originalPrice' => 620,
                'inStock' => true,
                'freeShipping' => true,
                'contents' => [
                    'مرطبان عسل زهور الأطلس البرية (1000 غرام)',
                    'مرطبان عسل الأوكالبتوس الطبيعي (1000 غرام)',
                    'توفير ممتاز للاستهلاك العائلي اليومي'
                ]
            ],
            [
                'id' => 'bundle-royal-gift',
                'name' => 'باقة الهدية الملكية الفاخرة',
                'slug' => 'atlas-royal-gift-collection',
                'description' => 'أفخم هدية تقدمها لمن تحب. مرطبان سدر ملكي نصف كيلو + مرطبان دغموس نصف كيلو في صندوق خشبي تقليدي مزخرف مع ملعقة زيتون أصلية وبطاقة إهداء مخصصة.',
                'image' => 'assets/images/honey_sidr.jpg',
                'jarsCount' => 2,
                'totalWeightGrams' => 1000,
                'price' => 590,
                'originalPrice' => 680,
                'inStock' => true,
                'freeShipping' => true,
                'contents' => [
                    'مرطبان عسل السدر الملكي الفاخر (500 غرام)',
                    'مرطبان عسل الدغموس المغربي الحر (500 غرام)',
                    'ملعقة خشب زيتون طبيعي مغربي',
                    'صندوق إهداء راقٍ بلمسات ذهبية',
                    'بطاقة تهنئة وإهداء خاصة'
                ]
            ]
        ],
        'cities' => [
            ['id' => 'casablanca', 'name' => 'الدار البيضاء (Casablanca)', 'rate' => 20, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'rabat', 'name' => 'الرباط (Rabat)', 'rate' => 25, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'sale', 'name' => 'سلا (Salé)', 'rate' => 25, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'temara', 'name' => 'تمارة (Témara)', 'rate' => 25, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'marrakech', 'name' => 'مراكش (Marrakech)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'fes', 'name' => 'فاس (Fès)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'tangier', 'name' => 'طنجة (Tanger)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'agadir', 'name' => 'أكادير (Agadir)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'meknes', 'name' => 'مكناس (Meknès)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'kenitra', 'name' => 'القنيطرة (Kénitra)', 'rate' => 25, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'tetouan', 'name' => 'تطوان (Tétouan)', 'rate' => 30, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'oujda', 'name' => 'وجدة (Oujda)', 'rate' => 35, 'estimatedDelivery' => '48-72 ساعة'],
            ['id' => 'safi', 'name' => 'آسفي (Safi)', 'rate' => 30, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'el-jadida', 'name' => 'الجديدة (El Jadida)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'nador', 'name' => 'الناظور (Nador)', 'rate' => 35, 'estimatedDelivery' => '48-72 ساعة'],
            ['id' => 'beni-mellal', 'name' => 'بني ملال (Béni Mellal)', 'rate' => 30, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'khemisset', 'name' => 'الخميسات (Khémisset)', 'rate' => 30, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'taza', 'name' => 'تازة (Taza)', 'rate' => 35, 'estimatedDelivery' => '48-72 ساعة'],
            ['id' => 'khouribga', 'name' => 'خريبكة (Khouribga)', 'rate' => 30, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'mohammedia', 'name' => 'المحمدية (Mohammedia)', 'rate' => 20, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'berrechid', 'name' => 'برشيد (Berrechid)', 'rate' => 25, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'settat', 'name' => 'سطات (Settat)', 'rate' => 30, 'estimatedDelivery' => '24-48 ساعة'],
            ['id' => 'larache', 'name' => 'العرائش (Larache)', 'rate' => 30, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'taroudant', 'name' => 'تارودانت (Taroudant)', 'rate' => 35, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'tiznit', 'name' => 'تيزنيت (Tiznit)', 'rate' => 35, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'essaouira', 'name' => 'الصويرة (Essaouira)', 'rate' => 35, 'estimatedDelivery' => '48 ساعة'],
            ['id' => 'errachidia', 'name' => 'الرشيدية (Errachidia)', 'rate' => 40, 'estimatedDelivery' => '48-72 ساعة'],
            ['id' => 'ouarzazate', 'name' => 'ورزازات (Ouarzazate)', 'rate' => 40, 'estimatedDelivery' => '48-72 ساعة'],
            ['id' => 'guelmim', 'name' => 'كلميم (Guelmim)', 'rate' => 40, 'estimatedDelivery' => '48-72 ساعة'],
            ['id' => 'laayoune', 'name' => 'العيون (Laâyoune)', 'rate' => 45, 'estimatedDelivery' => '72 ساعة'],
            ['id' => 'dakhla', 'name' => 'الداخلة (Dakhla)', 'rate' => 50, 'estimatedDelivery' => '72-96 ساعة']
        ],
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

/**
 * جلب بيانات المتجر من ملف JSON مع توفير النسخة الاحتياطية تلقائياً
 */
function get_db_data() {
    $default = get_default_data();

    if (!file_exists(DB_FILE)) {
        save_db_data($default);
        return $default;
    }

    $content = @file_get_contents(DB_FILE);
    if (!$content) {
        return $default;
    }

    $data = json_decode($content, true);

    if (!is_array($data) || empty($data['products'])) {
        save_db_data($default);
        return $default;
    }

    // التأكد من وجود كافة المفاتيح
    if (empty($data['bundles'])) $data['bundles'] = $default['bundles'];
    if (empty($data['cities'])) $data['cities'] = $default['cities'];
    if (empty($data['settings'])) $data['settings'] = $default['settings'];
    if (!isset($data['orders'])) $data['orders'] = [];

    return $data;
}

/**
 * حفظ البيانات في ملف JSON بأمان
 */
function save_db_data($data) {
    $dir = dirname(DB_FILE);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return @file_put_contents(DB_FILE, $json, LOCK_EX) !== false;
}

/**
 * إرسال رد JSON وترميز UTF-8 مع ترويسات CORS
 */
function send_json($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
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
