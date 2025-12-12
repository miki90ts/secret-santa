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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #d32f2f;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .icon-group {
            margin: 15px 0;
            text-align: center;
        }

        .icon {
            width: 40px;
            height: 40px;
            display: inline-block;
            margin: 0 10px;
            vertical-align: middle;
        }

        .content {
            padding: 30px;
        }

        .assignment-card {
            background-color: #e8f5e8;
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
            background-color: #4caf50;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
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
            padding-left: 30px;
            position: relative;
        }

        .tip-item svg {
            position: absolute;
            left: 0;
            top: 2px;
            width: 20px;
            height: 20px;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="icon-group">
                <!-- Santa Icon -->
                <svg class="icon" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C10.9 2 10 2.9 10 4C10 5.1 10.9 6 12 6C13.1 6 14 5.1 14 4C14 2.9 13.1 2 12 2M12 8C9.8 8 8 9.8 8 12C8 14.2 9.8 16 12 16C14.2 16 16 14.2 16 12C16 9.8 14.2 8 12 8M12 10C13.1 10 14 10.9 14 12C14 13.1 13.1 14 12 14C10.9 14 10 13.1 10 12C10 10.9 10.9 10 12 10M4 16C2.9 16 2 16.9 2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18C22 16.9 21.1 16 20 16H4Z" />
                </svg>
                <!-- Christmas Tree Icon -->
                <svg class="icon" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L8 8H11L7 14H10L6 22H18L14 14H17L13 8H16L12 2Z" />
                </svg>
            </div>
            <h1>Tajni Deda Mraz – {{ $event->name }}</h1>
            <p>Čarobna dodela poklona je uspešno obavljena!</p>
        </div>

        <div class="content">
            <p>Zdravo, <strong>{{ $giver->name }}</strong>!</p>

            <p>Imamo sjajne vesti! Dodela učesnika za događaj <strong>{{ $event->name }}</strong> je upravo završena!</p>

            <div class="assignment-card">
                <h2>
                    <!-- Gift Icon -->
                    <svg style="width: 30px; height: 30px; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="#2e7d32" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12V20C22 21.1 21.1 22 20 22H4C2.9 22 2 21.1 2 20V12H22M15 7C15 8.1 15.9 9 17 9C18.1 9 19 8.1 19 7C19 5.9 18.1 5 17 5C15.9 5 15 5.9 15 7M5 7C5 8.1 5.9 9 7 9C8.1 9 9 8.1 9 7C9 5.9 8.1 5 7 5C5.9 5 5 5.9 5 7M22 10H2V7C2 5.9 2.9 5 4 5H9.18C9.6 3.84 10.7 3 12 3C13.3 3 14.4 3.84 14.82 5H20C21.1 5 22 5.9 22 7V10M13 7C13 6.45 12.55 6 12 6C11.45 6 11 6.45 11 7C11 7.55 11.45 8 12 8C12.55 8 13 7.55 13 7Z" />
                    </svg>
                    Vaš zadatak je da darujete:
                </h2>
                <div class="receiver-name">{{ $receiver->name }}</div>
                <div class="icon-group">
                    <!-- Party Icon -->
                    <svg style="width: 50px; height: 50px;" viewBox="0 0 24 24" fill="#4caf50" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 16.12C16.47 15.71 19.17 12.76 19.17 9.17C19.17 5.3 16.04 2.17 12.17 2.17C8.3 2.17 5.17 5.3 5.17 9.17C5.17 10.78 5.77 12.25 6.75 13.37L5 22L13.37 20.25C13.78 19.81 14.11 19.29 14.37 18.72C13.83 17.89 13.5 16.92 13.5 15.88C13.5 15.95 13.26 16.06 13 16.12M9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5C10.62 11.5 9.5 10.38 9.5 9M18 14.5V17.5H21V19.5H18V22.5H16V19.5H13V17.5H16V14.5H18Z" />
                    </svg>
                </div>
            </div>

            <p>Sada možete početi da birate savršen poklon! Listu želja vaše osobe možete pogledati klikom na dugme ispod.</p>

            <div style="text-align: center;">
                <a href="{{ $assignmentUrl }}" class="btn">
                    Pogledajte dodelu i listu želja
                </a>
            </div>

            <div class="tips">
                <h3>Nekoliko saveta za učešće u Tajnom Deda Mrazu:</h3>
                <div class="tip-item">
                    <!-- Gift SVG -->
                    <svg viewBox="0 0 24 24" fill="#ff9800" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12V20C22 21.1 21.1 22 20 22H4C2.9 22 2 21.1 2 20V12H22M15 7C15 8.1 15.9 9 17 9C18.1 9 19 8.1 19 7C19 5.9 18.1 5 17 5C15.9 5 15 5.9 15 7M5 7C5 8.1 5.9 9 7 9C8.1 9 9 8.1 9 7C9 5.9 8.1 5 7 5C5.9 5 5 5.9 5 7M22 10H2V7C2 5.9 2.9 5 4 5H9.18C9.6 3.84 10.7 3 12 3C13.3 3 14.4 3.84 14.82 5H20C21.1 5 22 5.9 22 7V10Z" />
                    </svg>
                    <strong>Budžet:</strong> Pridržavajte se dogovorenog iznosa za poklon
                </div>
                <div class="tip-item">
                    <svg viewBox="0 0 24 24" fill="#ff9800" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12V20C22 21.1 21.1 22 20 22H4C2.9 22 2 21.1 2 20V12H22M15 7C15 8.1 15.9 9 17 9C18.1 9 19 8.1 19 7C19 5.9 18.1 5 17 5C15.9 5 15 5.9 15 7M5 7C5 8.1 5.9 9 7 9C8.1 9 9 8.1 9 7C9 5.9 8.1 5 7 5C5.9 5 5 5.9 5 7M22 10H2V7C2 5.9 2.9 5 4 5H9.18C9.6 3.84 10.7 3 12 3C13.3 3 14.4 3.84 14.82 5H20C21.1 5 22 5.9 22 7V10Z" />
                    </svg>
                    <strong>Lista želja:</strong> Pogledajte da li osoba ima želje ili predloge
                </div>
                <div class="tip-item">
                    <svg viewBox="0 0 24 24" fill="#ff9800" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12V20C22 21.1 21.1 22 20 22H4C2.9 22 2 21.1 2 20V12H22M15 7C15 8.1 15.9 9 17 9C18.1 9 19 8.1 19 7C19 5.9 18.1 5 17 5C15.9 5 15 5.9 15 7M5 7C5 8.1 5.9 9 7 9C8.1 9 9 8.1 9 7C9 5.9 8.1 5 7 5C5.9 5 5 5.9 5 7M22 10H2V7C2 5.9 2.9 5 4 5H9.18C9.6 3.84 10.7 3 12 3C13.3 3 14.4 3.84 14.82 5H20C21.1 5 22 5.9 22 7V10Z" />
                    </svg>
                    <strong>Diskrecija:</strong> Ne otkrivajte kome darujete dok ne dođe vreme
                </div>
                <div class="tip-item">
                    <svg viewBox="0 0 24 24" fill="#ff9800" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12V20C22 21.1 21.1 22 20 22H4C2.9 22 2 21.1 2 20V12H22M15 7C15 8.1 15.9 9 17 9C18.1 9 19 8.1 19 7C19 5.9 18.1 5 17 5C15.9 5 15 5.9 15 7M5 7C5 8.1 5.9 9 7 9C8.1 9 9 8.1 9 7C9 5.9 8.1 5 7 5C5.9 5 5 5.9 5 7M22 10H2V7C2 5.9 2.9 5 4 5H9.18C9.6 3.84 10.7 3 12 3C13.3 3 14.4 3.84 14.82 5H20C21.1 5 22 5.9 22 7V10Z" />
                    </svg>
                    <strong>Kreativnost:</strong> Lični i originalni pokloni su uvek najbolji
                </div>
            </div>

            @if($event->exchange_date)
            <div class="date-info">
                <strong>
                    <!-- Calendar Icon -->
                    <svg style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;" viewBox="0 0 24 24" fill="#2196f3" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 3H18V1H16V3H8V1H6V3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M19 19H5V8H19V19Z" />
                    </svg>
                    Datum razmene poklona:
                </strong><br>
                {{ $event->exchange_date->format('d.m.Y \u H:i') }}
            </div>
            @endif

            <p style="text-align: center; font-size: 18px; color: #4caf50;">
                <strong>Želimo vam srećnu kupovinu i mnogo magije u Tajnom Deda Mrazu!</strong>
            </p>
        </div>

        <div class="footer">
            <p>Topli pozdravi,<br><strong>Tim Tajnog Deda Mraza</strong></p>
            <div class="icon-group">
                <!-- Christmas Tree -->
                <svg class="icon" style="width: 30px; height: 30px;" viewBox="0 0 24 24" fill="#4caf50" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L8 8H11L7 14H10L6 22H18L14 14H17L13 8H16L12 2Z" />
                </svg>
                <!-- Snowflake -->
                <svg class="icon" style="width: 30px; height: 30px;" viewBox="0 0 24 24" fill="#87ceeb" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.47 11.27L18.57 12.72L20.47 13.67L19.77 15.38L17.87 14.43L17.92 16.78H16.17L16.22 14.43L14.32 15.38L13.62 13.67L15.52 12.72L12.62 11.27L13.32 9.56L16.22 11L16.17 8.66H17.92L17.87 11L20.77 9.56L21.47 11.27M9.67 13.67L8.97 15.38L7.07 14.43L7.12 16.78H5.37L5.42 14.43L3.52 15.38L2.82 13.67L4.72 12.72L1.82 11.27L2.52 9.56L5.42 11L5.37 8.66H7.12L7.07 11L9.97 9.56L10.67 11.27L7.77 12.72L9.67 13.67M13.32 18.12L12.62 19.83L9.72 18.38L9.77 20.73H8.02L8.07 18.38L6.17 19.33L5.47 17.62L7.37 16.67L4.47 15.22L5.17 13.51L8.07 14.96L8.02 12.61H9.77L9.72 14.96L11.62 14.01L12.32 15.72L10.42 16.67L13.32 18.12Z" />
                </svg>
                <!-- Santa -->
                <svg class="icon" style="width: 30px; height: 30px;" viewBox="0 0 24 24" fill="#d32f2f" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C10.9 2 10 2.9 10 4C10 5.1 10.9 6 12 6C13.1 6 14 5.1 14 4C14 2.9 13.1 2 12 2M12 8C9.8 8 8 9.8 8 12C8 14.2 9.8 16 12 16C14.2 16 16 14.2 16 12C16 9.8 14.2 8 12 8M12 10C13.1 10 14 10.9 14 12C14 13.1 13.1 14 12 14C10.9 14 10 13.1 10 12C10 10.9 10.9 10 12 10M4 16C2.9 16 2 16.9 2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18C22 16.9 21.1 16 20 16H4Z" />
                </svg>
            </div>
        </div>
    </div>
</body>

</html>