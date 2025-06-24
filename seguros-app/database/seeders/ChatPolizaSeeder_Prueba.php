<?php

namespace Database\Seeders;

use App\Models\ChatPoliza;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatPolizaSeeder_Prueba extends Seeder
{
    public function run(): void
    {
        ChatPoliza::create([
            'id_poliza' => 1,
            'id_usuario' => 1,
            'mensaje' => 'Hola, espero que estés bien. Quería comentarte que estuve revisando detenidamente los términos y condiciones de la póliza de vida que contratamos el mes pasado. Me surgieron algunas dudas importantes que me gustaría aclarar para estar completamente seguro de qué cobertura tengo. Por ejemplo, en la sección que habla sobre exclusiones, no me quedó claro si los daños causados por desastres naturales están cubiertos o no. Además, quisiera saber si existe alguna limitación en cuanto a la edad o condiciones preexistentes que puedan afectar la validez del seguro. Me sería de gran ayuda que me pudieras detallar estos puntos para poder explicarlos también a mi familia. Muchas gracias de antemano.',
            'adjunto' => false,
        ]);

        ChatPoliza::create([
            'id_poliza' => 1,
            'id_usuario' => 2,
            'mensaje' => 'Hola, muchas gracias por tu mensaje tan detallado y por la confianza en consultarnos. Respecto a tus dudas, puedo confirmarte que la póliza cubre daños causados por desastres naturales, siempre y cuando estén dentro de las condiciones especificadas en el contrato. Sin embargo, hay algunas exclusiones puntuales como daños derivados de terremotos en ciertas zonas geográficas que se mencionan explícitamente en el documento. Sobre la edad y condiciones preexistentes, la póliza tiene un límite máximo de edad para su contratación de 65 años, y ciertas enfermedades preexistentes pueden requerir un análisis adicional antes de la aceptación definitiva del riesgo. Si quieres, puedo enviarte un resumen actualizado con toda esta información para que tengas un documento de referencia. También estoy disponible para una llamada para explicarte cualquier punto con mayor detalle.',
            'adjunto' => false,
        ]);

        ChatPoliza::create([
            'id_poliza' => 1,
            'id_usuario' => 1,
            'mensaje' => 'Perfecto, muchas gracias por la aclaración. Me encantaría recibir ese resumen actualizado que mencionas para tenerlo a mano. Por otro lado, quería preguntarte cómo es el proceso en caso de que necesitemos hacer una reclamación. ¿Qué documentos son indispensables? ¿Hay algún plazo máximo para presentar la reclamación después de que ocurra un siniestro? Además, quisiera saber si el proceso es 100% online o si es necesario acudir a alguna oficina física para completar algunos trámites. Me preocupa también cómo se gestiona la comunicación durante el proceso para estar siempre informado del estado de la reclamación.',
            'adjunto' => false,
        ]);

        ChatPoliza::create([
            'id_poliza' => 1,
            'id_usuario' => 2,
            'mensaje' => 'Claro, te explico con gusto. Para presentar una reclamación por siniestro, los documentos indispensables incluyen el parte oficial de siniestro, fotografías claras y detalladas del daño, informes médicos o periciales cuando corresponda, y cualquier documento adicional que justifique la reclamación, como facturas o presupuestos de reparación. Es fundamental presentar la reclamación dentro de los 30 días siguientes a la ocurrencia del siniestro, para evitar problemas de validez. El proceso es mayormente online: puedes subir toda la documentación a nuestro portal seguro o enviarla por correo electrónico. Sin embargo, en casos complejos o cuando sea necesario, te podríamos citar en alguna de nuestras oficinas para una revisión presencial. Durante todo el proceso, tendrás acceso a un sistema de seguimiento en tiempo real donde podrás ver cada actualización y también tendrás un asesor asignado que te contactará periódicamente para informarte sobre el estado de tu reclamación. Estamos comprometidos a que el proceso sea lo más transparente y sencillo posible para ti.',
            'adjunto' => false,
        ]);

        ChatPoliza::create([
            'id_poliza' => 2,
            'id_usuario' => 3,
            'mensaje' => 'Buenos días, quisiera consultar acerca de la póliza de salud que contraté recientemente. Estoy planeando un viaje al extranjero y necesito saber si las consultas médicas y tratamientos realizados fuera de nuestro país están cubiertos. Además, ¿podrías indicarme cuáles son los límites de cobertura para gastos médicos internacionales? También quisiera saber si es posible recibir atención en clínicas privadas fuera del país y si debo notificar a la aseguradora antes de acudir a una consulta o intervención médica. Por último, agradecería si me explicaras el procedimiento para el reembolso de gastos, qué documentos debo conservar y en qué plazo puedo esperar la devolución del dinero.',
            'adjunto' => false,
        ]);

        ChatPoliza::create([
            'id_poliza' => 2,
            'id_usuario' => 1,
            'mensaje' => 'Hola, gracias por tu consulta tan completa. En efecto, tu póliza de salud incluye cobertura para consultas médicas y tratamientos en clínicas privadas dentro y fuera del país, siempre y cuando estén dentro de los límites establecidos en el contrato, que para gastos médicos internacionales es de hasta 10,000 euros anuales. Es recomendable que notifiques a la aseguradora antes de recibir atención médica fuera del país para que podamos coordinar y validar la cobertura, aunque en emergencias la atención está garantizada sin previa notificación. Para el reembolso, debes conservar todas las facturas originales, informes médicos y cualquier documento relacionado con la atención. La documentación debe enviarse en un plazo máximo de 30 días desde la prestación del servicio. Una vez recibida toda la documentación, el trámite de reembolso se procesa en aproximadamente 20 días hábiles. También ofrecemos una línea de asistencia médica internacional 24/7 para emergencias, donde podrás recibir soporte inmediato.',
            'adjunto' => false,
        ]);
        
        ChatPoliza::create([
            'id_poliza' => 2,
            'id_usuario' => 3,
            'mensaje' => 'Muchas gracias por toda la información. La línea de asistencia médica internacional 24/7 me parece muy útil, ¿podrías indicarme cómo puedo contactar con ella? ¿Está disponible a través de una aplicación móvil o es un número telefónico? Además, quisiera saber si existen condiciones específicas para su uso o si está disponible para cualquier tipo de emergencia médica mientras estoy fuera del país.',
            'adjunto' => false,
        ]);
        
        ChatPoliza::create([
            'id_poliza' => 2,
            'id_usuario' => 1,
            'mensaje' => 'Por supuesto, la línea de asistencia médica está disponible a través de un número telefónico que te proporcionaremos en tu tarjeta de asegurado y también mediante nuestra app móvil oficial, donde podrás iniciar una llamada o chat en vivo con un especialista. La asistencia está disponible para cualquier emergencia médica cubierta por la póliza, las 24 horas del día, los 7 días de la semana, sin importar el país donde te encuentres. El equipo está capacitado para ayudarte con la coordinación de centros médicos, ambulancias y transporte sanitario si fuera necesario. Además, te ofrecen asesoría médica básica y seguimiento hasta que puedas regresar a casa o continuar tu tratamiento. Si necesitas, puedo enviarte el manual de usuario para la app junto con los números de contacto.',
            'adjunto' => false,
        ]);
    }
}
