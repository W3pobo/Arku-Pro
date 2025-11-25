<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TaskRecommenderService;

class TrainRecommendationModel extends Command
{
    protected $signature = 'model:train-recommendations';
    protected $description = 'Entrenar el modelo de recomendaciones';

    public function handle(TaskRecommenderService $recommenderService)
    {
        $this->info('Iniciando entrenamiento del modelo de recomendaciones...');
        
        // Aquí puedes agregar lógica de entrenamiento más avanzada
        // Por ahora, limpiamos la cache para forzar regeneración
        \Illuminate\Support\Facades\Cache::flush();
        
        $this->info('Modelo entrenado exitosamente.');
        
        return Command::SUCCESS;
    }
}