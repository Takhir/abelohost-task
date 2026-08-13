<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {block name="title"}
            {$appName|escape}
        {/block}
    </title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            font-family:
                    Arial,
                    sans-serif;

            color: #1f2937;

            background: #f8fafc;
        }

        a {
            color: #2563eb;

            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1100px;

            margin: 0 auto;

            padding: 30px 20px;
        }

        .articles {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }

        .article-card {
            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 10px;

            overflow: hidden;
        }

        .article-card img {
            width: 100%;

            height: 200px;

            object-fit: cover;
        }

        .article-card-content {
            padding: 20px;
        }

        .meta {
            color: #6b7280;

            font-size: 14px;
        }

        .category {
            margin-bottom: 60px;
        }

        .pagination {
            display: flex;

            gap: 8px;

            margin-top: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;

            border: 1px solid #d1d5db;

            border-radius: 6px;

            background: white;
        }

        .pagination .active {
            background: #2563eb;

            color: white;

            border-color: #2563eb;
        }

        @media (max-width: 800px) {
            .articles {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>
                <a href="/">
                    {$appName|escape}
                </a>
            </h1>
        </header>
        <main>
            {block name="content"}
            {/block}
        </main>
    </div>
</body>
</html>