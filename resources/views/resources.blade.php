<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resources</title>
        <link rel="stylesheet" href="/css/main.css">

        <style>
        body {
            background: linear-gradient(to bottom right, #f0fdf4, #e0f7fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .resources-wrapper {
            padding: 60px 20px;
            text-align: center;
        }

        .resources-wrapper h1 {
            font-size: 2.5rem;
            color: #2f4f4f;
            margin-bottom: 10px;
        }

        .resources-wrapper p {
            font-size: 1.1rem;
            margin-bottom: 40px;
            color: #555;
        }

        .resource-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .resource-card {
            background-color: #ffffffdd;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 25px;
            max-width: 300px;
            flex: 1 1 280px;
            text-align: left;
            transition: transform 0.3s ease;
        }

        .resource-card:hover {
            transform: translateY(-5px);
        }

        .resource-card h3 {
            color: #00796b;
            margin-bottom: 10px;
        }

        .resource-card p {
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .resource-card a {
            display: inline-block;
            margin-top: 12px;
            background-color: #4caf50;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .resource-card a:hover {
            background-color: #388e3c;
        }
    </style>
    </head>
    <body>

        <x-layout>
          
            
            <div class="resources-wrapper">
        <h1>Helpful Resources</h1>
        <p>Explore tools, guides, and services to support you in end-of-life care, planning, and death literacy.</p>

        <div class="resource-grid">

            <div class="resource-card">
                <h3>End-of-Life HELP User Guide</h3>
                <p>A helpful guide for anyone trying to navigate the HELP app, and how best to uitilize its features.</p>
                <a href="/images/HELP-App-User-Guide.pdf" target="_blank">Download PDF</a>
            </div>

            <div class="resource-card">
                <h3>Learn About The HELP Program</h3>
                <p>Learn about the HELP program and get a better understanding of what it hopes to achieve.</p>
                <a href="https://healthyendoflifeprogram.org/help-program/" target="_blank">Visit Site</a>
            </div>

            <div class="resource-card">
                <h3>Conversation Starters</h3>
                <p>Prompts and guidance to help families and friends talk openly about death, dying, and what matters most.</p>
                <a href="/images/Generic-HELP-App-Conversation-Starter-V2.pdf" target="_blank">Download PDF</a>
            </div>

            <div class="resource-card">
                <h3>HELP App Research Sheet</h3>
                <p>Information about the HELP app, how it treats user data and what you should know about what happens with your information.</p>
                <a href="/images/HELP-App-Research-Information-sheet.pdf" target="_blank">Download PDF</a>
            </div>

        </div>
    </div>

              
        
        </body>
                </html>
        </x-layout>
