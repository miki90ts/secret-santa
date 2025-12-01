<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaša Secret Santa dodela</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            margin: 20px 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #d32f2f 0%, #f57c00 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .emoji {
            font-size: 2em;
            margin: 10px 0;
        }
        .content {
            padding: 30px;
        }
        .assignment-card {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
            border: 2px solid #4caf50;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .assignment-card h2 {
            color: #2e7d32;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .receiver-name {
            font-size: 28px;
            font-weight: bold;
            color: #1b5e20;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .tips {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .tips h3 {
            color: #e65100;
            margin-top: 0;
        }
        .tip-item {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }
        .tip-item:before {
            content: "🎁";
            position: absolute;
            left: 0;
        }
        .date-info {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
        }
        .snowflake {
            color: #87ceeb;
            animation: fall 3s linear infinite;
        }
        @keyframes fall {
            0% { transform: translateY(-10px) rotate(0deg); }
            100% { transform: translateY(10px) rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="emoji">🎅🎄</div>
            <h1>Tajni Deda Mraz – {{ $event->name }}</h1>
            <p>Čarobna dodela poklona je uspešno obavljena!</p>
        </div>

        <div class="content">
            <p>Zdravo, <strong>{{ $giver->name }}</strong>! <span class="emoji">✨</span></p>
            
            <p>Imamo sjajne vesti! Dodela učesnika za događaj <strong>{{ $event->name }}</strong> je upravo završena!</p>

            <div class="assignment-card">
                <h2>🎁 Vaš zadatak je da darujete:</h2>
                <div class="receiver-name">{{ $receiver->name }}</div>
                <div class="emoji">🎉</div>
            </div>

            <p>Sada možete početi da birate savršen poklon! Listu želja vaše osobe možete pogledati klikom na dugme ispod.</p>

            <div style="text-align: center;">
                <a href="{{ $assignmentUrl }}" class="btn">
                    Pogledajte dodelu i listu želja 🎁
                </a>
            </div>

            <div class="tips">
                <h3>🎯 Nekoliko saveta za učešće u Tajnom Deda Mrazu:</h3>
                <div class="tip-item"><strong>Budžet:</strong> Pridržavajte se dogovorenog iznosa za poklon</div>
                <div class="tip-item"><strong>Lista želja:</strong> Pogledajte da li osoba ima želje ili predloge</div>
                <div class="tip-item"><strong>Diskrecija:</strong> Ne otkrivajte kome darujete dok ne dođe vreme 🤫</div>
                <div class="tip-item"><strong>Kreativnost:</strong> Lični i originalni pokloni su uvek najbolji</div>
            </div>

            @if($event->exchange_date)
            <div class="date-info">
                <strong>📅 Datum razmene poklona:</strong><br>
                {{ $event->exchange_date->format('d.m.Y \u H:i') }}
            </div>
            @endif

            <p style="text-align: center; font-size: 18px; color: #4caf50;">
                <strong>Želimo vam srećnu kupovinu i mnogo magije u Tajnom Deda Mrazu! ✨</strong>
            </p>
        </div>

        <div class="footer">
            <p>Topli pozdravi,<br><strong>Tim Tajnog Deda Mraza</strong></p>
            <div class="emoji">🎄 ❄️ 🎅</div>
        </div>
    </div>
</body>
</html>