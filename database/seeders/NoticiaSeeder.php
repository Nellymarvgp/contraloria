<?php

namespace Database\Seeders;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NoticiaSeeder extends Seeder
{
    public function run()
    {
        $admin = User::first();

        $noticias = [
            [
                'titulo' => 'Nueva Plataforma de Gestión Municipal',
                'contenido' => "Nos complace anunciar el lanzamiento de nuestro nuevo Sistema Integral de Gestión Municipal. Esta plataforma moderniza y agiliza los procesos administrativos de la Contraloría Municipal de Independencia.\n\nEntre las características destacadas se incluyen:\n- Gestión eficiente de recursos humanos\n- Control de horarios y asistencia\n- Seguimiento de departamentos y cargos\n- Generación de reportes en tiempo real",
                'publicado' => true,
                'created_at' => now()->subDays(2),
            ],
            [
                'titulo' => 'Jornada de Capacitación en Control Fiscal',
                'contenido' => "La Contraloría Municipal de Independencia llevará a cabo una jornada de capacitación dirigida a funcionarios públicos sobre los procedimientos de control fiscal y la importancia de la transparencia en la gestión pública.\n\nLa actividad se realizará en nuestras instalaciones y contará con la participación de expertos en el área. Los temas a tratar incluyen:\n- Marco legal del control fiscal\n- Procedimientos administrativos\n- Mejores prácticas en gestión pública\n- Herramientas de control y seguimiento",
                'publicado' => true,
                'created_at' => now()->subDays(1),
            ],
            [
                'titulo' => 'Actualización de Normativas y Procedimientos',
                'contenido' => "Como parte de nuestro compromiso con la mejora continua, hemos actualizado las normativas y procedimientos internos de la Contraloría Municipal.\n\nLas actualizaciones incluyen:\n- Nuevos formatos de declaración patrimonial\n- Procedimientos simplificados para auditorías\n- Guías actualizadas para la rendición de cuentas\n- Protocolos de atención ciudadana\n\nEstas mejoras buscan optimizar nuestros servicios y garantizar una gestión más eficiente y transparente.",
                'publicado' => true,
                'created_at' => now(),
            ],
        ];

        foreach ($noticias as $noticia) {
            Noticia::create([
                'titulo' => $noticia['titulo'],
                'slug' => Str::slug($noticia['titulo']),
                'contenido' => $noticia['contenido'],
                'publicado' => $noticia['publicado'],
                'user_id' => $admin->id,
                'created_at' => $noticia['created_at'],
                'updated_at' => $noticia['created_at'],
            ]);
        }
    }
}
