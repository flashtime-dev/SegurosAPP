<?php

namespace Database\Seeders;

use App\Models\Compania;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companias = [
            ['nombre' => 'Allianz', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Allianz.png'],
            ['nombre' => 'Axa', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Axa.png'],
            ['nombre' => 'Caser', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Caser.png'],
            ['nombre' => 'Fiact', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Fiact.png'],
            ['nombre' => 'Generali', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Generali.png'],
            ['nombre' => 'GeneraliOn', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/GeneraliOn.png'],
            ['nombre' => 'Helvetia', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Helvetia.png'],
            ['nombre' => 'Mapfre', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Mapfre.png'],
            ['nombre' => 'MGS', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/MGS.png'],
            ['nombre' => 'Mutua de propietarios', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Mutua.png'],
            ['nombre' => 'Ocaso', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Ocaso.png'],
            ['nombre' => 'Occident', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Occident.png'],
            ['nombre' => 'Pelayo', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/pelayo.png'],
            ['nombre' => 'Preventiva', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Preventiva.png'],
            ['nombre' => 'Reale', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Reale.png'],
            ['nombre' => 'Santa Lucía', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/SantaLucia.png'],
            ['nombre' => 'Zurich', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Zurich.png'],
            ['nombre' => 'Patria Hispana', 'url_logo' => 'https://segurosaxarquia.com/wp-content/uploads/2025/01/Patria.png'],
        ];

        foreach ($companias as $compania) {
            Compania::create($compania);
        }
    }
}
