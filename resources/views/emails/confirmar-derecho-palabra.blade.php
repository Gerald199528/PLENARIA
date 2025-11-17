<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f5f5f5; padding: 20px;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="text-align: center; border-bottom: 3px solid #3b82f6; padding-bottom: 20px; margin-bottom: 30px;">
            <h1 style="color: #1f2937; margin: 0; font-size: 28px;">
                <i style="color: #3b82f6;">✓</i> Tu Solicitud ha sido Confirmada
            </h1>
        </div>

        <!-- Greeting -->
        <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
            Estimado(a) <strong>{{ $nombre }}</strong>,
        </p>

        <!-- Body -->
        <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
            <p style="color: #1e40af; margin: 0; font-size: 15px;">
                <strong>¡Buenas noticias!</strong> Tu solicitud de <strong>Derecho de Palabra</strong> ha sido confirmada exitosamente.
            </p>
        </div>

        <!-- Details -->
        <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <h3 style="color: #1f2937; font-size: 16px; margin-top: 0; margin-bottom: 15px;">Detalles de tu Solicitud:</h3>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold; width: 40%;">Nombre:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">{{ $nombre }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold;">Cédula:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">{{ $cedula }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold;">Email:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">{{ $email }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #6b7280; font-weight: bold;">Fecha de Confirmación:</td>
                    <td style="padding: 10px 0; color: #1f2937;">{{ $fecha }}</td>
                </tr>
            </table>
        </div>

        <!-- Observaciones -->
        @if($observaciones)
        <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
            <h3 style="color: #92400e; margin: 0 0 10px 0; font-size: 15px;">Observaciones:</h3>
            <p style="color: #78350f; margin: 0; line-height: 1.6; white-space: pre-wrap;">{{ $observaciones }}</p>
        </div>
        @endif

        <!-- Footer Message -->
        <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
            <p style="color: #166534; margin: 0; font-size: 14px;">
                Si tienes alguna duda o necesitas más información, por favor contacta con el administrador del sistema.
            </p>
        </div>

        <!-- Closing -->
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 10px;">
            Agradecemos tu participación en nuestras sesiones municipales.
        </p>

        <!-- Signature -->
        <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 30px;">
            <p style="color: #374151; font-size: 14px; margin: 0;">
                <strong>Sistema de Derecho de Palabra</strong><br>
                <span style="color: #9ca3af;">{{ config('app.name') }}</span>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px;">
        Este es un correo automático, por favor no respondas a este mensaje.
    </p>
</div>