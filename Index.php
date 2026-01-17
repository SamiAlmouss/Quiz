<?php
// صفحة الرئيسية مع الروابط
$current_page = $_GET['page'] ?? 'home';

// بيانات الصفحات
$pages = [
    'home' => [
        'title' => '🏠 الصفحة الرئيسية',
        'description' => 'خادم ويب متكامل مع Nginx، PHP، SQLite و Flask'
    ],
    'nginx' => [
        'title' => '🚀 خادم Nginx',
        'description' => 'تعرف على خادم Nginx وخصائصه المميزة'
    ],
    'sqlite' => [
        'title' => '💾 قاعدة بيانات SQLite',
        'description' => 'قاعدة بيانات خفيفة وسهلة الاستخدام'
    ],
    'flask' => [
        'title' => '🐍 إطار عمل Flask',
        'description' => 'إطار عمل Python لبناء تطبيقات الويب'
    ]
];

// اختيار المحتوى حسب الصفحة
switch($current_page) {
    case 'nginx':
        $page_content = getNginxPage();
        break;
    case 'sqlite':
        $page_content = getSqlitePage();
        break;
    case 'flask':
        $page_content = getFlaskPage();
        break;
    default:
        $page_content = getHomePage();
}
?>

<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pages[$current_page]['title'] ?></title>
    <style>
        /* CSS أساسي مشترك */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            min-height: 90vh;
        }
        
        .navbar {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 4px solid #00c6ff;
        }
        
        .logo {
            font-size: 1.8em;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background: #00c6ff;
            color: #1a2980;
        }
        
        .content {
            padding: 40px;
        }
        
        .page-header {
            margin-bottom: 40px;
            text-align: center;
        }
        
        .page-title {
            color: #1e3c72;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .page-description {
            color: #666;
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .footer {
            text-align: center;
            padding: 25px;
            background: #f8f9fa;
            color: #666;
            border-top: 1px solid #eee;
            margin-top: 40px;
        }
        
        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .nav-links {
                justify-content: center;
            }
            
            .content {
                padding: 20px;
            }
            
            .page-title {
                font-size: 2em;
            }
        }
        
        /* الكود التالي سيكون متضمناً في كل صفحة */
        <?= $page_content['css'] ?>
    </style>
</head>
<body>
    <div class="container">
        <!-- شريط التنقل -->
        <nav class="navbar">
            <div class="logo">
                <span>🌐</span>
                خادم الويب المتكامل
            </div>
            <div class="nav-links">
                <a href="?page=home" class="nav-link <?= $current_page == 'home' ? 'active' : '' ?>">
                    <span>🏠</span> الرئيسية
                </a>
                <a href="?page=nginx" class="nav-link <?= $current_page == 'nginx' ? 'active' : '' ?>">
                    <span>🚀</span> Nginx
                </a>
                <a href="?page=sqlite" class="nav-link <?= $current_page == 'sqlite' ? 'active' : '' ?>">
                    <span>💾</span> SQLite
                </a>
                <a href="?page=flask" class="nav-link <?= $current_page == 'flask' ? 'active' : '' ?>">
                    <span>🐍</span> Flask
                </a>
            </div>
        </nav>
        
        <!-- محتوى الصفحة -->
        <div class="content">
            <div class="page-header">
                <h1 class="page-title"><?= $pages[$current_page]['title'] ?></h1>
                <p class="page-description"><?= $pages[$current_page]['description'] ?></p>
            </div>
            
            <?= $page_content['html'] ?>
        </div>
        
        <!-- تذييل الصفحة -->
        <div class="footer">
            <p>تم التطوير باستخدام PHP <?= phpversion() ?> | <?= date('Y') ?> | جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>

<?php
// دالة الصفحة الرئيسية
function getHomePage() {
    return [
        'css' => '
            .hero {
                text-align: center;
                padding: 60px 20px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 20px;
                color: white;
                margin-bottom: 50px;
            }
            
            .hero h2 {
                font-size: 3em;
                margin-bottom: 20px;
            }
            
            .hero p {
                font-size: 1.2em;
                max-width: 800px;
                margin: 0 auto 30px;
                opacity: 0.9;
            }
            
            .tech-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
                margin: 40px 0;
            }
            
            .tech-card {
                background: white;
                border-radius: 15px;
                padding: 30px;
                text-align: center;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            
            .tech-card:hover {
                transform: translateY(-10px);
                border-color: #667eea;
            }
            
            .tech-icon {
                font-size: 3em;
                margin-bottom: 20px;
            }
            
            .tech-title {
                color: #1e3c72;
                font-size: 1.5em;
                margin-bottom: 15px;
            }
            
            .tech-description {
                color: #666;
                line-height: 1.6;
            }
            
            .features {
                margin-top: 50px;
                background: #f8f9fa;
                padding: 40px;
                border-radius: 20px;
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-top: 30px;
            }
            
            .feature-item {
                background: white;
                padding: 20px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .feature-icon {
                font-size: 2em;
                color: #667eea;
            }
        ',
        'html' => '
            <div class="hero">
                <h2>🌐 مرحباً بك في خادم الويب المتكامل</h2>
                <p>منصة شاملة تجمع بين أفضل تقنيات تطوير الويب في مكان واحد</p>
            </div>
            
            <h2 style="text-align: center; color: #1e3c72; margin-bottom: 30px;">🔧 التقنيات المستخدمة</h2>
            
            <div class="tech-grid">
                <div class="tech-card">
                    <div class="tech-icon">🚀</div>
                    <h3 class="tech-title">خادم Nginx</h3>
                    <p class="tech-description">خادم ويب سريع وفعال مع استهلاك منخفض للذاكرة، يدعم معالجة متزامنة للطلبات وحمل عالي.</p>
                </div>
                
                <div class="tech-card">
                    <div class="tech-icon">💾</div>
                    <h3 class="tech-title">قاعدة بيانات SQLite</h3>
                    <p class="tech-description">قاعدة بيانات خفيفة الوزن، سريعة، ولا تحتاج إلى خادم منفصل، مثالية للتطبيقات الصغيرة والمتوسطة.</p>
                </div>
                
                <div class="tech-card">
                    <div class="tech-icon">🐍</div>
                    <h3 class="tech-title">إطار عمل Flask</h3>
                    <p class="tech-description">إطار عمل Python بسيط ومرن لبناء تطبيقات ويب سريعة مع الحد الأدنى من التعقيد.</p>
                </div>
            </div>
            
            <div class="features">
                <h2 style="text-align: center; color: #1e3c72; margin-bottom: 30px;">✨ مميزات المنصة</h2>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">⚡</div>
                        <div>
                            <h4>سرعة عالية</h4>
                            <p>أداء ممتاز في معالجة الطلبات</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <div>
                            <h4>آمنة</h4>
                            <p>حماية متقدمة للبيانات</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">🔄</div>
                        <div>
                            <h4>مرنة</h4>
                            <p>سهلة التعديل والتطوير</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">📱</div>
                        <div>
                            <h4>متجاوبة</h4>
                            <p>تعمل على جميع الأجهزة</p>
                        </div>
                    </div>
                </div>
            </div>
        '
    ];
}

// دالة صفحة Nginx
function getNginxPage() {
    $server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Nginx';
    $php_version = phpversion();
    
    return [
        'css' => '
            .nginx-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin: 30px 0;
            }
            
            .stat-card {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                padding: 25px;
                border-radius: 15px;
                text-align: center;
            }
            
            .stat-value {
                font-size: 2em;
                font-weight: bold;
                margin: 10px 0;
            }
            
            .stat-label {
                opacity: 0.9;
            }
            
            .nginx-features {
                background: #f0f7ff;
                padding: 30px;
                border-radius: 15px;
                margin: 30px 0;
            }
            
            .feature-list {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 15px;
                margin-top: 20px;
            }
            
            .feature-list li {
                background: white;
                padding: 15px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            }
            
            .code-example {
                background: #1e1e1e;
                color: #d4d4d4;
                padding: 20px;
                border-radius: 10px;
                overflow-x: auto;
                font-family: "Courier New", monospace;
                margin: 20px 0;
                direction: ltr;
                text-align: left;
            }
            
            .config-block {
                background: #2d2d2d;
                padding: 20px;
                border-radius: 10px;
                margin: 20px 0;
                direction: ltr;
            }
            
            .keyword {
                color: #569cd6;
            }
            
            .string {
                color: #ce9178;
            }
            
            .comment {
                color: #6a9955;
            }
        ',
        'html' => '
            <div class="nginx-stats">
                <div class="stat-card">
                    <div class="stat-label">خادم الويب</div>
                    <div class="stat-value">Nginx</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">إصدار PHP</div>
                    <div class="stat-value">' . $php_version . '</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">الحالة</div>
                    <div class="stat-value">✅ نشط</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">التاريخ</div>
                    <div class="stat-value">' . date('Y-m-d') . '</div>
                </div>
            </div>
            
            <div class="nginx-features">
                <h3 style="color: #1e3c72; margin-bottom: 20px;">✨ مميزات Nginx الرائعة</h3>
                
                <ul class="feature-list" style="list-style: none;">
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> معمارية غير متزامنة (Asynchronous)</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> استهلاك منخفض للذاكرة</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> تحميل عالي للطلبات</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> Reverse Proxy مدمج</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> Load Balancing</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> دعم HTTP/2 و HTTP/3</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> Caching متقدم</li>
                    <li><span style="color: #4CAF50; font-size: 1.2em;">✓</span> Gzip Compression</li>
                </ul>
            </div>
            
            <h3 style="color: #1e3c72; margin: 30px 0 15px;">⚙️ مثال على إعدادات Nginx</h3>
            
            <div class="config-block">
                <pre class="code-example">
<span class="keyword">server</span> {
    <span class="keyword">listen</span> <span class="string">80</span>;
    <span class="keyword">server_name</span> <span class="string">example.com</span>;
    <span class="keyword">root</span> <span class="string">/var/www/html</span>;
    
    <span class="comment"># إعدادات الفهرس</span>
    <span class="keyword">index</span> <span class="string">index.php index.html index.htm</span>;
    
    <span class="keyword">location</span> / {
        <span class="keyword">try_files</span> <span class="string">$uri $uri/ =404</span>;
    }
    
    <span class="comment"># معالجة ملفات PHP</span>
    <span class="keyword">location</span> <span class="string">~ \\.php$</span> {
        <span class="keyword">include</span> <span class="string">fastcgi_params</span>;
        <span class="keyword">fastcgi_pass</span> <span class="string">unix:/run/php/php-fpm.sock</span>;
        <span class="keyword">fastcgi_index</span> <span class="string">index.php</span>;
        <span class="keyword">fastcgi_param</span> <span class="string">SCRIPT_FILENAME $document_root$fastcgi_script_name</span>;
    }
}</pre>
            </div>
            
            <div style="background: #e8f5e9; padding: 20px; border-radius: 10px; margin-top: 30px;">
                <h4 style="color: #2e7d32; margin-bottom: 10px;">💡 معلومات مهمة</h4>
                <p>ملف إعدادات Nginx الرئيسي: <code>/etc/nginx/nginx.conf</code></p>
                <p>ملف إعدادات الموقع: <code>/etc/nginx/sites-available/default</code></p>
                <p>اختبار الإعدادات: <code>sudo nginx -t</code></p>
            </div>
        '
    ];
}

// دالة صفحة SQLite
function getSqlitePage() {
    return [
        'css' => '
            .sqlite-demo {
                background: linear-gradient(135deg, #4CAF50, #2E7D32);
                color: white;
                padding: 30px;
                border-radius: 15px;
                margin: 30px 0;
                text-align: center;
            }
            
            .db-structure {
                background: #f5f5f5;
                padding: 25px;
                border-radius: 15px;
                margin: 20px 0;
                border-right: 5px solid #4CAF50;
            }
            
            .sql-examples {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 30px 0;
            }
            
            .sql-card {
                background: white;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border-top: 4px solid #4CAF50;
            }
            
            .sql-code {
                background: #2d2d2d;
                color: #f8f8f2;
                padding: 15px;
                border-radius: 8px;
                font-family: "Courier New", monospace;
                margin: 10px 0;
                overflow-x: auto;
                direction: ltr;
                text-align: left;
            }
            
            .advantages {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin: 30px 0;
            }
            
            .advantage-card {
                background: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                border: 2px solid #e0e0e0;
            }
            
            .advantage-icon {
                font-size: 2.5em;
                margin-bottom: 15px;
                color: #4CAF50;
            }
            
            .php-sqlite {
                background: #e3f2fd;
                padding: 25px;
                border-radius: 15px;
                margin-top: 30px;
            }
        ',
        'html' => '
            <div class="sqlite-demo">
                <h3 style="margin-bottom: 15px;">💾 SQLite - قاعدة البيانات في ملف واحد</h3>
                <p>قاعدة بيانات خفيفة، سريعة، ولا تحتاج إلى خادم منفصل</p>
            </div>
            
            <div class="advantages">
                <div class="advantage-card">
                    <div class="advantage-icon">⚡</div>
                    <h4>سريعة</h4>
                    <p>أداء عالي في القراءة والكتابة</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">📁</div>
                    <h4>ملف واحد</h4>
                    <p>جميع البيانات في ملف .db واحد</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">🔧</div>
                    <h4>سهلة التركيب</h4>
                    <p>لا تحتاج إلى إعدادات معقدة</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">💰</div>
                    <h4>مجانية</h4>
                    <p>مفتوحة المصدر تماماً</p>
                </div>
            </div>
            
            <div class="db-structure">
                <h4 style="color: #2E7D32; margin-bottom: 15px;">📊 هيكل قاعدة بيانات SQLite</h4>
                <p>ملف SQLite (.db) يحتوي على:</p>
                <ul style="padding-right: 20px; margin: 10px 0;">
                    <li>الجداول (Tables)</li>
                    <li>المؤشرات (Indexes)</li>
                    <li>المشاهدات (Views)</li>
                    <li>المحفزات (Triggers)</li>
                    <li>الدوال (Functions)</li>
                </ul>
            </div>
            
            <h4 style="color: #1e3c72; margin: 30px 0 15px;">🔍 أمثلة على أوامر SQLite</h4>
            
            <div class="sql-examples">
                <div class="sql-card">
                    <h5>إنشاء جدول</h5>
                    <pre class="sql-code">CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);</pre>
                </div>
                
                <div class="sql-card">
                    <h5>إضافة بيانات</h5>
                    <pre class="sql-code">INSERT INTO users (name, email) 
VALUES ("أحمد", "ahmed@example.com");</pre>
                </div>
                
                <div class="sql-card">
                    <h5>استعلام البيانات</h5>
                    <pre class="sql-code">SELECT * FROM users 
WHERE email LIKE "%@example.com"
ORDER BY created_at DESC
LIMIT 10;</pre>
                </div>
            </div>
            
            <div class="php-sqlite">
                <h4 style="color: #1565c0; margin-bottom: 15px;">🐘 استخدام SQLite مع PHP</h4>
                
                <div class="sql-code">// الاتصال بقاعدة بيانات SQLite
$db = new SQLite3("mydatabase.db");

// تنفيذ استعلام
$result = $db->query("SELECT * FROM users");

while ($row = $result->fetchArray()) {
    echo $row["name"] . " - " . $row["email"];
}

// إغلاق الاتصال
$db->close();</div>
                
                <p style="margin-top: 15px; color: #666;">
                    <strong>ملاحظة:</strong> SQLite مثالية للتطبيقات الصغيرة والمتوسطة، 
                    التطبيقات المحلية، والأنظمة المضمنة.
                </p>
            </div>
            
            <div style="background: #fff3e0; padding: 20px; border-radius: 10px; margin-top: 30px;">
                <h5 style="color: #ef6c00;">📈 متى تستخدم SQLite؟</h5>
                <ul style="padding-right: 20px; margin: 10px 0;">
                    <li>تطبيقات الويب الصغيرة والمتوسطة</li>
                    <li>التطبيقات المحلية (Desktop)</li>
                    <li>تطبيقات الجوال</li>
                    <li>أنظمة IoT والمضمنة</li>
                    <li>بيئات التطوير والاختبار</li>
                </ul>
            </div>
        '
    ];
}

// دالة صفحة Flask
function getFlaskPage() {
    return [
        'css' => '
            .flask-hero {
                background: linear-gradient(135deg, #FF9800, #F57C00);
                color: white;
                padding: 40px;
                border-radius: 20px;
                text-align: center;
                margin-bottom: 40px;
            }
            
            .flask-features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 25px;
                margin: 30px 0;
            }
            
            .feature-card {
                background: white;
                padding: 25px;
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                border-left: 5px solid #FF9800;
            }
            
            .feature-card h4 {
                color: #F57C00;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .python-code {
                background: #263238;
                color: #eceff1;
                padding: 20px;
                border-radius: 10px;
                font-family: "Courier New", monospace;
                margin: 20px 0;
                overflow-x: auto;
                direction: ltr;
                text-align: left;
            }
            
            .highlight {
                color: #82b1ff;
            }
            
            .string {
                color: #c3e88d;
            }
            
            .comment {
                color: #546e7a;
            }
            
            .route-examples {
                background: #fff3e0;
                padding: 25px;
                border-radius: 15px;
                margin: 30px 0;
            }
            
            .route-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
                margin-top: 20px;
            }
            
            .route-item {
                background: white;
                padding: 15px;
                border-radius: 8px;
                font-family: monospace;
                border: 1px solid #ffcc80;
            }
            
            .flask-ecosystem {
                background: #e8f5e9;
                padding: 30px;
                border-radius: 15px;
                margin-top: 30px;
            }
            
            .extensions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 15px;
            }
            
            .extension {
                background: #4CAF50;
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-size: 0.9em;
            }
        ',
        'html' => '
            <div class="flask-hero">
                <h3 style="font-size: 2em; margin-bottom: 15px;">🐍 Flask - إطار عمل Python للويب</h3>
                <p>بسيط، خفيف، وقوي لبناء تطبيقات ويب سريعة</p>
            </div>
            
            <div class="flask-features">
                <div class="feature-card">
                    <h4><span>🎯</span> بسيط وسهل</h4>
                    <p>يكفي بضعة أسطر لبدء تطبيق ويب</p>
                </div>
                
                <div class="feature-card">
                    <h4><span>⚡</span> خفيف الوزن</h4>
                    <p>لا يحتوي على أدوات غير ضرورية</p>
                </div>
                
                <div class="feature-card">
                    <h4><span>🔌</span> مرن وقابل للتوسع</h4>
                    <p>يدعم الإضافات حسب الحاجة</p>
                </div>
            </div>
            
            <h4 style="color: #1e3c72; margin: 30px 0 15px;">💻 أبسط تطبيق Flask</h4>
            
            <div class="python-code">
<span class="comment"># app.py</span>
<span class="keyword">from</span> flask <span class="keyword">import</span> Flask

app = Flask(__name__)

<span class="keyword">@app</span>.route(<span class="string">"/"</span>)
<span class="keyword">def</span> home():
    <span class="keyword">return</span> <span class="string">"مرحباً بالعالم!"</span>

<span class="keyword">@app</span>.route(<span class="string">"/about"</span>)
<span class="keyword">def</span> about():
    <span class="keyword">return</span> <span class="string">"من نحن"</span>

<span class="keyword">if</span> __name__ == <span class="string">"__main__"</span>:
    app.run(debug=True)</div>
            
            <div class="route-examples">
                <h5 style="color: #ef6c00;">🌐 أمثلة على Routes في Flask</h5>
                
                <div class="route-grid">
                    <div class="route-item">@app.route("/")</div>
                    <div class="route-item">@app.route("/user/&lt;username&gt;")</div>
                    <div class="route-item">@app.route("/post/&lt;int:post_id&gt;")</div>
                    <div class="route-item">@app.route("/api/data", methods=["GET"])</div>
                    <div class="route-item">@app.route("/login", methods=["POST"])</div>
                </div>
            </div>
            
            <h4 style="color: #1e3c72; margin: 30px 0 15px;">🔧 تركيب Flask</h4>
            
            <div class="python-code">
<span class="comment"># تثبيت Flask</span>
pip install flask

<span class="comment"># تشغيل التطبيق</span>
python app.py

<span class="comment"># الوصول للتطبيق</span>
<span class="comment"># http://localhost:5000</span></div>
            
            <div class="flask-ecosystem">
                <h5 style="color: #2e7d32;">📚 إضافات Flask الشائعة</h5>
                
                <div class="extensions">
                    <span class="extension">Flask-SQLAlchemy</span>
                    <span class="extension">Flask-Login</span>
                    <span class="extension">Flask-WTF</span>
                    <span class="extension">Flask-Mail</span>
                    <span class="extension">Flask-RESTful</span>
                    <span class="extension">Flask-SocketIO</span>
                    <span class="extension">Flask-Caching</span>
                    <span class="extension">Flask-Migrate</span>
                </div>
                
                <p style="margin-top: 20px; color: #666;">
                    <strong>💡 نصائح:</strong> Flask مثالي للمشاريع الصغيرة والمتوسطة، 
                    APIs، وتطبيقات الويب السريعة. يمكن دمجه مع Nginx و Gunicorn للإنتاج.
                </p>
            </div>
            
            <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin-top: 30px;">
                <h5 style="color: #1565c0;">🚀 Flask + Nginx + Gunicorn</h5>
                <p style="margin: 10px 0;">لبيئة إنتاج احترافية:</p>
                <ol style="padding-right: 20px; margin: 10px 0;">
                    <li>Gunicorn كخادم تطبيقات</li>
                    <li>Nginx كـ Reverse Proxy</li>
                    <li>Supervisor لإدارة العمليات</li>
                    <li>Redis للذاكرة المخبأة</li>
                    <li>PostgreSQL لقاعدة البيانات</li>
                </ol>
            </div>
        '
    ];
}
?>
