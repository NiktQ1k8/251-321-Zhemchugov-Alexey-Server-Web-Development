<?php
// Динамическая часть: определяем имя посетителя (можно заменить на логику из сессии, БД и т.п.)
$visitorName = 'Гость';
if (isset($_SERVER['REMOTE_ADDR'])) {
    // Можно использовать GeoIP или хранить в сессии – для примера просто по IP
    $visitorName = 'Посетитель (' . $_SERVER['REMOTE_ADDR'] . ')';
}

// Текущая дата и время по Москве
date_default_timezone_set('Europe/Moscow');
$currentDateTime = date('d.m.Y H:i:s');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Домашняя работа 2.2 – Hello, World!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f5f7fa;
            color: #2c3e50;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #1e2b3a 0%, #0f1722 100%);
            color: white;
            padding: 0.8rem 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-bottom: 3px solid #e67e22;
        }

        .logo {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .header-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.6rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            white-space: nowrap;
        }

        /* Main */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }

        .dynamic-card {
            background: white;
            padding: 3rem 4rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(230,126,34,0.2);
            max-width: 700px;
            width: 100%;
        }

        .greeting {
            font-size: 3.5rem;
            font-weight: 700;
            color: #1e2b3a;
            margin-bottom: 0.5rem;
        }

        .sub-greeting {
            font-size: 1.8rem;
            color: #e67e22;
            margin-bottom: 2rem;
            border-bottom: 2px dashed #e67e22;
            padding-bottom: 1rem;
            display: inline-block;
        }

        .info-text {
            font-size: 1.2rem;
            margin: 1rem 0;
            color: #34495e;
        }

        .datetime {
            background: #ecf0f1;
            padding: 0.8rem 1.8rem;
            border-radius: 40px;
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            display: inline-block;
            margin-top: 1.5rem;
            border: 1px solid #bdc3c7;
        }

        /* Footer */
        .footer {
            background-color: #1e2b3a;
            color: #bdc3c7;
            text-align: center;
            padding: 1.2rem;
            font-size: 0.95rem;
            border-top: 1px solid #2c3e50;
        }

        .footer a {
            color: #e67e22;
            text-decoration: none;
            margin: 0 0.3rem;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                padding: 1rem;
            }
            .header-title {
                position: static;
                transform: none;
                margin-top: 0.5rem;
                font-size: 1.3rem;
                white-space: normal;
                text-align: center;
            }
            .logo {
                height: 40px;
            }
            .greeting {
                font-size: 2.5rem;
            }
            .sub-greeting {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <!-- Логотип слева -->
        <img src="logo.svg" alt="Московский Политех" class="logo">
        <!-- Заголовок по центру -->
        <div class="header-title">2.1. Домашняя работа: Hello, World!</div>
        <!-- Пустой блок для сохранения flex-баланса (можно убрать, если не нужно) -->
        <div style="width: 50px;"></div>
    </header>

    <main class="main">
        <div class="dynamic-card">
            <div class="greeting">👋 Привет, мир!</div>
            <div class="sub-greeting">Hello, World!</div>

            <!-- Динамический контент на PHP -->
            <div class="info-text">
                Добро пожаловать, <strong><?php echo htmlspecialchars($visitorName); ?></strong>!
            </div>
            <div class="info-text">
                Сегодня: <span class="datetime"><?php echo $currentDateTime; ?></span>
            </div>

            <p style="margin-top: 2.5rem; color: #7f8c8d;">
                <em>Страница сгенерирована на сервере <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'PHP'; ?></em>
            </p>
        </div>
    </main>

    <footer class="footer">
        <div>
            © <?php echo date('Y'); ?> Московский Политех. 
            <a href="#">О вузе</a> | 
            <a href="#">Контакты</a> | 
            <a href="#">Политика конфиденциальности</a>
        </div>
        <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #95a5a6;">
            Факультет информационных технологий • Группа 221-321 • Иванов И.И.
        </div>
    </footer>
</body>
</html>