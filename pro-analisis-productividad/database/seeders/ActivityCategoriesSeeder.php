<?php
// database/seeders/ActivityCategoriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityCategory;
use App\Models\ProductivityTag;

class ActivityCategoriesSeeder extends Seeder
{
    public function run()
    {
        // Categorías de actividad del sistema (esto está bien)
        $systemCategories = [
            [
                'name' => 'Desarrollo',
                'color' => '#10b981',
                'icon' => '💻',
                'description' => 'Desarrollo de software y programación',
                'is_productive' => true,
                'productivity_weight' => 90,
                'is_system' => true
            ],
            [
                'name' => 'Reuniones',
                'color' => '#3b82f6',
                'icon' => '👥',
                'description' => 'Reuniones y coordinación de equipo',
                'is_productive' => true,
                'productivity_weight' => 70,
                'is_system' => true
            ],
            [
                'name' => 'Aprendizaje',
                'color' => '#8b5cf6',
                'icon' => '📚',
                'description' => 'Estudio y aprendizaje de nuevas tecnologías',
                'is_productive' => true,
                'productivity_weight' => 85,
                'is_system' => true
            ],
            [
                'name' => 'Planificación',
                'color' => '#f59e0b',
                'icon' => '📋',
                'description' => 'Planificación y organización de tareas',
                'is_productive' => true,
                'productivity_weight' => 80,
                'is_system' => true
            ],
            [
                'name' => 'Descanso',
                'color' => '#6b7280',
                'icon' => '☕',
                'description' => 'Pausas activas y descansos',
                'is_productive' => false,
                'productivity_weight' => 30,
                'is_system' => true
            ],
            [
                'name' => 'Distracciones',
                'color' => '#ef4444',
                'icon' => '📱',
                'description' => 'Distracciones y tiempo no productivo',
                'is_productive' => false,
                'productivity_weight' => 10,
                'is_system' => true
            ]
        ];

        foreach ($systemCategories as $category) {
            ActivityCategory::create($category);
        }

        // Etiquetas de productividad del sistema - TIPOS CORREGIDOS
        $systemTags = [
            // Enfoque y Concentración (focus)
            [
                'name' => 'Alta Concentración',
                'type' => 'focus',
                'color' => '#10b981',
                'impact_score' => 25,
                'description' => 'Estado de flujo y alta concentración',
                'is_system' => true
            ],
            [
                'name' => 'Concentración Media',
                'type' => 'focus',
                'color' => '#f59e0b',
                'impact_score' => 10,
                'description' => 'Concentración normal',
                'is_system' => true
            ],
            [
                'name' => 'Baja Concentración',
                'type' => 'focus',
                'color' => '#ef4444',
                'impact_score' => -15,
                'description' => 'Dificultad para concentrarse',
                'is_system' => true
            ],

            // Energía y Estado (energy)
            [
                'name' => 'Energía Alta',
                'type' => 'energy',
                'color' => '#10b981',
                'impact_score' => 20,
                'description' => 'Nivel de energía alto',
                'is_system' => true
            ],
            [
                'name' => 'Energía Normal',
                'type' => 'energy',
                'color' => '#f59e0b',
                'impact_score' => 5,
                'description' => 'Nivel de energía normal',
                'is_system' => true
            ],
            [
                'name' => 'Fatiga',
                'type' => 'energy',
                'color' => '#ef4444',
                'impact_score' => -25,
                'description' => 'Cansancio y fatiga',
                'is_system' => true
            ],

            // Entorno de Trabajo (environment)
            [
                'name' => 'Oficina Silenciosa',
                'type' => 'environment',
                'color' => '#10b981',
                'impact_score' => 15,
                'description' => 'Entorno de trabajo óptimo',
                'is_system' => true
            ],
            [
                'name' => 'Home Office',
                'type' => 'environment',
                'color' => '#3b82f6',
                'impact_score' => 10,
                'description' => 'Trabajo desde casa',
                'is_system' => true
            ],
            [
                'name' => 'Espacio Ruidoso',
                'type' => 'environment',
                'color' => '#ef4444',
                'impact_score' => -10,
                'description' => 'Entorno con distracciones auditivas',
                'is_system' => true
            ],

            // Estado de Ánimo (mood)
            [
                'name' => 'Motivado',
                'type' => 'mood',
                'color' => '#10b981',
                'impact_score' => 20,
                'description' => 'Estado de ánimo positivo y motivado',
                'is_system' => true
            ],
            [
                'name' => 'Neutral',
                'type' => 'mood',
                'color' => '#6b7280',
                'impact_score' => 0,
                'description' => 'Estado de ánimo normal',
                'is_system' => true
            ],
            [
                'name' => 'Estresado',
                'type' => 'mood',
                'color' => '#ef4444',
                'impact_score' => -15,
                'description' => 'Estado de estrés o ansiedad',
                'is_system' => true
            ],

            // Distracciones (distraction)
            [
                'name' => 'Sin Distracciones',
                'type' => 'distraction',
                'color' => '#10b981',
                'impact_score' => 15,
                'description' => 'Sesión sin interrupciones',
                'is_system' => true
            ],
            [
                'name' => 'Distracciones Mínimas',
                'type' => 'distraction',
                'color' => '#f59e0b',
                'impact_score' => 0,
                'description' => 'Algunas distracciones menores',
                'is_system' => true
            ],
            [
                'name' => 'Muchas Interrupciones',
                'type' => 'distraction',
                'color' => '#ef4444',
                'impact_score' => -20,
                'description' => 'Sesión con muchas interrupciones',
                'is_system' => true
            ]
        ];

        foreach ($systemTags as $tag) {
            ProductivityTag::create($tag);
        }
    }
}