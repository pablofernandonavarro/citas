<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condiciones del Servicio - Tus Turnos</title>
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
        <h1>Condiciones del Servicio</h1>
        <p class="last-updated">Última actualización: {{ date('d/m/Y') }}</p>

        <h2>1. Aceptación de los Términos</h2>
        <p>Al acceder y utilizar <strong>Tus Turnos</strong>, aceptas estar sujeto a estos Términos de Servicio y todas las leyes aplicables.</p>

        <h2>2. Descripción del Servicio</h2>
        <p><strong>Tus Turnos</strong> es un sistema de gestión de citas médicas que permite:</p>
        <ul>
            <li>Agendar citas con profesionales de la salud</li>
            <li>Recibir confirmaciones y recordatorios por WhatsApp y correo electrónico</li>
            <li>Gestionar y cancelar citas programadas</li>
        </ul>

        <h2>3. Uso del Servicio</h2>
        <p>Te comprometes a:</p>
        <ul>
            <li>Proporcionar información precisa y actualizada</li>
            <li>Mantener la confidencialidad de tu cuenta</li>
            <li>Notificar cancelaciones con anticipación razonable</li>
            <li>No usar el servicio para fines ilegales o no autorizados</li>
            <li>Respetar los horarios de las citas programadas</li>
        </ul>

        <h2>4. Responsabilidades del Usuario</h2>
        <ul>
            <li>Llegar puntualmente a las citas programadas</li>
            <li>Notificar cambios en tu información de contacto</li>
            <li>Cancelar citas con al menos 24 horas de anticipación cuando sea posible</li>
            <li>Mantener tu número de teléfono y correo electrónico activos</li>
        </ul>

        <h2>5. Comunicaciones</h2>
        <p>Al usar nuestro servicio, aceptas recibir:</p>
        <ul>
            <li>Mensajes de confirmación de citas</li>
            <li>Recordatorios por WhatsApp y correo electrónico</li>
            <li>Notificaciones sobre cambios o cancelaciones</li>
            <li>Información importante sobre el servicio</li>
        </ul>

        <h2>6. Cancelaciones y Reagendamientos</h2>
        <ul>
            <li>Puedes cancelar o reagendar citas contactándonos directamente</li>
            <li>Se recomienda notificar con 24 horas de anticipación</li>
            <li>Las cancelaciones reiteradas sin aviso pueden resultar en restricción del servicio</li>
        </ul>

        <h2>7. Limitación de Responsabilidad</h2>
        <p>El servicio se proporciona "tal cual" sin garantías de ningún tipo. No somos responsables por:</p>
        <ul>
            <li>Interrupciones temporales del servicio</li>
            <li>Problemas de comunicación por WhatsApp o email</li>
            <li>Decisiones médicas o tratamientos proporcionados por profesionales</li>
        </ul>

        <h2>8. Privacidad y Protección de Datos</h2>
        <p>El uso de tu información personal está regido por nuestra <a href="/politica-de-privacidad">Política de Privacidad</a>.</p>

        <h2>9. Modificaciones del Servicio</h2>
        <p>Nos reservamos el derecho de:</p>
        <ul>
            <li>Modificar o discontinuar el servicio temporalmente o permanentemente</li>
            <li>Actualizar estos términos en cualquier momento</li>
            <li>Cambiar las características del servicio</li>
        </ul>

        <h2>10. Terminación</h2>
        <p>Podemos suspender o terminar tu acceso al servicio si:</p>
        <ul>
            <li>Violas estos Términos de Servicio</li>
            <li>Proporcionas información falsa</li>
            <li>Incurres en cancelaciones excesivas sin aviso</li>
            <li>Usas el servicio de manera inapropiada</li>
        </ul>

        <h2>11. Ley Aplicable</h2>
        <p>Estos términos se rigen por las leyes de la República Argentina.</p>

        <h2>12. Consentimiento</h2>
        <p>Al usar nuestro servicio, confirmas que:</p>
        <ul>
            <li>Has leído y comprendido estos términos</li>
            <li>Aceptas recibir comunicaciones por WhatsApp y email</li>
            <li>Proporcionas tu consentimiento para el procesamiento de tus datos según nuestra Política de Privacidad</li>
        </ul>

        <div class="contact-info">
            <h2>Contacto</h2>
            <p>Si tienes preguntas sobre estos Términos de Servicio, puedes contactarnos:</p>
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
