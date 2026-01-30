<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            font-family: Arial, sans-serif;
            color: #1a1a1a;
            line-height: 1.6;
            background: white;
        }
        body {
            padding: 0;
            margin: 0;
        }
        .container {
            width: 100%;
            background: white;
        }
        
        /* Banner */
        .banner {
            background-color: {{ $primaryColor ?? '#0f2440' }};
            padding: 35px 40px;
            position: relative;
            border-bottom: 4px solid {{ $secondaryColor ?? '#00d4ff' }};
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .banner-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .header-text {
            color: white;
        }
        
        .header-text h1 {
            font-size: 30px;
            font-weight: bold;
            margin: 0 0 8px 0;
            color: white;
        }
        
        .header-text p {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #e8ebf0;
            margin: 0;
        }
        
        /* Contenido con logo de fondo */
        .content {
            padding: 35px 40px;
            background: white;
            position: relative;
            min-height: 500px;
        }
        
        .logo-watermark {
            position: absolute;
            top: 19%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
        }
        
        .logo-watermark img {
            max-width: 400px;
            height: auto;
        }
        
        .info-section {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: {{ $primaryColor ?? '#0f2440' }};
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid {{ $secondaryColor ?? '#00d4ff' }};
            display: inline-block;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-item {
            display: table-cell;
            width: 50%;
            padding: 12px 0 12px 0;
            border-bottom: 1px solid #e8ebf0;
            vertical-align: top;
            padding-right: 30px;
        }
        
        .info-item-full {
            display: table-cell;
            width: 100%;
            padding: 12px 0 12px 0;
            border-bottom: 1px solid #e8ebf0;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            color: {{ $primaryColor ?? '#0f2440' }};
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 5px;
            opacity: 0.8;
        }
        
        .info-value {
            color: #2c3e50;
            font-size: 13px;
            font-weight: normal;
            word-break: break-word;
        }
        
        .info-value-highlight {
            background-color: #f0f8ff;
            padding: 5px 8px;
            border-left: 3px solid {{ $secondaryColor ?? '#00d4ff' }};
            font-weight: bold;
            display: inline-block;
        }

        .chart-section {
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 20px;
            border: 2px solid {{ $secondaryColor ?? '#00d4ff' }};
            border-radius: 8px;
            text-align: center;
            page-break-inside: avoid;
            position: relative;
            z-index: 1;
        }

        .chart-section h3 {
            font-size: 14px;
            font-weight: bold;
            color: {{ $primaryColor ?? '#0f2440' }};
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .chart-image {
            max-width: 100%;
            width: 90%;
            height: auto;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            display: inline-block;
        }
        
        /* Badges/Tags */
        .badge-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f0f8ff;
            border-left: 4px solid {{ $secondaryColor ?? '#00d4ff' }};
            position: relative;
            z-index: 1;
        }
        
        .badge-title {
            display: block;
            font-size: 10px;
            font-weight: bold;
            color: {{ $primaryColor ?? '#0f2440' }};
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .badge-items {
            display: block;
            text-align: center;
        }
        
        .badge-item {
            display: inline-block;
            background-color: {{ $primaryColor ?? '#0f2440' }};
            color: white;
            padding: 6px 102px;
            margin: 3px auto;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: capitalize;
            border: 1px solid {{ $secondaryColor ?? '#00d4ff' }};
        }
        
        /* Para impresión */
        @page {
            size: A4;
            margin: 0;
        }
        
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            body, html {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .container {
                box-shadow: none !important;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Banner -->
        <div class="banner">
            <div class="banner-content">
                <div class="header-text">
                        @if($image ?? null)
                <div style="text-align: center; margin-bottom: 10px; position: relative; z-index: 1;">
                <img src="{{ $image }}" alt="Imagen" style="max-width: 250px; max-height: 150px;
                display: block; margin: 0 auto; border: 2px solid {{ $secondaryColor ?? '#00d4ff' }};
                border-radius:  8px; background-color: #fff;">
                </div>
            @endif
                    <h1>{{ $title }}</h1>
                    <p>{{ $subtitle }}</p>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="content">
            @if($logo_icon ?? null)
                <div class="logo-watermark">
                    <img src="{{ $logo_icon }}" alt="Logo">
                </div>
            @endif
     

            <div class="info-section">
                <div class="section-title">{{ $sectionTitle ?? 'Información' }}</div>
                
                <table class="info-grid">
                    @php
                        $count = 0;
                        $itemsPerRow = 2;
                    @endphp
                    @foreach($fields as $field)
                        @if($count % $itemsPerRow === 0)
                            <tr class="info-row">
                        @endif
                        
                        <td class="info-item">
                            <span class="info-label">{{ $field['label'] }}</span>
                            <span class="info-value">
                                @if($field['highlight'] ?? false)
                                    <span class="info-value-highlight">{{ $field['value'] ?? 'N/A' }}</span>
                                @else
                                    {{ $field['value'] ?? 'N/A' }}
                                @endif
                            </span>
                        </td>
                        
                        @if(($count + 1) % $itemsPerRow === 0 || $loop->last)
                            @if($loop->last && ($count + 1) % $itemsPerRow !== 0)
                                <td class="info-item"></td>
                            @endif
                            </tr>
                        @endif
                        
                        @php $count++ @endphp
                    @endforeach
                </table>
            </div>

            @if($chartImage ?? null)
                <div class="chart-section">
                    <h3> Visualización de Datos</h3>
                    <img src="{{ $chartImage }}" alt="Gráfica de Crónicas" class="chart-image">
                </div>
            @endif

            @if($tags ?? null)
                <div class="badge-section">
                    <span class="badge-title">{{ $badgeTitle ?? 'Etiquetas' }}</span>
                    <div class="badge-items">
                        @foreach($tags as $tag)
                            <span class="badge-item">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
</body>
</html>