<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f5f5f5; padding: 20px;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Header dinámico según estado -->
        <div style="text-align: center; border-bottom: 3px solid {{ $estado === 'aprobado' ? '#10b981' : ($estado === 'rechazado' ? '#ef4444' : '#6b7280') }}; padding-bottom: 20px; margin-bottom: 30px;">
            @if($estado === 'aprobado')
                <h1 style="color: #10b981; margin: 0; font-size: 28px;">
                    <span style="color: #10b981;">✓</span> Tu Solicitud ha sido Aprobada
                </h1>
            @elseif($estado === 'rechazado')
                <h1 style="color: #ef4444; margin: 0; font-size: 28px;">
                    <span style="color: #ef4444;">✕</span> Tu Solicitud ha sido Rechazada
                </h1>
            @else
                <h1 style="color: #6b7280; margin: 0; font-size: 28px;">
                    <span style="color: #6b7280;">●</span> Actualización de tu Solicitud
                </h1>
            @endif
        </div>

        <!-- Greeting -->
        <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
            Estimado(a) <strong>{{ $nombre }}</strong>,
        </p>

        <!-- Body dinámico según estado -->
        @if($estado === 'aprobado')
            <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
                <p style="color: #166534; margin: 0; font-size: 15px;">
                    <strong>¡Buenas noticias!</strong> Tu solicitud de <strong>Atención Ciudadana</strong> ha sido <strong style="color: #10b981;">APROBADA</strong> exitosamente. Pronto recibirás seguimiento sobre tu caso.
                </p>
            </div>
        @elseif($estado === 'rechazado')
            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
                <p style="color: #7f1d1d; margin: 0; font-size: 15px;">
                    <strong>Información importante:</strong> Tu solicitud de <strong>Atención Ciudadana</strong> ha sido <strong style="color: #ef4444;">RECHAZADA</strong>. Por favor, revisa los comentarios a continuación para conocer los motivos.
                </p>
            </div>
        @else
            <div style="background-color: #f9fafb; border-left: 4px solid #6b7280; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
                <p style="color: #374151; margin: 0; font-size: 15px;">
                    Hemos actualizado el estado de tu solicitud de <strong>Atención Ciudadana</strong>. Por favor, revisa los detalles a continuación.
                </p>
            </div>
        @endif

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
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold;">Teléfono:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">{{ $telefono }}</td>
                </tr>
                @if($whatsapp && $whatsapp !== 'N/A')
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold;">WhatsApp:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">{{ $whatsapp }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold;">Tipo de Solicitud:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">{{ $tipo_solicitud }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold; vertical-align: top;">Descripción:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937; white-space: pre-wrap;">{{ $descripcion }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-weight: bold;">Estado:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #1f2937;">
                        @if($estado === 'aprobado')
                            <span style="color: #10b981; font-weight: bold;">✓ Aprobado</span>
                        @elseif($estado === 'rechazado')
                            <span style="color: #ef4444; font-weight: bold;">✕ Rechazado</span>
                        @else
                            <span style="color: #f59e0b; font-weight: bold;">● Pendiente</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #6b7280; font-weight: bold;">Fecha de Respuesta:</td>
                    <td style="padding: 10px 0; color: #1f2937;">{{ $fecha }}</td>
                </tr>
            </table>
        </div>

        <!-- Respuesta/Observaciones -->
        @if($respuesta)
            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
                <h3 style="color: #92400e; margin: 0 0 10px 0; font-size: 15px;">
                    @if($estado === 'aprobado')
                        Comentarios del Administrador:
                    @elseif($estado === 'rechazado')
                        Motivos del Rechazo:
                    @else
                        Observaciones:
                    @endif
                </h3>
                <p style="color: #78350f; margin: 0; line-height: 1.6; white-space: pre-wrap;">{{ $respuesta }}</p>
            </div>
        @endif

        <!-- Footer Message -->
        <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 25px; border-radius: 5px;">
            <p style="color: #166534; margin: 0; font-size: 14px;">
                Si tienes alguna duda o necesitas más información, por favor contacta con el área de atención ciudadana.
            </p>
        </div>

        <!-- Closing -->
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 10px;">
            Agradecemos tu participación y confianza en nuestro sistema de atención ciudadana.
        </p>



    <!-- Footer -->
    <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px;">
        Este es un correo automático, por favor no respondas a este mensaje.
    </p>
</div>
```
