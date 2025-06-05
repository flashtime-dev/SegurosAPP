<?php

namespace Database\Seeders;

use App\Models\ChatSiniestro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatSiniestroSeeder_Prueba extends Seeder
{
    public function run(): void
    {
        ChatSiniestro::create([
            'id_siniestro' => 1,
            'id_usuario' => 1,
            'mensaje' => 'Hola, buenos días. Quisiera informar que el pasado fin de semana sufrí un accidente en mi vehículo asegurado. El impacto fue fuerte y aunque no tuve lesiones graves, los daños materiales son considerables. Me gustaría saber cuál es el procedimiento que debo seguir para iniciar el proceso de reclamación. También quisiera confirmar qué documentos debo preparar y si es necesario realizar una inspección presencial del vehículo antes de que se autorice la reparación. Agradecería si me pueden indicar los plazos aproximados para la evaluación del siniestro y la aprobación de la cobertura. Muchas gracias.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 1,
            'id_usuario' => 2,
            'mensaje' => 'Hola, muchas gracias por contactarnos. Lamentamos el incidente que ha sufrido. Para iniciar el trámite de reclamación por el siniestro, debe presentar el parte de accidente debidamente firmado, fotografías del vehículo y del lugar del siniestro, y copia de su póliza. La inspección del vehículo generalmente se realiza en nuestras instalaciones o en talleres autorizados, y será coordinada por nuestro ajustador una vez recibida la documentación. El proceso de evaluación suele tomar entre 7 y 15 días hábiles, dependiendo de la complejidad del daño. Le mantendremos informado durante todo el proceso y estaremos disponibles para resolver cualquier duda. Si tiene alguna urgencia, también puede acudir a uno de nuestros centros de atención para recibir soporte inmediato.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 1,
            'id_usuario' => 1,
            'mensaje' => 'Perfecto, gracias por la información. Quisiera confirmar también si durante el proceso de reparación puedo solicitar un vehículo de reemplazo y si ese servicio está incluido en la cobertura. Además, ¿existe algún costo adicional que deba considerar para el uso del coche de sustitución? Por último, me interesa saber si puedo realizar el seguimiento del estado del siniestro desde una aplicación móvil o portal web para no tener que llamar constantemente.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 1,
            'id_usuario' => 2,
            'mensaje' => 'Sí, el servicio de vehículo de sustitución está incluido para este tipo de pólizas, y podrá disponer de un coche similar al suyo mientras dure la reparación, sujeto a disponibilidad. No se le cobrará ningún coste adicional por este servicio, ya que está cubierto dentro de la prima que usted paga. Para el seguimiento del siniestro, contamos con un portal web y una aplicación móvil donde podrá consultar el estado actualizado, subir documentos adicionales y comunicarse directamente con su ajustador asignado. Le enviaremos un enlace y credenciales para que pueda acceder a la plataforma en cuanto registremos su reclamación. Si necesita ayuda para usarla, nuestro equipo de soporte está a su disposición para guiarlo paso a paso.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 3,
            'mensaje' => 'Hola, tuve un incendio en mi vivienda y quisiera saber cómo iniciar la reclamación. ¿Qué documentos debo presentar y cuál es el proceso para la evaluación de daños?',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 2,
            'mensaje' => 'Lamentamos lo ocurrido. Para iniciar la reclamación por incendio, debe presentar el parte oficial del siniestro, fotografías de los daños, y el inventario de los bienes afectados. También se programará una inspección presencial con un perito para evaluar los daños estructurales y mobiliario.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 3,
            'mensaje' => '¿El seguro cubre la reconstrucción de las partes estructurales y el reemplazo de muebles? ¿Hay algún límite en la cobertura para estos daños?',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 2,
            'mensaje' => 'Sí, la póliza cubre tanto la reparación o reconstrucción estructural como el reemplazo del mobiliario afectado, hasta el límite establecido en el contrato. Le recomendamos revisar las condiciones específicas para conocer los topes y exclusiones.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 3,
            'mensaje' => 'Perfecto, ¿cuánto tiempo suele tardar la evaluación y el pago de la indemnización?',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 2,
            'mensaje' => 'La evaluación inicial se realiza en un plazo de 5 a 7 días hábiles tras la inspección. El pago de la indemnización se procesa en un máximo de 20 días hábiles después de la aprobación del informe pericial y recepción de toda la documentación.',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 3,
            'mensaje' => 'Muchas gracias por la información y la rapidez en responder. ¿Puedo subir los documentos y fotos a través de la plataforma online o es necesario entregarlos en alguna oficina?',
            'adjunto' => false,
        ]);

        ChatSiniestro::create([
            'id_siniestro' => 2,
            'id_usuario' => 2,
            'mensaje' => 'Puede subir toda la documentación a través de nuestra plataforma online, que es el método más rápido y seguro. Si necesita ayuda con el proceso, también puede contactarnos para soporte o acudir a alguna de nuestras oficinas.',
            'adjunto' => false,
        ]);
    }
}
