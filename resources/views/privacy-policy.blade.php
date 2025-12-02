<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - Tus Turnos</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .last-updated {
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 30px;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
        .contact-info {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Política de Privacidad</h1>
        <p class="last-updated">Última actualización: {{ date('d/m/Y') }}</p>

        <h2>1. Información que Recopilamos</h2>
        <p>En <strong>Tus Turnos</strong>, recopilamos la siguiente información para brindar nuestro servicio de gestión de citas médicas:</p>
        <ul>
            <li><strong>Información Personal:</strong> Nombre completo, número de teléfono, correo electrónico</li>
            <li><strong>Información de Citas:</strong> Fechas, horarios, especialidad médica, doctor asignado</li>
            <li><strong>Información de Comunicación:</strong> Mensajes enviados a través de WhatsApp y correo electrónico</li>
        </ul>

        <h2>2. Cómo Usamos tu Información</h2>
        <p>Utilizamos tu información únicamente para:</p>
        <ul>
            <li>Gestionar y confirmar tus citas médicas</li>
            <li>Enviarte recordatorios de citas por WhatsApp y correo electrónico</li>
            <li>Comunicarnos contigo sobre cambios o cancelaciones</li>
            <li>Mejorar nuestro servicio de gestión de turnos</li>
        </ul>

        <h2>3. Comunicaciones por WhatsApp</h2>
        <p>Al proporcionar tu número de teléfono, aceptas recibir:</p>
        <ul>
            <li>Confirmaciones de citas programadas</li>
            <li>Recordatorios de citas próximas</li>
            <li>Notificaciones importantes sobre tus turnos</li>
        </ul>
        <p>Puedes solicitar dejar de recibir estos mensajes en cualquier momento contactándonos.</p>

        <h2>4. Compartición de Información</h2>
        <p>Tu información personal <strong>NO</strong> es compartida, vendida o alquilada a terceros, excepto:</p>
        <ul>
            <li>Con profesionales médicos involucrados en tu atención</li>
            <li>Cuando sea requerido por ley</li>
            <li>Con proveedores de servicios que nos ayudan a operar (ej: Meta/WhatsApp para envío de mensajes)</li>
        </ul>

        <h2>5. Seguridad de los Datos</h2>
        <p>Implementamos medidas de seguridad apropiadas para proteger tu información personal contra acceso no autorizado, alteración o destrucción.</p>

        <h2>6. Retención de Datos</h2>
        <p>Mantenemos tu información mientras mantengas citas activas con nosotros y durante un período razonable después para cumplir con obligaciones legales.</p>

        <h2>7. Tus Derechos</h2>
        <p>Tienes derecho a:</p>
        <ul>
            <li>Acceder a tu información personal</li>
            <li>Solicitar corrección de datos inexactos</li>
            <li>Solicitar eliminación de tus datos</li>
            <li>Oponerte al procesamiento de tus datos</li>
            <li>Retirar tu consentimiento en cualquier momento</li>
        </ul>

        <h2>8. Cookies y Tecnologías Similares</h2>
        <p>Nuestro sitio web puede utilizar cookies para mejorar tu experiencia. Puedes configurar tu navegador para rechazar cookies.</p>

        <h2>9. Cambios a esta Política</h2>
        <p>Nos reservamos el derecho de actualizar esta política de privacidad. Te notificaremos sobre cambios significativos.</p>

        <h2>10. Consentimiento</h2>
        <p>Al usar nuestro servicio, consientes el procesamiento de tu información según se describe en esta política.</p>

        <div class="contact-info">
            <h2>Contacto</h2>
            <p>Si tienes preguntas sobre esta Política de Privacidad, puedes contactarnos:</p>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:pablofernandonavarro@gmail.com">pablofernandonavarro@gmail.com</a></li>
                <li><strong>Dirección:</strong> Jose C Paz 5723, San Martín, Buenos Aires, Argentina</li>
                <li><strong>Teléfono:</strong> Disponible durante horario de atención</li>
            </ul>
        </div>

        <p style="margin-top: 40px; text-align: center; color: #7f8c8d;">
            &copy; {{ date('Y') }} Tus Turnos - Sistema de Gestión de Citas Médicas
        </p>
    </div>
</body>
</html>
