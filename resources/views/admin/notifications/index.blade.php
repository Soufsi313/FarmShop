@extends('admin.layout')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell text-primary me-2"></i>
            Notifications
        </h1>
        <div class="d-flex gap-2">
            <button id="markAllRead" class="btn btn-sm btn-success">
                <i class="fas fa-check-double me-1"></i>Tout marquer lu
            </button>
            <span id="notificationCount" class="badge bg-warning">0</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        Notifications récentes
                    </h6>
                </div>
                <div class="card-body">
                    <div id="notificationsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des notifications...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Charger les notifications au démarrage
    loadNotifications();
    
    // Recharger les notifications toutes les 30 secondes
    setInterval(loadNotifications, 30000);
    
    // Marquer toutes les notifications comme lues
    $('#markAllRead').click(function() {
        $.ajax({
            url: "{{ route('admin.notifications.mark-all-read') }}",
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    loadNotifications();
                    toastr.success('Toutes les notifications ont été marquées comme lues');
                }
            },
            error: function() {
                toastr.error('Erreur lors de la mise à jour');
            }
        });
    });
});

function loadNotifications() {
    $.ajax({
        url: "{{ route('admin.notifications.index') }}",
        method: 'GET',
        success: function(response) {
            if (response.success) {
                updateNotificationsList(response.notifications);
                updateNotificationCount(response.count);
            }
        },
        error: function() {
            $('#notificationsList').html(`
                <div class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <p>Erreur lors du chargement des notifications</p>
                </div>
            `);
        }
    });
}

function updateNotificationsList(notifications) {
    if (notifications.length === 0) {
        $('#notificationsList').html(`
            <div class="text-center py-4 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p>Aucune notification</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    notifications.forEach(function(notification) {
        let icon = getNotificationIcon(notification.type);
        let color = getNotificationColor(notification.type);
        let title = getNotificationTitle(notification.type, notification.data);
        let description = getNotificationDescription(notification.type, notification.data);
        
        html += `
            <div class="notification-item border-left-${color} p-3 mb-3 bg-light" data-id="${notification.id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex">
                        <div class="text-${color} me-3">
                            <i class="${icon} fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${title}</h6>
                            <p class="mb-1 text-muted">${description}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>${notification.created_at}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-success mark-read-btn" data-id="${notification.id}">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${notification.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#notificationsList').html(html);
    
    // Attacher les événements
    $('.mark-read-btn').click(function() {
        markAsRead($(this).data('id'));
    });
    
    $('.delete-btn').click(function() {
        deleteNotification($(this).data('id'));
    });
}

function updateNotificationCount(count) {
    $('#notificationCount').text(count);
    
    // Mettre à jour le badge dans la navigation principale aussi
    $('.admin-header .badge').text(count);
    if (count === 0) {
        $('.admin-header .badge').addClass('d-none');
    } else {
        $('.admin-header .badge').removeClass('d-none');
    }
}

function markAsRead(id) {
    $.ajax({
        url: `/admin/notifications/${id}/read`,
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                loadNotifications();
                toastr.success('Notification marquée comme lue');
            }
        },
        error: function() {
            toastr.error('Erreur lors de la mise à jour');
        }
    });
}

function deleteNotification(id) {
    if (confirm('Supprimer cette notification ?')) {
        $.ajax({
            url: `/admin/notifications/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    loadNotifications();
                    toastr.success('Notification supprimée');
                }
            },
            error: function() {
                toastr.error('Erreur lors de la suppression');
            }
        });
    }
}

function getNotificationIcon(type) {
    const icons = {
        'App\\Notifications\\ProductLowStock': 'fas fa-exclamation-triangle',
        'App\\Notifications\\ProductOutOfStock': 'fas fa-times-circle',
        'App\\Notifications\\OrderStatusChanged': 'fas fa-shopping-cart',
        'App\\Notifications\\RentalOverdue': 'fas fa-clock',
        'default': 'fas fa-bell'
    };
    
    return icons[type] || icons['default'];
}

function getNotificationColor(type) {
    const colors = {
        'App\\Notifications\\ProductLowStock': 'warning',
        'App\\Notifications\\ProductOutOfStock': 'danger',
        'App\\Notifications\\OrderStatusChanged': 'info',
        'App\\Notifications\\RentalOverdue': 'danger',
        'default': 'primary'
    };
    
    return colors[type] || colors['default'];
}

function getNotificationTitle(type, data) {
    const titles = {
        'App\\Notifications\\ProductLowStock': '⚠️ Stock faible',
        'App\\Notifications\\ProductOutOfStock': '🚨 Rupture de stock',
        'App\\Notifications\\OrderStatusChanged': '📦 Commande mise à jour',
        'App\\Notifications\\RentalOverdue': '⏰ Location en retard',
        'default': 'Notification'
    };
    
    return titles[type] || titles['default'];
}

function getNotificationDescription(type, data) {
    switch(type) {
        case 'App\\Notifications\\ProductLowStock':
            return `Le produit "${data.product_name}" a atteint le seuil critique (${data.quantity} restant)`;
        case 'App\\Notifications\\ProductOutOfStock':
            return `Le produit "${data.product_name}" est en rupture de stock`;
        case 'App\\Notifications\\OrderStatusChanged':
            return `Commande ${data.order_number} : ${data.status}`;
        case 'App\\Notifications\\RentalOverdue':
            return `Location ${data.rental_number} en retard de ${data.days_overdue} jour(s)`;
        default:
            return JSON.stringify(data);
    }
}
</script>

<style>
.notification-item {
    transition: all 0.3s ease;
    border-radius: 8px;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.border-left-primary {
    border-left: 4px solid #007bff !important;
}
</style>
@endsection
