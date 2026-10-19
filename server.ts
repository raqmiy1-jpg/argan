import express, { Request, Response } from 'express';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { createServer as createViteServer } from 'vite';
import { Product, HoneyBundle, CityShipping, Order, StoreSettings } from './src/types.ts';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

// Persistent Database File Path
const DB_DIR = path.join(__dirname, 'data');
const DB_FILE = path.join(DB_DIR, 'db.json');

interface DatabaseSchema {
  products: Product[];
  bundles: HoneyBundle[];
  cities: CityShipping[];
  orders: Order[];
  settings: StoreSettings;
}

// Initial Data with Authentic Moroccan Honey Details
const INITIAL_PRODUCTS: Product[] = [
  {
    id: 'prod-thyme',
    name: 'عسل الزعتر الجبلي الأصيل',
    slug: 'moroccan-thyme-honey',
    shortDescription: 'عسل نادر بنكهة عطرية قوية من أعالي جبال الأطلس المتوسط.',
    detailedDescription: 'يُجنى عسل الزعتر من شجيرات الزعتر البري المنتشرة على سفوح جبال الأطلس المتوسط على ارتفاع يتجاوز 1400 متر. يتميز بلونه العنبري الداكن، وقوامه الكثيف، ونكهته العشبية الحارة الفريدة المحبوبة في التقاليد المغربية الأصيلة.',
    image: '/src/assets/images/honey_jar_thyme_1792267326022.jpg',
    sourceRegion: 'جبال الأطلس المتوسط (إيموزار مرموشة / خنيفرة)',
    harvestSeason: 'أواخر فصل الصيف (يوليوز - غشت)',
    textureAndTaste: 'قوام ثقيل ومخملي مع لمسة حارة عطرية ونهاية خشبية لطيفة',
    color: 'عنبري داكن يميل إلى البني النحاسي',
    storageInstructions: 'يُحفظ في مكان جاف ومظلم بعيداً عن الرطوبة وحرارة الشمس المباشرة بدرجة حرارة الغرفة (18-24°C).',
    weights: [
      { weight: 250, unit: 'g', price: 140, inStock: true, stockCount: 45 },
      { weight: 500, unit: 'g', price: 260, inStock: true, stockCount: 30 },
      { weight: 1000, unit: 'g', price: 490, inStock: true, stockCount: 15 }
    ],
    featured: true,
    isAvailable: true,
    faq: [
      {
        question: 'لماذا يُعتبر عسل الزعتر من أثمن الأنواع في المغرب؟',
        answer: 'نظراً لقصر موسم إزهار الزعتر الجبلي في المرتفعات واعتماده على التساقطات المطرية الربيعية، بالإضافة إلى صعوبة جغرافية التضاريس.'
      },
      {
        question: 'هل يمكن أن يتبلور عسل الزعتر مع مرور الوقت؟',
        answer: 'نعم، التبلور ظاهرة طبيعية تدل على نقاء العسل وعدم تعرضه للتسخين الصناعي المبستر. يمكن استعادة سيولته بوضعه في حمام ماء دافئ خفيف.'
      }
    ]
  },
  {
    id: 'prod-daghmous',
    name: 'عسل الدغموس المغربي الحر',
    slug: 'moroccan-daghmous-honey',
    shortDescription: 'نكهة دافئة لاذعة فريدة تشتهر بها نبتة الدغموس الشوكية في الجنوب المغربي.',
    detailedDescription: 'يُستخلص هذا العسل الاستثنائي من أزهار نبات الدغموس (Euphorbia resinifera)، وهو نبات شوكي مستوطن في المغرب خاصة بمناطق سوس ماسة وحوض درعة. يتميز بإحساس حرارة ولذعة دافئة لطيفة في الحلق فور تذوقه.',
    image: '/src/assets/images/honey_jar_daghmous_1792267326881.jpg',
    sourceRegion: 'سوس ماسة وسفوح الأطلس الصغير (تيزنيت / تارودانت)',
    harvestSeason: 'أوائل الصيف (ماي - يونيو)',
    textureAndTaste: 'قوام ناعم غني مع حرارة مميزة ومذاق لاذع يترك إحساساً دافئاً',
    color: 'ذهبي محمر إلى عنبري نحاسي براق',
    storageInstructions: 'يُحفظ بإحكام في مرطبانه الزجاجي الأصلي في درجة حرارة معتدلة بعيداً عن أي روائح قوية.',
    weights: [
      { weight: 250, unit: 'g', price: 150, inStock: true, stockCount: 50 },
      { weight: 500, unit: 'g', price: 280, inStock: true, stockCount: 40 },
      { weight: 1000, unit: 'g', price: 530, inStock: true, stockCount: 20 }
    ],
    featured: true,
    isAvailable: true,
    faq: [
      {
        question: 'ما هو سر الحرارة المميزة في عسل الدغموس؟',
        answer: 'ترجع إلى المركبات الطبيعية الفريدة لرحيق نبتة الدغموس الشوكية المستوطنة في جبال الجنوب المغربي.'
      }
    ]
  },
  {
    id: 'prod-sidr',
    name: 'عسل السدر الملكي الفاخر',
    slug: 'royal-moroccan-sidr-honey',
    shortDescription: 'قوام كثيف ومذاق كراميلي ساحر من أشجار السدر العتيقة.',
    detailedDescription: 'عسل السدر الملكي المقطوف من أشجار النبق البرية الموغلة في القدم على التلال المغربية المشمسة. يحظى بشهرة واسعة لمذاقه الهادئ الغني ونقائه الفائق ونكهته العسلية الرائعة التي ترضي أرقى الأذواق.',
    image: '/src/assets/images/honey_jar_sidr_1792267327022.jpg',
    sourceRegion: 'سهول وتلال الشاوية وتادلة أزيلال',
    harvestSeason: 'فصل الخريف (شتنبر - أكتوبر)',
    textureAndTaste: 'كثافة عالية ولزوجة مخملية مع نكهة حلوة متوازنة بنفحات كراميلية',
    color: 'عسلي ذهبي كهرماني عميق',
    storageInstructions: 'يُحفظ في حرارة الغرفة في مكان جاف ومعتم. تجنب وضع ملاعق معدنية رطبة داخل المرطبان.',
    weights: [
      { weight: 250, unit: 'g', price: 160, inStock: true, stockCount: 35 },
      { weight: 500, unit: 'g', price: 300, inStock: true, stockCount: 25 },
      { weight: 1000, unit: 'g', price: 570, inStock: true, stockCount: 12 }
    ],
    featured: true,
    isAvailable: true,
    faq: [
      {
        question: 'كيف يمكن تمييز عسل السدر الطبيعي؟',
        answer: 'يتميز برائحته العطرية الهادئة الشبيهة بأزهار السدر، وقوامه الثقيل الذي لا ينقطع بسهولة عند صبه.'
      }
    ]
  },
  {
    id: 'prod-eucalyptus',
    name: 'عسل الأوكالبتوس الطبيعي',
    slug: 'moroccan-eucalyptus-honey',
    shortDescription: 'رائحة منعشة ومذاق معتدل مستخلص من غابات الكاليبتوس المغربية.',
    detailedDescription: 'يُجمع من غابات أشجار الأوكالبتوس الشاسعة في المغرب مثل غابة المعمورة ومنطقة الغرب. عسل مفضل للعائلات بفضل مذاقه المنعش ونفحاته الخفيفة التي تجمع بين الحلاوة والانتعاش الخفيف.',
    image: '/src/assets/images/honey_jar_daghmous_1792267326881.jpg',
    sourceRegion: 'غابات المعمورة وحوض الغرب',
    harvestSeason: 'فصل الربيع وبداية الصيف (أبريل - يونيو)',
    textureAndTaste: 'قوام متوسط السيولة وسهل الدهن، بنكهة خشبية منعشة ومحببة',
    color: 'عنبري فاتح يميل إلى البني الفاتح المائل للرمادي الخفيف',
    storageInstructions: 'يُحفظ في وعاء محكم الإغلاق في مكان بارد وجاف.',
    weights: [
      { weight: 250, unit: 'g', price: 95, inStock: true, stockCount: 60 },
      { weight: 500, unit: 'g', price: 180, inStock: true, stockCount: 45 },
      { weight: 1000, unit: 'g', price: 330, inStock: true, stockCount: 25 }
    ],
    featured: false,
    isAvailable: true
  },
  {
    id: 'prod-wildflower',
    name: 'عسل زهور الأطلس البرية',
    slug: 'atlas-mountain-wildflower-honey',
    shortDescription: 'توليفة زهرية متعددة المصادر من مروج ومراعي جبال الأطلس الخضراء.',
    detailedDescription: 'عسل متعدد الأزهار يجمعه النحل من باقة غنية من أزهار الجبل العفوية مثل الخزامى، إكليل الجبل، البابونج البري، وأزهار المروج الجبلية. يتميز بنكهة متوازنة ومحبوبة لدى الكبار والصغار.',
    image: '/src/assets/images/hero_atlas_honey_1792267326345.jpg',
    sourceRegion: 'جبال الأطلس الكبير وورزازات',
    harvestSeason: 'موسم الربيع (مارس - ماي)',
    textureAndTaste: 'ناعم وسلس بنكهة فواحة متوازنة غير حادة',
    color: 'ذهبي مشرق صافٍ',
    storageInstructions: 'يُحفظ بعيداً عن أشعة الشمس المباشرة في درجة حرارة الغرفة.',
    weights: [
      { weight: 250, unit: 'g', price: 90, inStock: true, stockCount: 70 },
      { weight: 500, unit: 'g', price: 165, inStock: true, stockCount: 50 },
      { weight: 1000, unit: 'g', price: 290, inStock: true, stockCount: 30 }
    ],
    featured: false,
    isAvailable: true
  },
  {
    id: 'prod-carob',
    name: 'عسل الخروب المغربي التقليدي',
    slug: 'moroccan-carob-honey',
    shortDescription: 'عسل داكن ذو خصائص هضمية ممتازة ومذاق ترابي محلى راقٍ.',
    detailedDescription: 'يُقطف من أزهار شجرة الخروب المعمرة في سفوح جبال الريف والأطلس المتوسط. يتمتع بقيمة غذائية عالية ونكهة خاصة قريبة من حبوب الكاكاو الخفيفة والكراميل الطبيعي.',
    image: '/src/assets/images/honey_jar_thyme_1792267326022.jpg',
    sourceRegion: 'إقليم تاونات وسفوح مقدمة الريف',
    harvestSeason: 'فصل الخريف (أكتوبر - نونبر)',
    textureAndTaste: 'قوام سميك مخملي، مع حلاوة خفيفة غير طاغية',
    color: 'بني داكن غامق يشبه دبس التمر والشوكولاتة',
    storageInstructions: 'يُحفظ في مكان مظلم وبارد.',
    weights: [
      { weight: 250, unit: 'g', price: 110, inStock: true, stockCount: 40 },
      { weight: 500, unit: 'g', price: 210, inStock: true, stockCount: 30 },
      { weight: 1000, unit: 'g', price: 390, inStock: true, stockCount: 15 }
    ],
    featured: false,
    isAvailable: true
  }
];

const INITIAL_BUNDLES: HoneyBundle[] = [
  {
    id: 'bundle-trio-discovery',
    name: 'باقة التذوق والاستكشاف الثلاثية',
    slug: 'atlas-trio-discovery-pack',
    description: 'المجموعة المثالية للتعرف على أروع نكهات العسل المغربي. تحتوي على 3 مرطبانات فاخرة (250غ لكل مرطبان): عسل الزعتر الجبلي، عسل الدغموس الحر، وعسل السدر الملكي، مرفقة بملعقة عسل خشبية مصنوعة يدوياً.',
    image: '/src/assets/images/honey_discovery_bundle_1792267327536.jpg',
    jarsCount: 3,
    totalWeightGrams: 750,
    price: 390,
    originalPrice: 450,
    inStock: true,
    freeShipping: true,
    contents: [
      'مرطبان عسل الزعتر الجبلي (250 غرام)',
      'مرطبان عسل الدغموس الصحراوي (250 غرام)',
      'مرطبان عسل السدر الملكي (250 غرام)',
      'ملعقة خشبية يدوية لغرف العسل (هدية)',
      'صندوق هدايا كرتوني صديق للبيئة'
    ]
  },
  {
    id: 'bundle-immunity-duo',
    name: 'باقة مناعة الأطلس الثنائية',
    slug: 'atlas-immunity-duo-pack',
    description: 'مزيج القوة والأصالة المغربية. مرطبانان بوزن نصف كيلوغرام لكل منهما من أقوى نوعين: عسل الدغموس الحار وعسل الزعتر الجبلي المركز.',
    image: '/src/assets/images/honey_jar_daghmous_1792267326881.jpg',
    jarsCount: 2,
    totalWeightGrams: 1000,
    price: 490,
    originalPrice: 540,
    inStock: true,
    freeShipping: true,
    contents: [
      'مرطبان عسل الدغموس المغربي الحر (500 غرام)',
      'مرطبان عسل الزعتر الجبلي الأصيل (500 غرام)',
      'تغليف آمن ومحكم مضاد للصدمات'
    ]
  },
  {
    id: 'bundle-family-pack',
    name: 'باقة العائلة اليومية (2 كيلوغرام)',
    slug: 'atlas-family-everyday-pack',
    description: 'العسل الطبيعي لكل أفراد العائلة لوجبات الإفطار والتحلية الطبيعية اليومية. مرطبانان كبيران بحجم 1 كيلوغرام لكل منهما: عسل زهور الأطلس وعسل الأوكالبتوس.',
    image: '/src/assets/images/hero_atlas_honey_1792267326345.jpg',
    jarsCount: 2,
    totalWeightGrams: 2000,
    price: 560,
    originalPrice: 620,
    inStock: true,
    freeShipping: true,
    contents: [
      'مرطبان عسل زهور الأطلس البرية (1000 غرام)',
      'مرطبان عسل الأوكالبتوس الطبيعي (1000 غرام)',
      'توفير ممتاز للاستهلاك العائلي اليومي'
    ]
  },
  {
    id: 'bundle-royal-gift',
    name: 'باقة الهدية الملكية الفاخرة',
    slug: 'atlas-royal-gift-collection',
    description: 'أفخم هدية تقدمها لمن تحب. مرطبان سدر ملكي نصف كيلو + مرطبان دغموس نصف كيلو في صندوق خشبي تقليدي مزخرف مع ملعقة زيتون أصلية وبطاقة إهداء مخصصة.',
    image: '/src/assets/images/honey_jar_sidr_1792267327022.jpg',
    jarsCount: 2,
    totalWeightGrams: 1000,
    price: 590,
    originalPrice: 680,
    inStock: true,
    freeShipping: true,
    contents: [
      'مرطبان عسل السدر الملكي الفاخر (500 غرام)',
      'مرطبان عسل الدغموس المغربي الحر (500 غرام)',
      'ملعقة خشب زيتون طبيعي مغربي',
      'صندوق إهداء راقٍ بلمسات ذهبية',
      'بطاقة تهنئة وإهداء خاصة'
    ]
  }
];

const INITIAL_CITIES: CityShipping[] = [
  { id: 'casablanca', name: 'الدار البيضاء (Casablanca)', rate: 20, estimatedDelivery: '24-48 ساعة' },
  { id: 'rabat', name: 'الرباط (Rabat)', rate: 25, estimatedDelivery: '24-48 ساعة' },
  { id: 'sale', name: 'سلا (Salé)', rate: 25, estimatedDelivery: '24-48 ساعة' },
  { id: 'temara', name: 'تمارة (Témara)', rate: 25, estimatedDelivery: '24-48 ساعة' },
  { id: 'marrakech', name: 'مراكش (Marrakech)', rate: 30, estimatedDelivery: '24-48 ساعة' },
  { id: 'fes', name: 'فاس (Fès)', rate: 30, estimatedDelivery: '24-48 ساعة' },
  { id: 'tangier', name: 'طنجة (Tanger)', rate: 30, estimatedDelivery: '24-48 ساعة' },
  { id: 'agadir', name: 'أكادير (Agadir)', rate: 30, estimatedDelivery: '24-48 ساعة' },
  { id: 'meknes', name: 'مكناس (Meknès)', rate: 30, estimatedDelivery: '24-48 ساعة' },
  { id: 'kenitra', name: 'القنيطرة (Kénitra)', rate: 25, estimatedDelivery: '24-48 ساعة' },
  { id: 'tetouan', name: 'تطوان (Tétouan)', rate: 30, estimatedDelivery: '48 ساعة' },
  { id: 'oujda', name: 'وجدة (Oujda)', rate: 35, estimatedDelivery: '48-72 ساعة' },
  { id: 'safi', name: 'آسفي (Safi)', rate: 30, estimatedDelivery: '48 ساعة' },
  { id: 'el-jadida', name: 'الجديدة (El Jadida)', rate: 30, estimatedDelivery: '24-48 ساعة' },
  { id: 'nador', name: 'الناظور (Nador)', rate: 35, estimatedDelivery: '48-72 ساعة' },
  { id: 'beni-mellal', name: 'بني ملال (Béni Mellal)', rate: 30, estimatedDelivery: '48 ساعة' },
  { id: 'khemisset', name: 'الخميسات (Khémisset)', rate: 30, estimatedDelivery: '48 ساعة' },
  { id: 'taza', name: 'تازة (Taza)', rate: 35, estimatedDelivery: '48-72 ساعة' },
  { id: 'khouribga', name: 'خريبكة (Khouribga)', rate: 30, estimatedDelivery: '48 ساعة' },
  { id: 'mohammedia', name: 'المحمدية (Mohammedia)', rate: 20, estimatedDelivery: '24-48 ساعة' },
  { id: 'ouarzazate', name: 'ورزازات (Ouarzazate)', rate: 35, estimatedDelivery: '48-72 ساعة' },
  { id: 'laayoune', name: 'العيون (Laâyoune)', rate: 40, estimatedDelivery: '3-4 أيام' },
  { id: 'dakhla', name: 'الداخلة (Dakhla)', rate: 45, estimatedDelivery: '3-5 أيام' },
  { id: 'other', name: 'مدينة أو جماعة مغربية أخرى', rate: 35, estimatedDelivery: '48-72 ساعة' }
];

const INITIAL_SETTINGS: StoreSettings = {
  storeName: 'مناحل الأطلس',
  brandTagline: 'من خيرات الطبيعة المغربية',
  phone: '+212 661 234 567',
  whatsapp: '212661234567',
  email: 'contact@manahil-atlas.ma',
  address: 'جبال الأطلس المتوسط، جهة فاس مكناس، المملكة المغربية',
  announcementText: 'عسل طبيعي من خيرات الطبيعة المغربية – الدفع عند الاستلام متاح والتوصيل لجميع المدن المغربية',
  showAnnouncement: true,
  freeShippingThreshold: 500,
  adminPassword: 'atlas'
};

const INITIAL_ORDERS: Order[] = [
  {
    id: 'ATLAS-7821',
    customerName: 'كريم التازي',
    phone: '0661984523',
    city: 'الدار البيضاء (Casablanca)',
    address: 'شارع غاندي، إقامة النخيل، الشقة 14، المعاريف',
    items: [
      {
        id: 'prod-thyme',
        type: 'product',
        name: 'عسل الزعتر الجبلي الأصيل',
        weightLabel: '500 غرام',
        quantity: 1,
        unitPrice: 260,
        totalPrice: 260,
        image: '/src/assets/images/honey_jar_thyme_1792267326022.jpg'
      },
      {
        id: 'prod-daghmous',
        type: 'product',
        name: 'عسل الدغموس المغربي الحر',
        weightLabel: '250 غرام',
        quantity: 1,
        unitPrice: 150,
        totalPrice: 150,
        image: '/src/assets/images/honey_jar_daghmous_1792267326881.jpg'
      }
    ],
    subtotal: 410,
    shippingFee: 20,
    total: 430,
    notes: 'يرجى الاتصال قبل الوصول بنصف ساعة',
    paymentMethod: 'COD',
    status: 'shipping',
    createdAt: new Date(Date.now() - 3600000 * 5).toISOString()
  },
  {
    id: 'ATLAS-7820',
    customerName: 'فاطمة الزهراء بنجلون',
    phone: '0672113344',
    city: 'الرباط (Rabat)',
    address: 'حي أكدال، زنقة واد بهت، عمارة 8',
    items: [
      {
        id: 'bundle-trio-discovery',
        type: 'bundle',
        name: 'باقة التذوق والاستكشاف الثلاثية',
        weightLabel: '750 غرام (3 مرطبانات)',
        quantity: 1,
        unitPrice: 390,
        totalPrice: 390,
        image: '/src/assets/images/honey_discovery_bundle_1792267327536.jpg'
      }
    ],
    subtotal: 390,
    shippingFee: 25,
    total: 415,
    notes: 'طلب كهدية لوالدتي',
    paymentMethod: 'COD',
    status: 'delivered',
    createdAt: new Date(Date.now() - 3600000 * 24).toISOString()
  }
];

// Initialize DB if not present
function getDb(): DatabaseSchema {
  try {
    if (!fs.existsSync(DB_DIR)) {
      fs.mkdirSync(DB_DIR, { recursive: true });
    }
    if (!fs.existsSync(DB_FILE)) {
      const initialDb: DatabaseSchema = {
        products: INITIAL_PRODUCTS,
        bundles: INITIAL_BUNDLES,
        cities: INITIAL_CITIES,
        orders: INITIAL_ORDERS,
        settings: INITIAL_SETTINGS
      };
      fs.writeFileSync(DB_FILE, JSON.stringify(initialDb, null, 2), 'utf-8');
      return initialDb;
    }
    const data = fs.readFileSync(DB_FILE, 'utf-8');
    return JSON.parse(data) as DatabaseSchema;
  } catch (error) {
    console.error('Error reading DB, using defaults:', error);
    return {
      products: INITIAL_PRODUCTS,
      bundles: INITIAL_BUNDLES,
      cities: INITIAL_CITIES,
      orders: INITIAL_ORDERS,
      settings: INITIAL_SETTINGS
    };
  }
}

function saveDb(data: DatabaseSchema) {
  try {
    if (!fs.existsSync(DB_DIR)) {
      fs.mkdirSync(DB_DIR, { recursive: true });
    }
    fs.writeFileSync(DB_FILE, JSON.stringify(data, null, 2), 'utf-8');
  } catch (error) {
    console.error('Error saving DB:', error);
  }
}

// -------------------------------------------------------------
// API Endpoints
// -------------------------------------------------------------

// Products API
app.get('/api/products', (_req: Request, res: Response) => {
  const db = getDb();
  res.json(db.products);
});

app.get('/api/products/:id', (req: Request, res: Response) => {
  const db = getDb();
  const prod = db.products.find(p => p.id === req.params.id || p.slug === req.params.id);
  if (!prod) {
    return res.status(404).json({ error: 'المنتج غير موجود' });
  }
  res.json(prod);
});

app.post('/api/products', (req: Request, res: Response) => {
  const db = getDb();
  const newProduct: Product = {
    ...req.body,
    id: req.body.id || `prod-${Date.now()}`
  };
  db.products.push(newProduct);
  saveDb(db);
  res.status(201).json(newProduct);
});

app.put('/api/products/:id', (req: Request, res: Response) => {
  const db = getDb();
  const index = db.products.findIndex(p => p.id === req.params.id);
  if (index === -1) {
    return res.status(404).json({ error: 'المنتج غير موجود' });
  }
  db.products[index] = { ...db.products[index], ...req.body };
  saveDb(db);
  res.json(db.products[index]);
});

app.delete('/api/products/:id', (req: Request, res: Response) => {
  const db = getDb();
  db.products = db.products.filter(p => p.id !== req.params.id);
  saveDb(db);
  res.json({ success: true });
});

// Bundles API
app.get('/api/bundles', (_req: Request, res: Response) => {
  const db = getDb();
  res.json(db.bundles);
});

app.post('/api/bundles', (req: Request, res: Response) => {
  const db = getDb();
  const newBundle: HoneyBundle = {
    ...req.body,
    id: req.body.id || `bundle-${Date.now()}`
  };
  db.bundles.push(newBundle);
  saveDb(db);
  res.status(201).json(newBundle);
});

app.put('/api/bundles/:id', (req: Request, res: Response) => {
  const db = getDb();
  const index = db.bundles.findIndex(b => b.id === req.params.id);
  if (index === -1) {
    return res.status(404).json({ error: 'الباقة غير موجودة' });
  }
  db.bundles[index] = { ...db.bundles[index], ...req.body };
  saveDb(db);
  res.json(db.bundles[index]);
});

app.delete('/api/bundles/:id', (req: Request, res: Response) => {
  const db = getDb();
  db.bundles = db.bundles.filter(b => b.id !== req.params.id);
  saveDb(db);
  res.json({ success: true });
});

// Cities & Shipping Rates API
app.get('/api/cities', (_req: Request, res: Response) => {
  const db = getDb();
  res.json(db.cities);
});

app.put('/api/cities', (req: Request, res: Response) => {
  const db = getDb();
  if (Array.isArray(req.body)) {
    db.cities = req.body;
    saveDb(db);
    return res.json(db.cities);
  }
  res.status(400).json({ error: 'بيانات المدن غير صالحة' });
});

// Store Settings API
app.get('/api/settings', (_req: Request, res: Response) => {
  const db = getDb();
  // Do not expose admin password in public settings
  const { adminPassword, ...publicSettings } = db.settings;
  res.json(publicSettings);
});

app.put('/api/settings', (req: Request, res: Response) => {
  const db = getDb();
  db.settings = { ...db.settings, ...req.body };
  saveDb(db);
  const { adminPassword, ...publicSettings } = db.settings;
  res.json(publicSettings);
});

// Orders API
app.get('/api/orders', (req: Request, res: Response) => {
  const db = getDb();
  let orders = [...db.orders];

  const search = req.query.search as string;
  const status = req.query.status as string;

  if (search) {
    const q = search.trim().toLowerCase();
    orders = orders.filter(o => 
      o.id.toLowerCase().includes(q) ||
      o.customerName.toLowerCase().includes(q) ||
      o.phone.includes(q) ||
      o.city.toLowerCase().includes(q)
    );
  }

  if (status && status !== 'all') {
    orders = orders.filter(o => o.status === status);
  }

  // Sort by date descending
  orders.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
  res.json(orders);
});

app.get('/api/orders/:id', (req: Request, res: Response) => {
  const db = getDb();
  const order = db.orders.find(o => o.id === req.params.id);
  if (!order) {
    return res.status(404).json({ error: 'الطلب غير موجود' });
  }
  res.json(order);
});

// Order Creation with Strict Server-Side Pricing & Stock Validation
app.post('/api/orders', (req: Request, res: Response) => {
  const db = getDb();
  const { customerName, phone, city, address, items, notes } = req.body;

  if (!customerName || !phone || !city || !address) {
    return res.status(400).json({ error: 'يرجى ملء جميع الحقول المطلوبة (الاسم، الهاتف، المدينة، العنوان)' });
  }

  // Validate Moroccan phone number (06, 07, 05, +212)
  const cleanPhone = phone.replace(/[\s-]/g, '');
  const isValidMoroccanPhone = /^(0|\+212|00212)[567]\d{8}$/.test(cleanPhone);
  if (!isValidMoroccanPhone) {
    return res.status(400).json({ error: 'يرجى إدخال رقم هاتف مغربي صحيح (مثال: 0661234567)' });
  }

  if (!items || !Array.isArray(items) || items.length === 0) {
    return res.status(400).json({ error: 'سلة المشتريات فارغة' });
  }

  // Server-side recalculation of prices & subtotal
  let verifiedSubtotal = 0;
  const verifiedItems = [];

  for (const clientItem of items) {
    const qty = Math.max(1, parseInt(clientItem.quantity, 10) || 1);

    if (clientItem.type === 'bundle') {
      const bundle = db.bundles.find(b => b.id === clientItem.id);
      if (!bundle) {
        return res.status(400).json({ error: `الباقة "${clientItem.name}" غير متوفرة حالياً` });
      }
      const unitPrice = bundle.price;
      const totalPrice = unitPrice * qty;
      verifiedSubtotal += totalPrice;

      verifiedItems.push({
        id: bundle.id,
        type: 'bundle' as const,
        name: bundle.name,
        weightLabel: `${bundle.totalWeightGrams} غرام (${bundle.jarsCount} مرطبانات)`,
        quantity: qty,
        unitPrice,
        totalPrice,
        image: bundle.image
      });
    } else {
      const product = db.products.find(p => p.id === clientItem.id);
      if (!product) {
        return res.status(400).json({ error: `المنتج "${clientItem.name}" غير متوفر` });
      }

      // Match weight option
      const weightOpt = product.weights.find(w => `${w.weight}${w.unit}` === clientItem.weightCode || `${w.weight} ${w.unit === 'g' ? 'غرام' : 'كلغ'}` === clientItem.weightLabel || w.price === clientItem.unitPrice) || product.weights[0];
      
      const unitPrice = weightOpt.price;
      const totalPrice = unitPrice * qty;
      verifiedSubtotal += totalPrice;

      verifiedItems.push({
        id: product.id,
        type: 'product' as const,
        name: product.name,
        weightLabel: `${weightOpt.weight} ${weightOpt.unit === 'g' ? 'غرام' : 'كلغ'}`,
        quantity: qty,
        unitPrice,
        totalPrice,
        image: product.image
      });
    }
  }

  // Calculate Shipping fee based on selected city & threshold
  const cityMatch = db.cities.find(c => c.name === city || city.includes(c.name) || c.id === city);
  let shippingFee = cityMatch ? cityMatch.rate : 35;

  // Free shipping check
  if (db.settings.freeShippingThreshold && verifiedSubtotal >= db.settings.freeShippingThreshold) {
    shippingFee = 0;
  }

  const total = verifiedSubtotal + shippingFee;

  // Generate Unique Order ID (e.g. ATLAS-2026-XXXX)
  const randomSuffix = Math.floor(1000 + Math.random() * 9000);
  const orderId = `ATLAS-${randomSuffix}`;

  const newOrder: Order = {
    id: orderId,
    customerName: customerName.trim(),
    phone: cleanPhone,
    city: city.trim(),
    address: address.trim(),
    items: verifiedItems,
    subtotal: verifiedSubtotal,
    shippingFee,
    total,
    notes: notes ? notes.trim() : undefined,
    paymentMethod: 'COD',
    status: 'new',
    createdAt: new Date().toISOString()
  };

  db.orders.unshift(newOrder);
  saveDb(db);

  res.status(201).json({
    success: true,
    order: newOrder,
    message: 'تم تسجيل طلبك بنجاح وسيتصل بك فريقنا لتأكيد التسليم'
  });
});

// Update Order Status
app.patch('/api/orders/:id/status', (req: Request, res: Response) => {
  const db = getDb();
  const { status } = req.body;
  const order = db.orders.find(o => o.id === req.params.id);
  if (!order) {
    return res.status(404).json({ error: 'الطلب غير موجود' });
  }

  const validStatuses = ['new', 'confirming', 'confirmed', 'shipping', 'delivered', 'cancelled'];
  if (!validStatuses.includes(status)) {
    return res.status(400).json({ error: 'حالة الطلب غير صالحة' });
  }

  order.status = status;
  order.updatedAt = new Date().toISOString();
  saveDb(db);
  res.json({ success: true, order });
});

// Admin Auth Endpoint
app.post('/api/admin/login', (req: Request, res: Response) => {
  const db = getDb();
  const { password } = req.body;
  const storedPassword = db.settings.adminPassword || 'atlas';

  if (password === storedPassword) {
    return res.json({
      success: true,
      token: `admin-token-${Date.now()}`,
      storeName: db.settings.storeName
    });
  }

  return res.status(401).json({ error: 'كلمة المرور غير صحيحة' });
});

// Admin Stats
app.get('/api/admin/stats', (_req: Request, res: Response) => {
  const db = getDb();
  const orders = db.orders;

  const totalOrders = orders.length;
  const totalSales = orders
    .filter(o => o.status !== 'cancelled')
    .reduce((sum, o) => sum + o.total, 0);

  const pendingOrders = orders.filter(o => o.status === 'new' || o.status === 'confirming').length;
  const deliveredOrders = orders.filter(o => o.status === 'delivered').length;

  res.json({
    totalOrders,
    totalSales,
    pendingOrders,
    deliveredOrders,
    productsCount: db.products.length,
    bundlesCount: db.bundles.length
  });
});

// -------------------------------------------------------------
// Vite Middleware / Static Serving
// -------------------------------------------------------------
async function setupServer() {
  const isProduction = process.env.NODE_ENV === 'production';

  if (!isProduction) {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: 'spa',
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(__dirname, 'dist');
    app.use(express.static(distPath));
    app.get('*', (_req: Request, res: Response) => {
      res.sendFile(path.join(distPath, 'index.html'));
    });
  }

  app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
  });
}

setupServer().catch(err => {
  console.error('Failed to start server:', err);
});
