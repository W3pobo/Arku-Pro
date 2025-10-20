<div class="card-custom mb-4">
    <div class="card-header-custom">
        <h6 class="card-title-custom mb-0">
            <i class="fas fa-magic me-2 text-accent"></i>Recomendaciones Inteligentes
        </h6>
    </div>
    <div class="card-body-custom p-3">
        <div id="sidebar-recommendations">
            <div class="d-flex justify-content-center">
                <div class="spinner-border spinner-border-sm text-accent" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSidebarRecommendations();
    
    function loadSidebarRecommendations() {
        fetch('{{ route("api.recommendations") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('sidebar-recommendations');
                
                if (data.success && data.recommendations.length > 0) {
                    let html = '';
                    
                    data.recommendations.slice(0, 3).forEach(task => {
                        html += `
                        <div class="recommendation-item mb-3 p-2 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="mb-1 text-main text-truncate" style="max-width: 70%;">${task.title}</h6>
                                <span class="badge-custom bg-${task.priority === 'high' ? 'danger' : (task.priority === 'medium' ? 'warning' : 'success')}">
                                    ${task.priority}
                                </span>
                            </div>
                            ${task.category ? `<small class="text-secondary d-block">${task.category}</small>` : ''}
                            <button class="btn btn-outline-accent btn-sm w-100 mt-2 view-task-sidebar" 
                                    data-task-id="${task.id}">
                                <i class="fas fa-eye me-1"></i>Ver
                            </button>
                        </div>
                        `;
                    });
                    
                    container.innerHTML = html;
                    
                    // Agregar event listeners a los botones
                    document.querySelectorAll('.view-task-sidebar').forEach(button => {
                        button.addEventListener('click', function() {
                            const taskId = this.getAttribute('data-task-id');
                            recordInteraction(taskId, 'view').then(() => {
                                window.location.href = `/tasks/${taskId}`;
                            });
                        });
                    });
                    
                } else {
                    container.innerHTML = `
                    <div class="text-center text-secondary">
                        <i class="fas fa-search fa-2x mb-2 text-accent"></i>
                        <p class="small mb-0 text-main">Completa tareas para ver recomendaciones</p>
                    </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading recommendations:', error);
                document.getElementById('sidebar-recommendations').innerHTML = `
                <div class="text-center text-secondary">
                    <p class="small mb-0 text-main">Error cargando recomendaciones</p>
                </div>
                `;
            });
    }
    
    function recordInteraction(taskId, interactionType) {
        return fetch('{{ route("api.record_interaction") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                task_id: taskId,
                interaction_type: interactionType
            })
        });
    }
});
</script>